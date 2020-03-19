<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Persona;
use AppBundle\Form\PersonaType;

/**
     * @Route("/miGrupo")
     */
class PersonasController extends Controller
{
    /**
     * @Route("/" , name="personas_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        $personas = $em->getRepository('AppBundle:Persona')->findByUser($user); 
        return $this->render('AppBundle:Personas:index.html.twig', array(
            'personas' => $personas
        ));
    }

    /**
     * @Route("/new" , name="personas_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $persona->setUser($user);
            $em->persist($persona);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );
            return $this->redirectToRoute('personas_index');
        }
        return $this->render('AppBundle:Personas:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit" , name="personas_edit")
     */
    public function editAction(Persona $persona , Request $request)
    {
     $em =$this->getDoctrine()->getManager();
     $form = $this->createForm(PersonaType::class, $persona);
     $form->handleRequest($request);
     $rol = $this->get('security.token_storage')->getToken()->getUser()->getRola();
     $servicios = $em->getRepository('AppBundle:ServiciosUs')->findByPersona($persona); 
     if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($persona);
        $em->flush();
        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );
        $direct = ($rol == 'ADMIN') ? 'personas_todas' : 'personas_index';
        return $this->redirectToRoute($direct);
    }
    
    return $this->render('AppBundle:Personas:edit.html.twig', array(
        'form' => $form->createView(),
        'info' => $persona->getUser()->getId(), 
        'idU' => $persona->getId(),
        'servicios' => $servicios,
    ));
}

    /**
     * @Route("/{id}/del" , name="personas_del")
     */
    public function delAction(Persona $persona)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($persona);
        $em->flush();
        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );
        return $this->redirectToRoute('personas_index');
    }

     /**
     * @Route("/todas" , name="personas_todas")
     */
    public function personasAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $us = $this->get('security.token_storage')->getToken()->getUser()->getRola();
        $personas = [];
        if($us === 'ADMIN'){
            $personas = $em->getRepository('AppBundle:Persona')->findAll(); 
        }
        return $this->render('AppBundle:Personas:personas.html.twig', array(
            'personas' => $personas
        ));
    }

}
