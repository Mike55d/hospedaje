<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Faqs;
use AppBundle\Form\FaqsType;

/**
 * @Route("faqs")
 */
class PreguntasFrecuentesController extends Controller
{
  /**
   * @Route("/" , name="faqs_index")
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $faqs = $em->getRepository('AppBundle:Faqs')->findAll();
    return $this->render('AppBundle:Faqs:index.html.twig', array(
      'faqs' => $faqs,
    ));
  }

  /**
   * @Route("/new", name="faqs_new")
   */
  public function nuevoAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
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
      'form' => $form->createView(),
    ));
  }

  /**
   * @Route("/{id}/edit", name="faqs_edit")
   */
  public function editaAction(Request $request , Faqs $faq)
  {
    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(FaqsType::class, $faq);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      $this->addFlash(
        'notice',
        'FAQ editada'
      );
      return $this->redirectToRoute('faqs_index',);
    }
    return $this->render('AppBundle:Faqs:edita.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
   * @Route("/{id}/del", name="faqs_del")
   */
  public function eliminaAction( Faqs $faq)
  {
    $em = $this->getDoctrine()->getManager();
    if ($faq) {
      $em->remove($faq);
      $em->flush();
      $this->addFlash(
        'notice',
        'FAQ eliminada'
      );
    }
    return $this->redirectToRoute('faqs_index',);
  }
}
