<?php

namespace AppBundle\Repository;

/**
 * MedidaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MedidaRepository extends \Doctrine\ORM\EntityRepository {
	public function getQbOrdenada() {
		$qb = $this->createQueryBuilder( 'm' );

		$qb->orderBy( 'm.creado', 'DESC' );

		return $qb;
	}
}
