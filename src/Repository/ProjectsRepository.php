<?php

namespace App\Repository;


use App\Entity\Projects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projects::class);
    }

    /**
     * @param string $id
     * @return mixed
     *
     * Genero un filtro de proyectos para devolver los que tengan asignados sus usuarios
     */
    public function getProjectsFiltro(string $id) {
        $em=$this->getEntityManager();
        $qb=$em->createQueryBuilder("p")->select("p")->
        from('App\Entity\Projects', 'p');

        $qb->innerJoin("p.userid", "us");
        $qb->where("us.id = :id");
        $qb->setParameter("id", $id);

        $consulta = $qb->getQuery();
        return $consulta->execute();
    }
}