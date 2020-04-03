<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
  /**
  * @Route("/dashboard", name="homepage")
  */
  public function indexAction(Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $solicitudes = $em->getRepository('AppBundle:Reserva')
    ->findBy(['status' => 'pendiente']); 
    $users = $em->getRepository('AppBundle:User')->findByRola('USER'); 
    $reservaciones = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'reservado']); 
    return $this->render('AppBundle:Home:index.html.twig',[
      'solicitudes'=> $solicitudes,
      'users' => $users,
      'reservas' => $reservaciones
    ]);
  }

  /**
  * @Route("/", name="dash")
  */
  public function dashboardAction(Request $request)
  {
    return $this->redirectToRoute('homepage');
  }
}
