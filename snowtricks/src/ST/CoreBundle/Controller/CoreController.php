<?php

namespace ST\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('STCoreBundle:Core:index.html.twig');
    }
}
