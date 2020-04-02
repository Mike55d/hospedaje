<?php

namespace AppBundle\Repository;

/**
 * ReservacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservacionRepository extends \Doctrine\ORM\EntityRepository
{
	public function buscarFecha($mes,$año,$cama){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('r')
		->from('AppBundle:Reservacion', 'r')
		->where('MONTH(r.fechaInicio) = :mes AND YEAR(r.fechaInicio) = :anio')
		->orWhere('MONTH(r.fechaFin) = :mes AND YEAR(r.fechaFin) = :anio')
		->andWhere('r.cama = :cama')
		->setParameter('mes',$mes)
		->setParameter('anio',$año)
		->setParameter('cama',$cama);
		$query = $qb->getQuery();
		return $query->getResult();
	}

	public function buscarPersonas($mes,$año,$grupo,$usuario,$reserva){
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->select('r')
		->from('AppBundle:Persona', 'p')
		->join('p.reserva','r')
		->where('r.status = Aprobada')
		->andWhere('MONTH(r.fechaInicio) = :mes AND YEAR(r.fechaInicio) = :anio')
		->orWhere('MONTH(r.fechaFin) = :mes AND YEAR(r.fechaFin) = :anio')
		->setParameter('mes',$mes)
		->setParameter('anio',$año);
		if ($grupo) {
			$qb->andWhere('r')
			->setParameter('grupo',$grupo);
		}
		if ($usuario) {
			$qb->andWhere('r')
			->setParameter('usuario',$usuario);
		}
		if ($reserva) {
			$qb->andWhere('r')
			->setParameter('reserva',$reserva);
		}
		$query = $qb->getQuery();
		return $query->getResult();
	}
}
