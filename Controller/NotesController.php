<?php

namespace Arii\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotesController extends Controller
{
    public function indexAction()
    {
        return $this->render('AriiDocBundle:Notes:index.html.twig');
    }
    
    public function toolbarAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        return $this->render('AriiDocBundle:Notes:toolbar.xml.twig', array(), $response );
    }

    public function listAction()
    {
        $db = $this->container->get('arii_core.db');
        $Arii = $db->getAriiDatabase();
        
        $sql = $this->container->get('arii_core.sql');
        $sql->InitDB($Arii); 
        
        $qry = $sql->Select(array('ID','NAME','TITLE'))
                .$sql->From(array('DOC_NOTE'))
                .$sql->OrderBy(array('TITLE'));

        $data = $db->Connector('grid');
        // $data->event->attach("beforeRender",array($this,"grid_render"));
        $data->render_sql($qry,'ID','TITLE');
    }

    function detail_render ($data){
        $data->set_value( 'VALUE', $data->get_value('VALUE1').' '.$data->get_value('VALUE2') );
        if ($data->get_value('IS_EDIT')=='Y')
            $data->set_row_color("#00cccc");
    }

    public function formAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('id');
        $db = $this->container->get('arii_core.db');
        $Arii = $db->getAriiDatabase();
        
        $sql = $this->container->get('arii_core.sql');
        $sql->InitDB($Arii); 
        
        $qry = $sql->Select(array('ID','NAME','TITLE','DESCRIPTION','NOTE'))
                .$sql->From(array('DOC_NOTE'))
                .$sql->Where(array('ID'=>$id));

        $data = $db->Connector('form');
        // $data->event->attach("beforeRender",array($this,"grid_render"));
        $data->render_sql($qry,'ID','ID,NAME,TITLE,DESCRIPTION,NOTE');
    }
    
    public function saveAction()
    {
        $request = Request::createFromGlobals();
        $id = $request->get('ID');
        if ($id != "")
            $note = $this->getDoctrine()->getRepository("AriiDocBundle:Note")->find($id);
        else
            $note = new \Arii\DocBundle\Entity\Note();            

        $note->setTitle($request->get('TITLE'));
        $note->setName($request->get('NAME'));
        $note->setDescription($request->get('DESCRIPTION'));
        $note->setNote($request->get('NOTE'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();
        
        return new Response("success");
    }

    public function deleteAction() {
        $request = Request::createFromGlobals();
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('AriiDocBundle:Note')->find($id);
        
        if (!$note) {
            print "$id ?";
            exit();
        }
        
        $em->remove($note);
        $em->flush();        
        print "success";
        exit();
    }

    public function partsAction()
    {
        $db = $this->container->get('arii_core.db');
        $Arii = $db->getAriiDatabase();
        
        $sql = $this->container->get('arii_core.sql');
        $sql->InitDB($Arii); 
        
        $qry = $sql->Select(array('p.ID','f.NAME as FILE','p.NAME as PART','n.NAME as NOTE'))
                .$sql->From(array('DOC_FILE f'))
                .$sql->LeftJoin('DOC_PART p',array('f.ID','p.FILE_ID'))
                .$sql->LeftJoin('DOC_NOTE_PART np',array('p.ID','np.PART_ID'))
                .$sql->LeftJoin('DOC_NOTE n',array('np.NOTE_ID','n.ID'))
                .$sql->OrderBy(array('f.NAME','p.NAME','n.NAME'));

        $dhtmlx = $this->container->get('arii_core.db');
        $data = $dhtmlx->Connector('grid');
    //    $data->event->attach("beforeRender",array($this,"grid_render"));
        $data->render_sql($qry,'ID','FILE,PART,NOTE');
    }
    
    public function note_on_partAction()
    {
        $request = Request::createFromGlobals();
        $source = $request->get('source');
        $target = $request->get('target');
        
        $note = $this->getDoctrine()->getRepository("AriiDocBundle:Note")->find($source);
        $part = $this->getDoctrine()->getRepository("AriiDocBundle:Part")->find($target);

        $note->addPart($part);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($note);
        $em->flush();
        
        return new Response("success");
    }
    
}
