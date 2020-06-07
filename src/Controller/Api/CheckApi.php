<?php
namespace App\Controller\Api;

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
     * Check if the token is alive
     */
    public function checkApi(): JsonResponse
    {
        return new JsonResponse("OK", Response::HTTP_OK);
    }
}