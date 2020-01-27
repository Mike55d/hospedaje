<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ServiciosType;
use AppBundle\Entity\Servicios;


class ServiciosController extends Controller
{
    /**
     * @Route("/servicios", name="index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $lav = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 1]);
        $tie = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 2]);
        $rot = $em->getRepository('AppBundle:Servicios')->findBy(['tipo' => 3]);
        // replace this example code with whatever you need
        return $this->render('AppBundle:Servicios:index.html.twig', array(
           #'form'=>$form->createView(),
           'servicios' => ['uno'=>$lav,'dos'=>$tie,'tre'=>$rot]
           
        ));
    }
    /**
     * @Route("/servicios/nuevo", name="servicios")
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
                'Your changes were saved!'
            );
            return $this->redirectToRoute('index',);
        }
        return $this->render('AppBundle:Servicios:nuevo.html.twig', array(
           'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/servicios/edita/{id}", name="edita_servicio")
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
                'Your changes were saved!'
            );
            return $this->redirectToRoute('index',);
        }
        return $this->render('AppBundle:Servicios:edita.html.twig', array(
           'form'=>$form->createView(),
           'datos'=> $Serv
        ));
    }

    /**
     * @Route("/servicios/elimina/{id}", name="elimina_servicio")
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
                    'Your changes were saved!'
            );
            }

            return $this->redirectToRoute('index',);
        
    }
    
}
