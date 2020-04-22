<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Caja;

/**
   * @Route("cajas")
   */
class CajasController extends Controller
{
  /**
   * @Route("/" , name="cajas_index")
   */
  public function indexAction()
  {
  	$em =$this->getDoctrine()->getManager();
  	$cajas = $em->getRepository('AppBundle:Caja')->findAll();
  	return $this->render('AppBundle:Cajas:index.html.twig', array(
  		'cajas' => $cajas
  	));
  }

  /**
   * @Route("/new" , name="cajas_new")
   */
  public function newAction(Request $request)
  {
  	$em =$this->getDoctrine()->getManager();
  	$grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
  	if ($request->get('nro')) {
  		$caja = new Caja;
  		$caja->setNro($request->get('nro'));
  		$caja->setResponsable($request->get('responsable'));
  		$caja->setEmailCaja($request->get('emailCaja'));
  		$caja->setEncargado($request->get('encargado'));
  		$caja->setEmailEncargado($request->get('emailEncargado'));
  		$grupo = $em->getRepository('AppBundle:Grupo')->find($request->get('grupo')); 
  		$caja->setGrupo($grupo);
  		$em->persist($caja);
  		$em->flush();
  		$this->addFlash(
  			'notice',
  			'Caja Creada Satisfactoriamente'
  		);
  		return $this->redirectToRoute('cajas_index');
  	}
  	return $this->render('AppBundle:Cajas:new.html.twig', array(
  		'grupos' => $grupos
  	));
  }

  /**
   * @Route("/{id}/edit" , name="cajas_edit")
   */
  public function editAction(Caja $caja,Request $request)
  {
  	$em =$this->getDoctrine()->getManager();
  	$grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
  	if ($request->get('nro')) {
  		$caja->setNro($request->get('nro'));
  		$caja->setResponsable($request->get('responsable'));
  		$caja->setEmailCaja($request->get('emailCaja'));
  		$caja->setEncargado($request->get('encargado'));
  		$caja->setEmailEncargado($request->get('emailEncargado'));
  		$grupo = $em->getRepository('AppBundle:Grupo')->find($request->get('grupo')); 
  		$caja->setGrupo($grupo);
  		$em->flush();
  		$this->addFlash(
  			'notice',
  			'Caja Editada Satisfactoriamente'
  		);
  		return $this->redirectToRoute('cajas_index');
  	}
  	return $this->render('AppBundle:Cajas:edit.html.twig', array(
  		'caja' => $caja,
  		'grupos' => $grupos
  	));
  }

}
