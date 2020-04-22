<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;

/**
* @Route("camas")
*/
class CamasController extends Controller
{
/**
  * @Route("/{id}/camas" , name= "camas_index")
  */
public function indexAction(Reserva $reserva)
{
	$em =$this->getDoctrine()->getManager();
	$personas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($reserva); 
	$camasPersonas = [];
	foreach ($personas as $persona) {
		$reservacion = $em->getRepository('AppBundle:Reservacion')->findOneBy(['persona' => $persona->getPersona(),'reserva'=> $reserva]);
		if ($reservacion) {
			$camasPersonas[] = ['persona' => $persona->getPersona(),'reservacion'=> $reservacion];
		}else{
			$camasPersonas[] = ['persona' => $persona->getPersona(),'reservacion'=> null];
		}
	}
	return $this->render('AppBundle:Camas:index.html.twig', array(
		'camasPersonas'=> $camasPersonas
	));
}
}
