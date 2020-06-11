<?php


namespace App\Controller\Api;

use App\Entity\Projects;
use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class ProjectsApi extends AbstractController
{
    /**
     * @Route("/projects", methods={"GET", "POST"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return JsonResponse
     *
     * Get the available projects
     */
    public function getAll(PaginatorInterface $paginator, Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(),true);
        $pageIndex = $json["page"];

        //Get the projects from the users
        $projects = null;
        if(!is_null($json)) {
            $projects = $this->getDoctrine()
                ->getRepository(Projects::class)
                ->getProjectsFiltro($json["id"]);
        }
        else{
            $projects = $this->getDoctrine()
                ->getRepository(Projects::class)
                ->findAll();
        }

        //Parse the projects
        $data = [];
        foreach ($projects as $project) {
            //Check if the project has components
            if(count($project->getPoFiles()) != 0)
                array_push($data, $this->GenerateObjectJson($project));
        }

        //Generate a data count
        $count = count($data) / 3;
        $round = round($count,0, PHP_ROUND_HALF_DOWN);
        
        if(is_float($count))
            $round++;

        //Set the pagination
        $pagination = $paginator->paginate(
            $data,
            $pageIndex,
            3
        );

        return new JsonResponse([
            "Projects"=> $pagination->getItems(),
            "NumberPages" => $round
        ], Response::HTTP_OK);
    }


    public function GenerateObjectJson(Projects $project) {
        return  [
            'Id'=>$project->getId(),
            'Name'=>$project->getName(),
            'Description'=>$project->getDescription(),
            'Icon'=>$project->getIcon()
        ];
    }
}