<?php

namespace GalerieImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GalerieImageBundle:Default:index.html.twig');
    }
}
