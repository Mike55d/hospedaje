<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;
use AppBundle\Form\ReservaType;
use AppBundle\Entity\ReservaPersona;


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
    return $this->render('AppBundle:Reservas:indexReservas.html.twig', array(
     'reservas' => $reservas
   ));
  }

  /**
  * @Route("/reservasAdmin" , name= "reservas_admin")
  */
  public function indexAdminAction()
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $reservas = $em->getRepository('AppBundle:Reserva')
    ->findBy(['status' => 'Reservado']); 
    return $this->render('AppBundle:Reservas:indexReservas.html.twig', array(
     'reservas' => $reservas
   ));
  }


  /**
  * @Route("/{id}/detallesReserva" , name= "detallesReserva_admin")
  */
  public function detallesReservaAction(Reserva $reserva )
  {
    $em =$this->getDoctrine()->getManager();
    $personas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($reserva);
    $mayores = 0;
    $menores = 0;
    $totalLavanderia = 0;
    $totalRoto= 0;
    $totalTienda = 0;
    $serviciosLavanderia = $em->getRepository('AppBundle:ServiciosUs')->buscarReserva('lavanderia',$reserva->getId());
    $checkoutCompleto = true;
    
    foreach ($serviciosLavanderia as $servicio) {
      $totalLavanderia += $servicio->getServicio()->getCosto();
    }
    $serviciosRoto = $em->getRepository('AppBundle:ServiciosUs')->buscarReserva('roto',$reserva->getId());
    foreach ($serviciosRoto as $servicio) {
      $totalRoto += $servicio->getServicio()->getCosto();
    } 
    $serviciosTienda = $em->getRepository('AppBundle:ServiciosUs')->buscarReserva('tienda',$reserva->getId());
    foreach ($serviciosTienda as $servicio) {
      $totalTienda += $servicio->getServicio()->getCosto();
    } 

    foreach ($personas as $persona) {
      $reservacion = $em->getRepository('AppBundle:Reservacion')->findOneByPersona($persona->getPersona()); 
      if($reservacion){
        if (!$reservacion->getFactura()) {
          $checkoutCompleto = false;
        }
        if ($persona->getPersona()->getMayorEdad()) {
          $mayores++;
        }else{
          $menores++;
        }
      }
    }
    return $this->render('AppBundle:Reservas:detallesReserva.html.twig', array(
      'reserva' => $reserva,
      'user'=> $reserva->getUser(),
      'personas' => $personas,
      'mayores' => $mayores,
      'menores'=> $menores,
      'totalLavanderia' => $totalLavanderia,
      'totalRoto' => $totalRoto,
      'totalTienda' => $totalTienda,
      'checkoutCompleto' => $checkoutCompleto
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
    $miGrupo = $em->getRepository('AppBundle:Persona')->findByUser($user); 
    if ($reserva->getStatus() == 'Aprobada') {
      if ($request->get('data')) {
        $personas = json_decode($request->get('data'),true);
        foreach ($personas as $persona) {
          $Persona = $em->getRepository('AppBundle:Persona')->find($persona['id']);
          $reservaPersona = new ReservaPersona;
          $reservaPersona->setReserva($reserva);
          $reservaPersona->setEntrada($reserva->getLlegada());
          $reservaPersona->setSalida(new \Datetime($persona['fecha']));
          $reservaPersona->setPersona($Persona);
          $em->persist($reservaPersona);
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
        'personas'=>$miGrupo,
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
