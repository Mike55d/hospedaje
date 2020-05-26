<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;

class DefaultController extends Controller
{
  /**
  * @Route("/dashboard", name="homepage")
  */
  public function indexAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $solicitudes = $em->getRepository('AppBundle:Reserva')
    ->findBy(['status' => 'pendiente']);
    $personas = $em->getRepository('AppBundle:Persona')->findByUser($user->getId()); 
    $huespedes = $em->getRepository('AppBundle:Persona')->findByHuesped(1); 
    $users = $em->getRepository('AppBundle:User')->findByRola('USER'); 
    $solicitudesUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'pendiente', 'user'=> $user->getId()]); 
    $reservaciones = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Reservado']); 
    $reservacionesUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Reservado', 'user'=> $user->getId()]); 
    $aprobadasUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Aprobada', 'user'=> $user->getId()]); 
    return $this->render('AppBundle:home:index.html.twig',[
      'solicitudes'=> $solicitudes,
      'users' => $users,
      'reservas' => $reservaciones,
      'reservasUser'=> $reservacionesUser,
      'personas' => $personas,
      'solicitudesUser' => $solicitudesUser,
      'aprobadasUser' => $aprobadasUser,
      'huespedes' => $huespedes,
    ]);
  }

  /**
  * @Route("/", name="dash")
  */
  public function dashboardAction(Request $request)
  {
    return $this->redirectToRoute('homepage');
  }

  /**
  * @Route("/test", name="test")
  */
  public function test(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $reservaciones = [1,3,4,5];
    $data =[];
    foreach ($reservaciones as $id) {
      $reservacion = $em->getRepository('AppBundle:Reservacion')->find($id); 
      $servicios = $em->getRepository('AppBundle:ServiciosUs')
      ->findBy(['persona'=>$reservacion->getPersona(),'reserva'=> $reservacion->getReserva()]); 
      $data[]= [
        'reservacion' => $reservacion ,
        'persona' => $reservacion->getPersona(),
        'servicios' => $servicios,
      ];
    }
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($this->renderView('AppBundle:PDF:factura.html.twig',['data'=>$data]));
    $html2pdf->output('my_doc.pdf','D');
    // return $this->render('AppBundle:PDF:factura.html.twig',['data'=>$data]);
  }
}
