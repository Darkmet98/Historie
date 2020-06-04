<?php


namespace App\Controller\Api;


use App\Controller\PoEntryController;
use App\Entity\PoFile;
use App\Entity\Projects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class PoEntryApi extends AbstractController
{
    /**
     * @Route("/entries/{id}", name="pofile_json_get", methods={"GET"})
     * @param $id
     * @return JsonResponse
     *
     * Return a list of components
     */
    public function getJsonList(Projects $id): JsonResponse
    {
        $poController = new PoEntryController();

        $pos = $id->getPoFiles();
        $data = [];

        foreach ($pos as $po) {
            array_push($data, $poController->GenerateObjectJson($po));
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/entries/{id}/{entry}", name="pofile_entry_get", methods={"GET"})
     * @Route("/entries/{id}/{entry}/{position}", name="pofile_entry_get_position", methods={"GET"})
     * @param Projects $id
     * @param int $entry
     * @param int|null $position
     * @return JsonResponse
     */
    public function getJsonEntry(Projects $id, int $entry, int $position=null): JsonResponse
    {
        $poEntry = new PoEntryController();

        //Get the PoFile
        $po = $poEntry->SearchEntry($id->getPoFiles()->toArray(), $entry);

        //Check the position
        $entryPosition = ($position == null)?$po->getPosition():$position;

        //Get the entry
        $poEntries = $po->getEntries();
        $entry = $poEntries[$entryPosition];

        return new JsonResponse($poEntry->GenerateEntryJson($entry, $id->getName(), $po->getName(), $entryPosition, count($poEntries)), Response::HTTP_OK);
    }

    /**
     * @Route("/entries/set", name="pofile_entry_set", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function setJsonEntry(Request $request)
    {
        $json = json_decode($request->getContent(), true);

        $po = $this->getDoctrine()
            ->getRepository(PoFile::class)
            ->find($json["id"]);

        $poEntries = $po->getEntries();

        $poEntries[$json["position"]]["Translated"] = $json["translation"];
        $po->setEntries($poEntries);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}