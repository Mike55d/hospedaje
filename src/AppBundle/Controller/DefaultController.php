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
}
