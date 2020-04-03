<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;
use AppBundle\Form\ReservaType;

/**
* @Route("Solicitudes")
*/
class SolicitudesController extends Controller
{
	/**
  * @Route("/{status}/misSolicitudes" , name= "solicitudes_misSolicitudes")
  */
   public function indexAction($status)
   {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    if ($status != 'todas') {
      $reservas = $em->getRepository('AppBundle:Reserva')
      ->findBy(['user'=>$user,'status' => $status]);
    }else{
     $reservas = $em->getRepository('AppBundle:Reserva')
     ->findBy(['user'=>$user]); 
   }
   return $this->render('AppBundle:Reservas:index.html.twig', array(
     'reservas' => $reservas,
     'statusRedirect' => $status,
   ));
 }

  /**
  * @Route("/{status}/Solicitudes" , name= "solicitudes_indexAdmin")
  */
  public function indexAdminAction($status)
  {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    if ($user->getRola() == 'ADMIN') {
      if ($status != 'todas') {
        $reservas = $em->getRepository('AppBundle:Reserva')
        ->findBy(['status' => $status]); 
      }else{
        $reservas = $em->getRepository('AppBundle:Reserva')
        ->findAll(); 
      }
    }
    return $this->render('AppBundle:Reservas:index.html.twig', array(
     'reservas' => $reservas,
     'statusRedirect' => $status,
   ));
  }

  /**
  * @Route("/new" , name= "solicitudes_new")
  */
  public function newAction( Request $request)
  {
  	$em = $this->getDoctrine()->getManager();
  	$user = $this->get('security.token_storage')
  	->getToken()->getUser(); 
  	$reserva = new Reserva();
  	$form = $this->createForm(ReservaType::class, $reserva);
  	$form->handleRequest($request);
  	if ($form->isSubmitted() && $form->isValid()) {
  		$reserva->setStatus('Pendiente');
  		$reserva->setUser($user);
  		$reserva->setFecha(new \Datetime());
  		$em->persist($reserva);
  		$em->flush();
  		$this->addFlash(
  			'notice',
  			'Nueva solicitud enviada satisfactoriamente'
  		);
  		return $this->redirectToRoute('solicitudes_misSolicitudes',['status'=>'pendiente']);
  	}
  	return $this->render('AppBundle:Solicitudes:new.html.twig', array(
  		'form' => $form->createView()
  	));

  }

    /**
    * @Route("/{id}/{status}/{statusRedirect}/changeStatus" , name= "solicitudes_changeStatus")
    */
   public function changeStatusAction(Reserva $reserva,$status,$statusRedirect)
   {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $reserva->setStatus($status);
    $em->flush();
    $this->addFlash(
        'notice',
        'Status cambiado satisfactoriamente'
      );
    return $this->redirectToRoute('solicitudes_indexAdmin',['status'=> $statusRedirect]);
  }

    /**
    * @Route("/{id}/{status}/changeStatus" , name= "solicitudes_changeStatusHome")
    */
   public function changeStatusHomeAction(Reserva $reserva , $status)
   {
    $em =$this->getDoctrine()->getManager(); 
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $reserva->setStatus($status);
    $em->flush();
    $this->addFlash(
        'notice',
        'Status Cambiado satisfactoriamente'
      );
    return $this->redirectToRoute('homepage');
  }
}
