<?php

namespace ST\SnowTricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FamilyController extends Controller
{
    public function indexAction()
    {
        return $this->render('STSnowTricksBundle:Family:index.html.twig');
    }
}
