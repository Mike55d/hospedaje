<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\FaqsType;
use AppBundle\Entity\Faqs;


class FaqsController extends Controller
{
    /**
     * @Route("/faqs", name="faqs_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        return $this->render('AppBundle:Faqs:index.html.twig', array(
           #'form'=>$form->createView(),
           'faqs' => $em->getRepository('AppBundle:Faqs')->findAll()
           
        ));
    }

        /**
     * @Route("/faqs/nuevo", name="faqs")
     */
    public function nuevoAction(Request $request )
    {
        $em =$this->getDoctrine()->getManager(); 
        $Faqs = new Faqs();
        $form = $this->createForm(FaqsType::class, $Faqs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Faqs->setPregunta($Faqs->getPregunta());
            $Faqs->setRespuesta($Faqs->getRespuesta());
            $Faqs->setActiva(1);
            $em->persist($Faqs);
            $em->flush();
            $this->addFlash(
                'notice',
                'Nueva FAQ creada'
            );
            return $this->redirectToRoute('faqs_index',);
        }
        return $this->render('AppBundle:Faqs:nuevo.html.twig', array(
           'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/faqs/edita/{id}", name="edita_Faq")
     */
    public function editaAction(Request $request )
    {
        $em =$this->getDoctrine()->getManager(); 
        $Faqs = new Faqs();
        $form = $this->createForm(FaqsType::class, $Faqs);
        $Faq = $em->getRepository('AppBundle:Faqs')->find(['id'=>$request->get('id')]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $Faq->setPregunta($Faqs->getPregunta());
            $Faq->setRespuesta($Faqs->getRespuesta());
            $em->persist($Faq);
            $em->flush();
            $this->addFlash(
                'notice',
                'FAQ editada'
            );
            return $this->redirectToRoute('faqs_index',);
        }
        return $this->render('AppBundle:Faqs:edita.html.twig', array(
           'form'=>$form->createView(),
           'datos'=> $Faq
        ));
    }

    /**
     * @Route("/Faqs/elimina/{id}", name="elimina_faq")
     */
    public function eliminaAction(Request $request )
    {
        $em =$this->getDoctrine()->getManager(); 
        $Serv = $em->getRepository('AppBundle:Faqs')->find(['id'=>$request->get('id')]);
        
        
            if($Serv){
                $em->remove($Serv);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'FAQ eliminada'
            );
            }

            return $this->redirectToRoute('faqs_index',);
        
    }

    
    
}
