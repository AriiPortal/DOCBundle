<?php

namespace Arii\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use mikehaertl\wkhtmlto\Pdf;

class DocController extends Controller
{
    public function toolbarAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        return $this->render('AriiDocBundle:Doc:toolbar.xml.twig', array(), $response );
    }
    
    public function gridAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('file');

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AriiDocBundle:File')->findOneBy(array('file' => $id));
        
        if (!$file) exit();
        
        $id = $file->getId();
                
        $sql = $this->container->get('arii_core.sql');
        $qry = $sql->Select(array('c.ID','NAME','MESSAGE','VALIDATED'))
                .$sql->From(array('DOC_CALL c'))
                .$sql->LeftJoin('DOC_PART p',array('c.PART_ID','p.ID'))
                .$sql->LeftJoin('DOC_USER u',array('c.ID','u.CALL_ID'))
                .$sql->Where(array('FILE_ID' => $id))
                .$sql->OrderBy(array('SEQ'));

        $dhtmlx = $this->container->get('arii_core.db');
        $data = $dhtmlx->Connector('grid');
        $data->event->attach("beforeRender",array($this,"grid_render"));
        $data->render_sql($qry,'ID','NAME,MESSAGE');
    }
    
    function grid_render ($data){
        if ($data->get_value('VALIDATED')=='')
            $data->set_row_color("#fbb4ae");
        elseif ($data->get_value('VALIDATED')>0)
            $data->set_row_color("#ccebc5");
        else 
            $data->set_row_color("#ffffcc");
    }


    public function formAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('id');

        $sql = $this->container->get('arii_core.sql');
        $qry = $sql->Select(array('u.ID as USER_ID','MESSAGE','TITLE','TRIGGERS','CONSTRAINTS','DESCRIPTION','RESTART','VALIDATED','INSTRUCTIONS','c.ID as CALL_ID'))
                .$sql->From(array('DOC_CALL c'))
                .$sql->LeftJoin('DOC_USER u',array('c.ID','u.CALL_ID'));

        $dhtmlx = $this->container->get('arii_core.db');
        $data = $dhtmlx->Connector('form');
        $data->event->attach("beforeRender",array($this,"form_render"));
        $data->render_sql($qry,'c.ID','ID,TITLE,TRIGGERS,CONSTRAINTS,DESCRIPTION,INSTRUCTIONS,CALL_ID,RESTART,VALIDATED');
    }

    function form_render ($data){
        $data->set_value( 'ID', $data->get_value('USER_ID') );
        if ($data->get_value('TITLE')=='')
            $data->set_value( 'TITLE', $data->get_value('MESSAGE') );
    }

    public function saveAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('ID');
         
        if ($id != "")
        {
            $user = $this->getDoctrine()->getRepository("AriiDocBundle:User")->find($id);
        }
        else {
            $user = new \Arii\DocBundle\Entity\User();            
        }

        $call_id = $request->get('CALL_ID');
        if ($call_id!='') {
            $call = $this->getDoctrine()->getRepository("AriiDocBundle:Call")->find($call_id);
            $user->setCall($call);
        }
        
        $user->setTitle($request->get('TITLE'));
        $user->setDescription($request->get('DESCRIPTION'));
        $user->setTriggers($request->get('TRIGGERS'));
        $user->setConstraints($request->get('CONSTRAINTS'));
        $user->setInstructions($request->get('INSTRUCTIONS'));
        $user->setValidated($request->get('VALIDATED'));
        $user->setRestart($request->get('RESTART'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return new Response("success");
    }

    public function deleteUserAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('id');
        
        $user = $this->getDoctrine()->getRepository("AriiDocBundle:User")->find($id);
        $user->remove();
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        
        return new Response("success");
    }
    
    public function docAction()
    {        
        $request = Request::createFromGlobals();
        $id = $request->get('file');

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AriiDocBundle:File')->findOneBy(array('file' => $id));

        if (!$file) {
            
            // On cree la fiche automatiquement
            $File = $this->getDoc($id);

            $file = new \Arii\DocBundle\Entity\File();
            $type = $em->getRepository('AriiDocBundle:Type')->findOneBy(array('name' => $File['type'] ));
            
            $file->setFile($id);
            $file->setType($type);
            $file->setName($File['name']);
            $file->setTitle($File['title']);
            $file->setAuthor($File['author']);
            $file->setDescription($File['description']);            
            $em->persist($file);
            
            foreach ($File['parts'] as $k=>$p) {
                $part = new \Arii\DocBundle\Entity\Part();
                $part->setFile($file);
                $part->setName($p['name']);
                $part->setSeq($k);
                $part->setCode($p['code']);
                $part->setComment($p['comment']);
                $em->persist($part);

                if (isset($p['exploit'])) { 
                    foreach ($p['exploit'] as $k=>$exp) {
                        $call = new \Arii\DocBundle\Entity\Call();
                        $call->setPart($part);
                        $call->setMessage($exp['message']);
                        $call->setExitCode($exp['exit']);
                        $call->setCallPgm($exp['call']);
                        $call->setParms($exp['parms']);
                        $em->persist($call);
                    }
                }
            }
            $em->flush();

        }
        else {         
            $File['name'] =        $file->getName();
            $File['title'] =       $file->getTitle();
            $File['description'] = $file->getDescription();
            $File['author'] =      $file->getAuthor();
            
            $parts = $em->getRepository('AriiDocBundle:Part')->findBy(array('file' => $file ));            
            $p=0; 
            $nb_calls= $nb_validated =$nb_in_progress = 0;
            foreach ($parts as $Part) {
                $File['parts'][$p]['name'] = $Part->getName();
                $File['parts'][$p]['comment'] = $Part->getComment();
                $File['parts'][$p]['code'] = $Part->getCode();
                
                $calls = $em->getRepository('AriiDocBundle:Call')->findBy(array('part' => $Part ));       
                $e = 0;
                foreach ($calls as $Call) {
                    $e++;
                    $File['parts'][$p]['exploit'][$e]['message'] = $Call->getMessage();
                    $File['parts'][$p]['exploit'][$e]['call'] = $Call->getCallPgm();
                    $File['parts'][$p]['exploit'][$e]['exit'] = $Call->getExitCode();
                    $File['parts'][$p]['exploit'][$e]['parms'] = $Call->getParms();
                    $nb_calls++;
                    
                    // Consignes ?
                    $User = $em->getRepository('AriiDocBundle:User')->findOneBy(array('call' => $Call ));
                    if ($User) {
                        $File['parts'][$p]['user'][$e]['title'] = $User->getTitle();
                        $File['parts'][$p]['user'][$e]['description'] = $User->getInstructions();
                        $File['parts'][$p]['user'][$e]['triggers'] = $User->getTriggers();
                        $File['parts'][$p]['user'][$e]['constraints'] = $User->getConstraints();
                        $File['parts'][$p]['user'][$e]['instructions'] = $User->getInstructions();
                        $File['parts'][$p]['user'][$e]['validated'] = $User->getValidated();
                        $File['parts'][$p]['user'][$e]['restart'] = $User->getRestart();
                        if ($File['parts'][$p]['user'][$e]['validated'])
                            $nb_validated++;
                        else 
                            $nb_in_progress++;
                    }
                }                
                $p++;
            }
            $File['count'] = $p;
            $File['calls'] = $nb_calls;
            $File['in_progress'] = round($nb_in_progress*100/$nb_calls);
            $File['validated'] = round($nb_validated*100/$nb_calls);
        }
        
        $response = new Response();        
        return $this->render('AriiDocBundle:Doc:bootstrap.html.twig',
                array(  'Doc' => $File ), 
                        $response );
    }

    public function refreshAction() {
        $request = Request::createFromGlobals();
        $id = $request->get('file');        

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AriiDocBundle:File')->findOneBy(array('file' => $id));        
    }

    protected function refresh($file,$File) {
    }
    
    public function getDoc($file) {
        $doc = $this->container->get('arii_doc.doc');
        list( $doctype, $name ) = $doc->DirInfo($file);
        
        $File['type'] = $doctype;
        $File['title'] = basename($file);
        $File['name'] = basename($file);
        $File['description'] = $file;
        $File['author'] = '?';
       
        // On retrourve le type
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('AriiDocBundle:Type')->findBy(array('name' => $doctype));
        if (!$type ) {
            return $File;
        }
        
        // on traite le document ligne a ligne
        $workspace= $this->container->getParameter('workspace');
        $dir = $workspace."/Doc";        
        $content = file_get_contents("$dir/$file");
        
        // On parse avec les règles
        // On retrouve les règles ?       
        $rules = $em->getRepository('AriiDocBundle:Rule')->findBy(array('type' => $type));
        foreach ($rules as $rule) {
            $name = $rule->getName();
            $match = str_replace('/','\/',$rule->getPregMatch());
            $Rule[$name] = $match;
                        
            if (preg_match('/'.$match.'/', $content, $Result)) {
                $File[$name] = trim($Result[1]);
            }
        }
        
        // On appelle le service
        $import = $this->container->get('doc_import.'.$doctype);
        $File = $import->Import(utf8_encode($content), $File);
        return $File;
    }

    public function deleteAction() {
        $request = Request::createFromGlobals();
        $id = $request->get('file');

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AriiDocBundle:File')->findOneBy(array('file' => $id));
        
        if (!$file) {
            print "$file ?";
            exit();
        }
        
        $em->remove($file);
        $em->flush();        
        print "success";
        exit();
    }
    
    protected function Text($code) {
        $code = str_replace(' ','&nbsp;',$code);
        return $code;
    }
    
    public function pdfAction() {
        $request = Request::createFromGlobals();
        $file = $request->get('file');
        
        $url = $this->generateUrl('html_Doc_doc')."?file=$file";
        
        $wkhtml = $this->container->getParameter('wkhtmltopdf');

        $temp = tempnam(sys_get_temp_dir(),'pdf');
        $cmd = escapeshellarg($wkhtml).' "http://localhost'.$url.'" "'.$temp.'"';
        print system($cmd);
/*        
        $descriptorspec = array(
            0 => array("pipe", "r"),  // // stdin est un pipe où le processus va lire
            1 => array("pipe", "w"),  // stdout est un pipe où le processus va écrire
            2 => array("pipe", "w") // stderr est un fichier
         );

        $process = proc_open($cmd, $descriptorspec, $pipes);
        
        if (is_resource($process)) {
            $out = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $err = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $return_value = proc_close($process);
            if ($return_value != 0) {
                print "[exit $return_value]<br/>";
                print "$out<br/>";
                print "<font color='red'>$err</font>";
                print "<hr/>";
                print "<pre>$cmd</pre>";
                exit();
            }
        }  
*/
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent(  file_get_contents($temp) );
        return $response;             
    }
}
