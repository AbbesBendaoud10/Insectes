<?php

namespace Acme\InsecteBundle\Repository;

use Acme\InsecteBundle\Entity;
/**
 * TodoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
 class InsecteRepository extends \Doctrine\ORM\EntityRepository
{

    public function findFriends($insecteId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM Acme\InsecteBundle\Entity\Insect u JOIN u.friends f WHERE f.id = :id"
            )->setParameter('id', $insecteId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
