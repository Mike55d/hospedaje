<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;
use AppBundle\Form\ReservaType;


/**
* @Route("reservas")
*/
class ReservasController extends Controller
{
  /**
  * @Route("/misReservas" , name= "reservas_misReservas")
  */
  public function indexAction()
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $reservas = $em->getRepository('AppBundle:Reserva')
    ->findBy(['user' => $user,'status' => 'Reservado']); 
    return $this->render('AppBundle:Reservas:index.html.twig', array(
     'reservas' => $reservas
   ));
  }

  /**
  * @Route("/{id}/new" , name= "reservas_new")
  */
  public function newAction(Reserva $reserva ,Request $request)
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser(); 
    $personas = $em->getRepository('AppBundle:Persona')
    ->findBy(['user' => $user , 'reserva' => null]); 
    if ($reserva->getStatus() == 'Aprobada') {
      if ($request->get('data')) {
        $personas = json_decode($request->get('data'),true);
        foreach ($personas as $persona) {
          $Persona = $em->getRepository('AppBundle:Persona')->find($persona['id']);
          $Persona->setReserva($reserva);
          $Persona->setEntrada($reserva->getLlegada());
          $Persona->setSalida(new \Datetime($persona['fecha']));
          $em->flush();
        }
        $reserva->setStatus('Reservado');
        $em->flush();
        $this->addFlash(
          'notice',
          'Reserva Completada , en proceso de asignacion de camas'
        );
        return $this->redirectToRoute('reservas_misReservas');
      }
      return $this->render('AppBundle:Reservas:new.html.twig', array(
        'personas'=>$personas,
        'reserva' => $reserva,
      ));
    }else{
      return $this->redirectToRoute('reservas_misReservas');
    }
  }

  /**
  * @Route("/edit" , name= "reservas_edit")
  */
  public function editAction()
  {
    return $this->render('AppBundle:Reservas:edit.html.twig', array(
            // ...
    ));
  }

  /**
  * @Route("/del" , name= "reservas_del")
  */
  public function delAction()
  {
    return $this->render('AppBundle:Reservas:del.html.twig', array(
            // ...
    ));
  }

  /**
  * @Route("/comidas" , name= "reservas_comidas")
  */
  public function comidasAction()
  {
    return $this->render('AppBundle:Reservas:comidas.html.twig', array(
     'reservas' => ''
   ));
  }

}
