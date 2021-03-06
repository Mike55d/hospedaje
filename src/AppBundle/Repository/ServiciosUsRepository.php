<?php

namespace AppBundle\Repository;

/**
 * ServiciosUsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiciosUsRepository extends \Doctrine\ORM\EntityRepository
{
		public function buscarTipo($tipo , $reserva , $persona){
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->select('s')
		->from('AppBundle:Serviciosus', 's')
		->join('s.servicio','sr')
		->where('sr.tipo = :tipo AND s.reserva = :reserva AND s.persona = :persona')
		->setParameter('tipo',$tipo)
		->setParameter('reserva',$reserva)
		->setParameter('persona',$persona);
		$query = $qb->getQuery();
		return $query->getResult();
	}

		public function buscarReserva($tipo , $reserva ){
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->select('s')
		->from('AppBundle:Serviciosus', 's')
		->join('s.servicio','sr')
		->where('sr.tipo = :tipo AND s.reserva = :reserva ')
		->setParameter('tipo',$tipo)
		->setParameter('reserva',$reserva);
		$query = $qb->getQuery();
		return $query->getResult();
	}
}
