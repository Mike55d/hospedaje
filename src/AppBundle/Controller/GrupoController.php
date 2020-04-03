<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Grupo;
use AppBundle\Form\GrupoType;

    /**
     * @Route("grupo")
     */
class GrupoController extends Controller
{
    /**
     * @Route("/" , name="grupo_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
        return $this->render('AppBundle:Grupo:index.html.twig', array(
            'grupos' => $grupos
        ));
    }

    /**
     * @Route("/new" , name="grupo_new")
     */
    public function newAction(Request $request)
    {
        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($grupo);
            $em->flush();
            $this->addFlash(
                'notice',
                'Nuevo grupo creado'
            );
            return $this->redirectToRoute('grupo_index');
        }
        return $this->render('AppBundle:Grupo:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("{id}/edit" , name="grupo_edit")
     */
    public function editAction(Grupo $grupo , Request $request)
    {
        return $this->render('AppBundle:Grupo:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/del" , name="grupo_del")
     */
    public function delAction()
    {
        return $this->render('AppBundle:Grupo:del.html.twig', array(
            // ...
        ));
    }

}
