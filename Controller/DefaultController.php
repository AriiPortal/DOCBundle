<?php

namespace Arii\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AriiDocBundle:Default:index.html.twig', array('name' => $name));
    }
}
