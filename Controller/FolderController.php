<?php

namespace Arii\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FolderController extends Controller
{
    protected $folder;
    
    public function indexAction($path='')
    {        
        return $this->render('AriiDocBundle:Folder:index.html.twig', array('path'=>$path));
    }

    public function toolbarAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        return $this->render('AriiDocBundle:Folder:toolbar.xml.twig', array(), $response );
    }

    public function foldersAction()
    {
        $workspace= $this->container->getParameter('workspace');
        $dir = $workspace."/Doc";
        $Folder = array();
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_file("$dir/$file") and substr($file,0,1)!='.') {
                    array_push($Folder,$file);
                }
            }
            closedir($dh);
        }        
        sort($Folder);
        
        $list = '<?xml version="1.0" encoding="UTF-8"?>';
        $list .= "<rows>\n";
        $doc = $this->container->get('arii_doc.doc');
        foreach ($Folder as $file) {
            list($type, $folder) = $doc->DirInfo($file);
            $list .= '<row id="'.$file.'"><cell>'.$type.'</cell><cell>'.$folder.'</cell></row>';
        }
        $list .= "</rows>\n";
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        $response->setContent( $list );
        return $response;        
    }
    
    public function listAction($folder='')
    {
        $request = Request::createFromGlobals();
        if ($request->get('folder')!='') {
            $folder = $request->get('folder');
        }
        
        $workspace= $this->container->getParameter('workspace');
        $dir = $workspace."/Doc/$folder";
        $list = '<?xml version="1.0" encoding="UTF-8"?>';
        $list .= "<rows>\n";
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (is_file("$dir/$file")) {
                    $list .= '<row id="'.$folder.'/'.$file.'"><cell>'.$file.'</cell></row>';
                }
            }
            closedir($dh);
        }        
        $list .= "</rows>\n";
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        $response->setContent( $list );
        return $response;        
    }

    public function fileAction($file='')
    {
        $request = Request::createFromGlobals();
        if ($request->get('file')!='') {
            $file = $request->get('file');
        }

        $workspace= $this->container->getParameter('workspace');
        $dir = $workspace."/Doc";
        print '<pre>'.utf8_encode(file_get_contents("$dir/$file")).'</pre>';
        exit();
    }

    public function docAction($file='')
    {
        $request = Request::createFromGlobals();
        if ($request->get('file')!='') {
            $file = $request->get('file');
        }

        $workspace= $this->container->getParameter('workspace');
        $dir = $workspace."/Doc";
        print file_get_contents("$dir/$file");
        exit();
    }
    
}
