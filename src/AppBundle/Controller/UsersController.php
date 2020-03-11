<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;

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
    public function newAction()
    {
        return $this->render('AppBundle:Users:new.html.twig', array(
            // ...
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
        return $this->render('AppBundle:Users:user.html.twig', array(
            'form' => $user ,  
            'personas' => $per,
            'idU' => $user->getId()
        ));
    }

    /**
     * @Route("/users/del" , name="users_del")
     */
    public function delAction()
    {
        return $this->render('AppBundle:Users:del.html.twig', array(
            // ...
        ));
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
                'Your changes were saved!'
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
