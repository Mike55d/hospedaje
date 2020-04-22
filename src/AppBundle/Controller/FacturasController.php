<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;


/**
* @Route("facturas")
*/
class FacturasController extends Controller
{
  /**
  * @Route("/" , name="facturas_index")
  */
  public function indexAction(Request $request)
  {
  	$em =$this->getDoctrine()->getManager(); 
  	$facturas = $em->getRepository('AppBundle:Factura')->findAll();
    if ($request->get('reserva')) {
      $facturas = $em->getRepository('AppBundle:Factura')->findByReserva($request->get('reserva'));
    }
  	return $this->render('AppBundle:Facturas:index.html.twig', array(
  		'facturas'=> $facturas
  	));
  }

}
