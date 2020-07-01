<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cuarto;


/**
 * @Route("cuartos")
 */
class CuartosController extends Controller
{
	/**
	 * @Route("/" , name="cuartos_index")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$cuartos = $em->getRepository('AppBundle:Cuarto')->findAll();
		return $this->render('AppBundle:Cuartos:index.html.twig', array(
			'cuartos'=> $cuartos,
		));
	}

	/**
	 * @Route("/new" , name="cuartos_new")
	 */
	public function newAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		if($request->get('numero')){
			$cuarto = new Cuarto;
			$cuarto->setNumero($request->get('numero'));
			$em->persist($cuarto);
			$em->flush();
			return $this->redirectToRoute('cuartos_index');
		}
		return $this->render('AppBundle:Cuartos:new.html.twig', array(
		));
	}

	/**
	 * @Route("/{id}/edit" , name="cuartos_edit")
	 */
	public function editAction(Cuarto $cuarto,Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		if($request->get('numero')){
			$cuarto->setNumero($request->get('numero'));
			$em->flush();
			return $this->redirectToRoute('cuartos_index');
		}
		return $this->render('AppBundle:Cuartos:edit.html.twig', array(
		));
	}

	/**
	 * @Route("/{id}/del" , name="cuartos_del")
	 */
	public function delAction(Cuarto $cuarto)
	{
		$em = $this->getDoctrine()->getManager();
		if($cuarto){
			try {
				$em->remove($cuarto);
				$em->flush();
				$this->addFlash('notice','Cuarto Eliminada satisfactoriamente');
			} catch (\Throwable $th) {
				$this->addFlash('error','Este cuarto tiene registros , no se puede eliminar');
			}
		}
		return $this->redirectToRoute('cuartos_index');
	}
}
