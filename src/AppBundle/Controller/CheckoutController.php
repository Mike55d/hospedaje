<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Spipu\Html2Pdf\Html2Pdf;
use AppBundle\Entity\Reserva;
use AppBundle\Entity\Factura;

/**
* @Route("/checkout")
*/
class CheckoutController extends Controller
{
  /**
  * @Route("/{id}/new" , name="checkout_newAdmin")
  */
  public function indexAction(Reserva $reserva ,SerializerInterface $serializer)
  {
    $em =$this->getDoctrine()->getManager();
    $personas = $em->getRepository('AppBundle:ReservaPersona')->findBy(['reserva'=> $reserva]);
    $caja = $em->getRepository('AppBundle:Caja')->findOneByGrupo($reserva->getUser()->getGrupo()); 
    $data = [];
    foreach ($personas as $persona) {
      $reservacion = $em->getRepository('AppBundle:Reservacion')->findOneBy(['reserva'=> $reserva,'persona'=> $persona->getPersona()]);
      $servicios = $em->getRepository('AppBundle:ServiciosUs')->findBy(['reserva'=> $reserva,'persona'=> $persona->getPersona()]);
      if ($reservacion && $reservacion->getStatus() == 'hospedado') {
        $data[]= [
          'id' =>$persona->getPersona()->getId(),
          'nombre' =>$persona->getPersona()->getNombre(),
          'tratamiento' =>$persona->getPersona()->getTratamiento(),
          'fecha' =>$persona->getPersona()->getFechaNacimiento(),
          'grupo' =>$persona->getPersona()->getGrupo()->getNombre(),
          'servicios'=> $servicios,
          'reservacion'=> $reservacion
        ];
      }
    }
    $datos = $serializer->serialize($data,'json');
    return $this->render('AppBundle:Checkout:index.html.twig', array(
      'reserva'=> $reserva,
      'data'=>$data,
      'datos' => $datos,
      'caja' => $caja,
    ));
  }


  /**
  * @Route("/checkoutGenerate" , name="checkout_generate")
  */
  public function generateCheckout(Request $request , \Swift_Mailer $mailer){
    $em =$this->getDoctrine()->getManager(); 
    $reservaciones = explode(',',$request->get('data'));
    $reserva = $em->getRepository('AppBundle:Reservacion')->find($reservaciones[0]); 
    $caja = $em->getRepository('AppBundle:Caja')
    ->findOneByGrupo($reserva->getPersona()->getGrupo()); 
    $factura = new Factura;
    $factura->setFecha(new \Datetime());
    $factura->setTotal($request->get('total'));
    $factura->setSubTotal($request->get('subtotal'));
    $factura->setDescuento($request->get('descuento'));
    $factura->setTipoPago($request->get('tipoPago'));
    $factura->setGrupo($reserva->getPersona()->getGrupo());
    $factura->setReserva($reserva->getReserva());
    $factura->setCaja($caja);
    $em->persist($factura);
    $em->flush();
    $data =[];
    foreach ($reservaciones as $id) {
      $reservacion = $em->getRepository('AppBundle:Reservacion')->find($id); 
      $reservacion->setStatus('salida');
      $reservacion->setFactura($factura);
      $servicios = $em->getRepository('AppBundle:ServiciosUs')
      ->findBy(['persona'=>$reservacion->getPersona(),'reserva'=> $reservacion->getReserva()]); 
      $data[]= [
        'reservacion' => $reservacion ,
        'persona' => $reservacion->getPersona(),
        'servicios' => $servicios,
      ];
      $em->flush();
    }
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($this->renderView('AppBundle:PDF:factura.html.twig',[
      'data'=>$data,
      'factura' => $factura,
    ]));
    $fileName = md5(uniqid());
    $html2pdf->output($this->getParameter('pdfs').'/'.$fileName.'.pdf', 'F');
    $message = (new \Swift_Message('Notificacion'))
    ->setSubject('Felicidades creaste tu lista de regalos')
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
      'Checkout realizado satisfactoriamente'
    );
    return $this->redirectToRoute('facturas_index');

  }

}
