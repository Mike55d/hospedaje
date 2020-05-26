<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;
use AppBundle\Entity\Factura;


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

  /**
  * @Route("/{id}/reenviar" , name="facturas_reenviar")
  */
  public function reenviarFactura(Factura $factura ,\Swift_Mailer $mailer){
    $em =$this->getDoctrine()->getManager();
    $data =[];
    $reservaciones = $em->getRepository('AppBundle:Reservacion')->findByFactura($factura);
    foreach ($reservaciones as $reservacion) {
      $servicios = $em->getRepository('AppBundle:ServiciosUs')
      ->findBy(['persona'=>$reservacion->getPersona(),'reserva'=> $reservacion->getReserva()]); 
      $data[]= [
        'reservacion' => $reservacion ,
        'persona' => $reservacion->getPersona(),
        'servicios' => $servicios,
      ];
    }
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($this->renderView('AppBundle:PDF:factura.html.twig',[
      'data'=>$data,
      'factura' => $factura,
    ]));
    $fileName = md5(uniqid());
    $html2pdf->output($this->getParameter('pdfs').'/'.$fileName.'.pdf', 'F');
    $message = (new \Swift_Message('Notificacion'))
    ->setSubject('Factura pendiente')
    ->setFrom('info@financeirocheznous.org')
    ->setTo('mike.gonzalez55d@gmail.com')
    ->setBody(
      $this->renderView(
        'AppBundle:Emails:factura.html.twig',
        array()),'text/html')
    ->attach(\Swift_Attachment::fromPath($this->getParameter('pdfs').'/'.$fileName.'.pdf'));
    $mailer->send($message);
    $this->addFlash(
      'notice',
      'Facura reenviada satisfactoriamente'
    );
    return $this->redirectToRoute('facturas_index');
  }

}
