<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;


/**
* @Route("api/calendario")
*/
class ApiCalendarioController extends AbstractFOSRestController
{
	/**
* @Route("/reservaciones")
*/
	public function reservacionesAction(Request $request)
	{
		$em =$this->getDoctrine()->getManager(); 
		// $data = json_decode($request->getContent(),true);
		// $camas = $em->getRepository('AppBundle:Cama')->findAll();
		$reservaciones = $em->getRepository('AppBundle:Reservacion')
		->buscarFecha($request->get('mes'),$request->get('año'));
		$view = $this->view($reservaciones,200);
		return $this->handleView($view);
	}
}
