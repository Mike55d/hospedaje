<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;
use AppBundle\Entity\Cama;

/**
 * @Route("camas")
 */
class CamasController extends Controller
{
	/**
	 * @Route("/{id}/asignadas" , name= "camas_asignadas")
	 */
	public function camasAsginadas(Reserva $reserva)
	{
		$em = $this->getDoctrine()->getManager();
		$personas = $em->getRepository('AppBundle:ReservaPersona')->findByReserva($reserva);
		$camasPersonas = [];
		foreach ($personas as $persona) {
			$reservacion = $em->getRepository('AppBundle:Reservacion')->findOneBy(['persona' => $persona->getPersona(), 'reserva' => $reserva]);
			if ($reservacion) {
				$camasPersonas[] = ['persona' => $persona->getPersona(), 'reservacion' => $reservacion];
			} else {
				$camasPersonas[] = ['persona' => $persona->getPersona(), 'reservacion' => null];
			}
		}
		return $this->render('AppBundle:Camas:asignadas.html.twig', array(
			'camasPersonas' => $camasPersonas
		));
	}

	/**
	 * @Route("/" , name= "camas_index")
	 */
	public function index(){
		$em = $this->getDoctrine()->getManager();
		$camas = $em->getRepository('AppBundle:Cama')->findAll();
		return $this->render('AppBundle:Camas:index.html.twig',[
			'camas'=>$camas,
		]);
	}

	/**
	 * @Route("/new" , name= "camas_new")
	 */
	public function new(Request $request){
		$em = $this->getDoctrine()->getManager();
		$cuartos = $em->getRepository('AppBundle:Cuarto')->findAll();
		if($request->get('numero')){
			$cama = new Cama;
			$cama->setNumero($request->get('numero'));
			$cuarto = $em->getRepository('AppBundle:Cuarto')->find($request->get('cuarto'));
			$cama->setCuarto($cuarto);
			$em->persist($cama);
			$em->flush();
			$this->addFlash('notice','Cama creada satisfactoriamente');
			return $this->redirectToRoute('camas_index');
		}
		return $this->render('AppBundle:Camas:new.html.twig',[
			'cuartos'=> $cuartos,
		]);
	}

	/**
	 * @Route("/{id}/edit" , name= "camas_edit")
	 */
	public function edit(Cama $cama,Request $request){
		$em = $this->getDoctrine()->getManager();
		$cuartos = $em->getRepository('AppBundle:Cuarto')->findAll();
		if($request->get('numero')){
			$cama->setNumero($request->get('numero'));
			$cuarto = $em->getRepository('AppBundle:Cuarto')->find($request->get('cuarto'));
			$cama->setCuarto($cuarto);
			$em->flush();
			$this->addFlash('notice','Cama editada satisfactoriamente');
			return $this->redirectToRoute('camas_index');
		}
		return $this->render('AppBundle:Camas:edit.html.twig',[
			'cuartos'=> $cuartos,
			'cama'=> $cama
		]);
	}

	/**
	 * @Route("/{id}/del" , name= "camas_del")
	 */
	public function del(Cama $cama){
		$em = $this->getDoctrine()->getManager();
		if($cama){
			try {
				$em->remove($cama);
				$em->flush();
				$this->addFlash('notice','Cama Eliminada satisfactoriamente');
			} catch (\Throwable $th) {
				$this->addFlash('error','Esta cama tiene registros , no se puede eliminar');
			}
		}
		return $this->redirectToRoute('camas_index');
	}


}
