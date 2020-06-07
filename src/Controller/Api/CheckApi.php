<?php


namespace App\Controller\Api;


use App\Controller\PoEntryController;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CheckApi extends AbstractController
{
    /**
     * @Route("/check", name="check_api", methods={"GET"})
     * @param $id
     * @return JsonResponse
     *
     * Return a list of components
     */
    public function checkApi(): JsonResponse
    {
        return new JsonResponse("OK", Response::HTTP_OK);
    }
}