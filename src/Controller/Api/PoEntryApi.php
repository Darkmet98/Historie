<?php


namespace App\Controller\Api;


use App\Controller\PoEntryController;
use App\Entity\PoFile;
use App\Entity\Projects;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/entries/{id}", name="pofile_json_get", methods={"GET","POST"})
     * @param PaginatorInterface $paginator
     * @param Projects $id
     * @param Request $request
     * @return JsonResponse
     *
     * Return a list of components
     */
    public function getJsonList(PaginatorInterface $paginator, Projects $id, Request $request): JsonResponse
    {
        $poController = new PoEntryController();

        $pos = $id->getPoFiles();
        $data = [];
        $pageIndex = 1;

        //Check if the request is null
        if ($request->getContent() != null){
            $pageIndex = json_decode($request->getContent(), true)["page"];
        }

        //Dump the necessary data
        foreach ($pos as $po) {
            array_push($data, $poController->GenerateObjectJson($po));
        }

        //Generate a data count
        $count = round(count($data) / 5,0);

        //Set the pagination
        $pagination = $paginator->paginate(
            $data,
            $pageIndex,
            5
        );

        //Return the json
        return new JsonResponse([
            "Entries" => $pagination->getItems(),
            "NumberPages" => $count,
            "ProjectName" => $id->getName()
        ], Response::HTTP_OK);
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

        //Get the entries
        $poEntries = $po->getEntries();

        //Obtain the current entry
        $currentEntry = $poEntries[$entryPosition];

        //Obtain the previous entry
        $previousEntry = ($entryPosition-1 < 0)
            ? ["Original"=>"None", "Translated"=>"None"]
            : $poEntries[$entryPosition-1];

        //Obtain the next entry
        $nextEntry = ($entryPosition+1 == count($poEntries))
            ? ["Original"=>"None", "Translated"=>"None"]
            : $poEntries[$entryPosition+1];

        return new JsonResponse(
            $poEntry->GenerateEntryJson(
                $currentEntry,$previousEntry,$nextEntry, $id->getName(), $po->getName(),
                $entryPosition, count($poEntries)), Response::HTTP_OK);
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