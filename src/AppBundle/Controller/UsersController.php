<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersController extends Controller
{
  /**
   * @Route("/users/" , name="users_index")
   */
  public function indexAction()
  {
    return $this->render('AppBundle:Users:index.html.twig', array(
          // ...
    ));
  }

  /**
   * @Route("/users/new" , name="users_new")
   */
  public function newAction(Request $request)
  { 
    $em =$this->getDoctrine()->getManager(); 
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $this->get('security.password_encoder')
      ->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);
      $user->setRoles("ROLE_USER");
      $user->setRolA("USER");
      $user->setActive(1);
      $em->persist($user);
      $em->flush();
      $this->addFlash(
        'notice',
        'Usuario '.$user->getName().' creado satisfactoriamente'
      );
      return $this->redirectToRoute('users_all');
    }
    return $this->render('AppBundle:Users:new.html.twig', array(
     'form'=>$form->createView()
   ));
  }

  /**
   * @Route("/{id}/edit" , name="users_edit")
   */
  public function editAction(User $user, Request $request)
  {
    $em =$this->getDoctrine()->getManager();
    $rol = $this->get('security.token_storage')->getToken()->getUser()->getRola();
    $per = [];
    if($rol === 'ADMIN'){
      $per = $em->getRepository('AppBundle:Persona')->findBy(['user' => $user->getId()]);
    }
    $personas = $em->getRepository('AppBundle:Persona')->findByUser($user->getId()); 
    $reservacionesUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'pendiente', 'user'=> $user->getId()]); 
    $reservaciones = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Reservado']); 
    $reservacionesUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Reservado', 'user'=> $user->getId()]); 
    $aprobadasUser = $em->getRepository('AppBundle:Reserva')->findBy(['status' => 'Aprobada', 'user'=> $user->getId()]); 
    return $this->render('AppBundle:Users:user.html.twig', array(
      'form' => $user ,  
      'personas' => $per,
      'idU' => $user->getId(),
      'reservasUser'=> $reservacionesUser,
      'personas' => $personas,
      'solicitudesUser' => $reservacionesUser,
      'aprobadasUser' => $aprobadasUser,
    ));
  }

  /**
   * @Route("/users/del" , name="users_del")
   */
  public function delAction(Request $request)
  {
  }

  /**
   * @Route("/users/changeStatus" , name="users_changeStatus")
   */
  public function changeStatus(Request $request)
  {
    $em =$this->getDoctrine()->getManager();
    $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
    $user->setActive(!$user->getActive());
    $em->flush();
    return new JsonResponse('ok');
  }

   /**
   * @Route("/register" , name="users_register")
   */
   public function registerAction(Request $request )
   {
    $em =$this->getDoctrine()->getManager(); 
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $this->get('security.password_encoder')
      ->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);
      $user->setRoles("ROLE_USER");
      $user->setRolA("USER");
      $user->setActive(1);
      $em->persist($user);
      $em->flush();
      $this->addFlash(
        'notice',
        'Te haz registrado satisfactoriamente'
      );
      return $this->redirectToRoute('login');
    }
    return $this->render('AppBundle:Users:register.html.twig', array(
     'form'=>$form->createView()
   ));
  }

  /**
   * @Route("/users/todos" , name="users_all")
   */
  public function todosAction()
  {
    $em =$this->getDoctrine()->getManager(); 
    $us = $this->get('security.token_storage')->getToken()->getUser()->getRola();
    $users = [];
    if($us === 'ADMIN'){
      $users = $em->getRepository('AppBundle:User')->findAll(); 
    }
    return $this->render('AppBundle:Users:todos.html.twig', array(
      'users' => $users
    ));
  }

}
