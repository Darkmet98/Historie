<?php


namespace App\Controller\Api;

use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class ProjectsApi extends AbstractController
{
    /**
     * @Route("/projects", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $projects = $this->getDoctrine()
            ->getRepository(Projects::class)
            ->findAll();
        $data = [];

        foreach ($projects as $project) {
            array_push($data, $this->GenerateObjectJson($project));
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }


    public function GenerateObjectJson(Projects $project) {
        return  [
            'Id'=>$project->getId(),
            'Name'=>$project->getName(),
            'Description'=>$project->getDescription(),
            'Repository'=>$project->getRepository(),
            'Branch'=>$project->getBranch()
        ];
    }
}