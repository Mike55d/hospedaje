<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Reserva;
use AppBundle\Form\ReservaType;


/**
 * @Route("reservas")
 */
class ReservasController extends Controller
{
    /**
     * @Route("/misReservas" , name= "reservas_misReservas")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $reservas = $em->getRepository('AppBundle:Reserva')
        ->findBy(['user' => $user,'status' => 'reservado']); 
        return $this->render('AppBundle:Reservas:index.html.twig', array(
         'reservas' => $reservas
     ));
    }

        /**
     * @Route("/{id}/new" , name= "reservas_new")
     */
    public function newAction(Reserva $reserva ,Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser(); 
        $personas = $em->getRepository('AppBundle:Persona')
        ->findBy(['user' => $user , 'reserva' => null]); 
        if ($reserva->getStatus() == 'Aprobada') {
            if ($request->get('personas')) {
                foreach ($request->get('personas') as $id) {
                    $Persona = $em->getRepository('AppBundle:')->find($id);
                    $Persona->setReserva($reserva);
                    $em->flush();
                }
            $reserva->setStatus('Registrada');
            $this->addFlash(
            'notice',
            'Your changes were saved!'
            );
            $this->redirectToRoute();
            }
            return $this->render('AppBundle:Reservas:new.html.twig', array(
                'personas'=>$personas
            ));
        }else{
            return $this->redirectToRoute('reservas_misReservas');
        }
    }

    /**
     * @Route("/edit" , name= "reservas_edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Reservas:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/del" , name= "reservas_del")
     */
    public function delAction()
    {
        return $this->render('AppBundle:Reservas:del.html.twig', array(
            // ...
        ));
    }

}
