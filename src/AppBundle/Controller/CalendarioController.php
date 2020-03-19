<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
     * @Route("calendario")
     */
class CalendarioController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Calendario:index.html.twig', array(
            // ...
        ));
    }

}
