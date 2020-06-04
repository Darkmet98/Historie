<?php

namespace App\Controller;

use App\Entity\Projects;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $projects = $this->getDoctrine()
            ->getRepository(Projects::class)
            ->findAll();

        $pagination = $paginator->paginate(
            $projects,
            $request->query->getInt("page",1),
            6
        );

        return $this->render('main/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/project/{id}", name="main_project")
     */
    public function downloadPatch($id)
    {
        $projects = $this->getDoctrine()
            ->getRepository(Projects::class)
            ->find($id);

        $releases = $projects->getReleases();

        return $this->render('main/project.html.twig', [
            'releases' => $releases,
            'project' => $projects
        ]);
    }
}
