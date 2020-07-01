<?php

namespace AppBundle\Repository;

/**
 * ReservaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservaRepository extends \Doctrine\ORM\EntityRepository
{
	public function buscarSolicitudes(){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('r')
		->from('AppBundle:Reserva', 'r')
		->where('r.status != :reservado')
		->setParameter('reservado','Reservado');
		;
		$query = $qb->getQuery();
		return $query->getResult();
	}

	public function buscarSolicitudesUser($user){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('r')
		->from('AppBundle:Reserva', 'r')
		->where('r.status != :reservado AND r.user = :user')
		->setParameter('user',$user)
		->setParameter('reservado','Reservado');
		$query = $qb->getQuery();
		return $query->getResult();
	}

	public function buscarGrupo($grupo){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('r')
		->from('AppBundle:Reserva', 'r')
		->join('r.user' ,'u')
		->where('u.grupo = :grupo AND r.status = :reservado')
		->setParameter('grupo',$grupo)
		->setParameter('reservado','Reservado');
		$query = $qb->getQuery();
		return $query->getResult();
	}
}
