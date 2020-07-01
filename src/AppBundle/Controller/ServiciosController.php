<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;   
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\ServiciosType;
use AppBundle\Entity\Servicios;

/**
  * @Route("servicios")
  */
class ServiciosController extends Controller
{
  /**
  * @Route("/", name="servicios_index")
  */
  public function indexAction()
  {
    $em =$this->getDoctrine()->getManager(); 
    $lav = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 'lavanderia']);
    $tie = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 'tienda']);
    $rot = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 'roto']);
    return $this->render('AppBundle:Servicios:index.html.twig', array(
     'servicios' => ['uno'=>$lav,'dos'=>$tie,'tre'=>$rot]

   ));
  }
  /**
  * @Route("/nuevo", name="servicios_new")
  */
  public function nuevoAction(Request $request )
  {
    $em =$this->getDoctrine()->getManager(); 
    $Servicios = new Servicios();
    $form = $this->createForm(ServiciosType::class, $Servicios);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $Servicios->setTipo($Servicios->getTipo());
      $Servicios->setServicio($Servicios->getServicio());
      $Servicios->setCosto($Servicios->getCosto());
      $em->persist($Servicios);
      $em->flush();
      $this->addFlash(
        'notice',
        'Nuevo servicio creado satisfactoriamente'
      );
      return $this->redirectToRoute('index',);
    }
    return $this->render('AppBundle:Servicios:nuevo.html.twig', array(
     'form'=>$form->createView(),
   ));
  }

  /**
  * @Route("/edita/{id}", name="edita_servicio")
  */
  public function editaAction(Request $request )
  {
    $em =$this->getDoctrine()->getManager(); 
    $Servicios = new Servicios();
    $form = $this->createForm(ServiciosType::class, $Servicios);
    $Serv = $em->getRepository('AppBundle:Servicios')->find(['id'=>$request->get('id')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $Serv->setTipo($Servicios->getTipo());
      $Serv->setServicio($Servicios->getServicio());
      $Serv->setCosto($Servicios->getCosto());
      $em->persist($Serv);
      $em->flush();
      $this->addFlash(
        'notice',
        'Servicio editado satisfactoriamente'
      );
      return $this->redirectToRoute('index',);
    }
    return $this->render('AppBundle:Servicios:edita.html.twig', array(
     'form'=>$form->createView(),
     'datos'=> $Serv
   ));
  }

  /**
  * @Route("/elimina/{id}", name="elimina_servicio")
  */
  public function eliminaAction(Request $request )
  {
    $em =$this->getDoctrine()->getManager(); 
    $Serv = $em->getRepository('AppBundle:Servicios')->find(['id'=>$request->get('id')]);
    if($Serv){
      $em->remove($Serv);
      $em->flush();
      $this->addFlash(
        'notice',
        'Servicio eliminado satisfactoriamente'
      );
    }
    return $this->redirectToRoute('index',);

  }

  /**
  * @Route("/ver/{tipo}", name="serviciosVer")
  */
  public function verAction($tipo )
  {
    $em =$this->getDoctrine()->getManager();        
    $ser = $em->createQuery('SELECT s.id,s.servicio from AppBundle\Entity\Servicios as s where s.tipo = :t')
    ->setParameter('t',$tipo)->execute();
    return new Response(json_encode($ser));
  }

}
