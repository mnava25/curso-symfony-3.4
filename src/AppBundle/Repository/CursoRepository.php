<?php

namespace AppBundle\Repository;

/**
 * CursoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CursoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCursos() {
        $query = $this->createQueryBuilder("c")
        ->where("c.precio > :precio")
        ->setParameter("precio","90")
        ->getQuery();
        $cursos = $query->getResult();
        return $cursos;
    }
}
