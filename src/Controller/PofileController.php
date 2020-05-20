<?php

namespace App\Controller;

use App\Entity\Pofile;
use App\Entity\Projects;
use App\Form\PofileType;
use Cz\Git\GitRepository;
use Gettext\Generator\JsonGenerator;
use Gettext\Loader\PoLoader;
use http\Header;
use RecursiveDirectoryIterator as dirIterator;
use RecursiveIteratorIterator as recursiveIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pofile")
 */
class PofileController extends AbstractController
{
    /**
     * @Route("/", name="pofile_index", methods={"GET"})
     */
    public function index(): Response
    {
        $pofiles = $this->getDoctrine()
            ->getRepository(Pofile::class)
            ->findAll();

        return $this->render('pofile/index.html.twig', [
            'pofiles' => $pofiles,
        ]);
    }

    /**
     * @Route("/new", name="pofile_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pofile = new Pofile();
        $form = $this->createForm(PofileType::class, $pofile);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->GeneratePoFiles();

            return $this->redirectToRoute('pofile_index');
        }

        return $this->render('pofile/new.html.twig', [
            'pofile' => $pofile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pofile_show", methods={"GET"})
     */
    public function show(Pofile $pofile): Response
    {
        return $this->render('pofile/show.html.twig', [
            'pofile' => $pofile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pofile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pofile $pofile): Response
    {
        $form = $this->createForm(PofileType::class, $pofile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pofile_index');
        }

        return $this->render('pofile/edit.html.twig', [
            'pofile' => $pofile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pofile_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pofile $pofile): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pofile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pofile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pofile_index');
    }


    /**
     * @throws \Cz\Git\GitException
     *
     * Generate the po entries on the pofile
     */
    private function GeneratePoFiles() {

        //Get the project
        $project = $this->getDoctrine()
            ->getRepository(Projects::class)
            ->find($_POST["pofile"]["projectid"][0]);

        //Obtain the folder route
        $folderRoute = $this->getParameter('git_repository').'/'.$project->getName();

        //Check the git folder
        if ( !is_dir(  $this->getParameter('git_repository') ) ) {
            mkdir(  $this->getParameter('git_repository') );
        }

        //Check the project folder
        if ( !is_dir( $folderRoute ) ) {
            mkdir( $folderRoute );
        }

        $entityManager = $this->getDoctrine()->getManager();

        //Obtain the files from git
        $repo = GitRepository::cloneRepository($project->getRepository(), $folderRoute);
        $repo->checkout($project->getBranch());

        //Search the all po files
        $array = $this->searchPo($folderRoute);

        $poController = new PoEntryController();

        foreach ($array as $path) {
            //Load a .po file and export to .json
            $translations = (new PoLoader())->loadFile($path);

            $json = (new JsonGenerator())->generateString($translations);

            $poFile = new Pofile();
            $poFile->setName(basename($path));
            $poFile->setPath($path);
            $poFile->setPosition(0);
            $poFile->addProjectid($project);
            $poFile->setEntries($poController->FixJsonGeneration($json));
            $entityManager->persist($poFile);
        }
        $entityManager->flush();
    }

    /**
     * @param $path
     * @return array
     *
     * Search the po files on the directory
     */
    function searchPo(string $path) {
        $arr = [];

        $it = new dirIterator($path);
        $display = Array ( 'po' );
        foreach(new recursiveIterator($it) as $file)
        {
            $array = explode('.', $file);
            if (in_array(strtolower(array_pop($array)), $display))
                array_push($arr, $file);
        }
        return $arr;
    }

    /************************
     *  REST API FUNCTIONS  *
     ************************/

    /**
     * @Route("/json/get/{id}", name="pofile_json_get", methods={"GET"})
     * @param $id
     * @return JsonResponse
     *
     * Return a list of components
     */
    public function getJsonList(Projects $id): JsonResponse
    {
        header("Access-Control-Allow-Origin: *");
        $poController = new PoEntryController();

        $pos = $id->getPofileid();
        $data = [];

        foreach ($pos as $po) {
            array_push($data, $poController->GenerateObjectJson($po));
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/json/get/{id}/{entry}", name="pofile_entry_get", methods={"GET"})
     * @Route("/json/get/{id}/{entry}/{position}", name="pofile_entry_get_position", methods={"GET"})
     * @param Projects $id
     * @param int $entry
     * @param int|null $position
     * @return JsonResponse
     */
    public function getJsonEntry(Projects $id, int $entry, int $position=null): JsonResponse
    {
        header("Access-Control-Allow-Origin: *");
        $poEntry = new PoEntryController();

        //Get the PoFile
        $po = $poEntry->SearchEntry($id->getPofileid()->toArray(), $entry);

        //Check the position
        $entryPosition = ($position == null)?$po->getPosition():$position;

        //Get the entry
        $poEntries = json_decode($po->getEntries(), true);
        $entry = $poEntries[$entryPosition];

        return new JsonResponse($poEntry->GenerateEntryJson($entry, $id->getName(), $po->getName(), $entryPosition, count($poEntries)), Response::HTTP_OK);
    }

    /**
     * @Route("/json/set", name="pofile_entry_set", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function setJsonEntry(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        $json = json_decode($request->getContent(), true);

        $po = $this->getDoctrine()
            ->getRepository(Pofile::class)
            ->find($json["id"]);

        $poEntries = json_decode($po->getEntries(), true);

        $poEntries[$json["position"]]["Translated"] = $json["translation"];
        $po->setEntries(json_encode($poEntries, true));
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

}
