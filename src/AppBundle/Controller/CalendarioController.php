<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Reservacion;

class CalendarioController extends Controller
{
	/**
	* @Route("/calendario" , name="calendario_index")
	*/
	public function indexAction()
	{
		$em =$this->getDoctrine()->getManager(); 
		$camas = $em->getRepository('AppBundle:Cama')->findAll();
		$reservaciones = $em->getRepository('AppBundle:Reservacion')->findAll();
		return $this->render('AppBundle:Calendario:index.html.twig', array(
			'camas' => $camas,
			'reservaciones' => $reservaciones,
		));
	}

}
