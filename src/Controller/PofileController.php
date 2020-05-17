<?php

namespace App\Controller;

use App\Entity\Pofile;
use App\Entity\Projects;
use App\Form\PofileType;
use Cz\Git\GitRepository;
use Gettext\Generator\JsonGenerator;
use Gettext\Loader\PoLoader;
use phpDocumentor\Reflection\Project;
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

    private function GeneratePoFiles() {
        $project = $this->getDoctrine()
            ->getRepository(Projects::class)
            ->find($_POST["pofile"]["projectid"][0]);

        $folderRoute = $this->getParameter('git_repository').'/'.$project->getName();

        if ( !is_dir(  $this->getParameter('git_repository') ) ) {
            mkdir(  $this->getParameter('git_repository') );
        }

        if ( !is_dir( $folderRoute ) ) {
            mkdir( $folderRoute );
        }

        $entityManager = $this->getDoctrine()->getManager();

        $repo = GitRepository::cloneRepository($project->getRepository(), $folderRoute);
        $repo->checkout($project->getBranch());

        $array = $this->searchPo($folderRoute);

        foreach ($array as $path) {
            //Load a .po file and export to .json
            $translations = (new PoLoader())->loadFile($path);
            $json = (new JsonGenerator())->generateString($translations);

            $pofile = new Pofile();
            $pofile->setName(basename($path));
            $pofile->setPath($path);
            $pofile->setPosition(0);
            $pofile->addProjectid($project);
            $pofile->setEntries($json);
            $entityManager->persist($pofile);
        }
        $entityManager->flush();
    }

    function searchPo($path) {
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
    //AJAX

    /**
     * @Route("/json/get/{id}", name="pofile_json_get", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getJsonList(Projects $id): JsonResponse
    {
        header("Access-Control-Allow-Origin: *");
        $pos = $id->getPofileid();
        $data = [];
        foreach ($pos as $po) {
            array_push($data, $this->GenerateObjectJson($po, true));
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
        $po = $this->SearchEntry($id->getPofileid()->toArray(), $entry);
        $poEntry = new PoEntryController();
        $entryPosition = ($position == null)?$po->getPosition():$position;
        $entry = $poEntry->getEntry($po->getEntries(), $entryPosition);

        return new JsonResponse($this->GenerateEntryJson($entry, $id->getName(), $po->getName(), $entryPosition), Response::HTTP_OK);
    }

    //JSON
    public function GenerateObjectJson(Pofile $po, bool $isList) {
        if($isList) return  [
            'Id'=>$po->getId(),
            'Name'=>$po->getName(),
            'Status'=>'Incomplete',
            'Translated'=>40
        ];
        else
            return null;
    }

    private function GenerateEntryJson(array $entry, string $name, string $file, int $index) {
        $ori = array_keys($entry["entry"])[0];
        return[
            "Project"=>$name,
            "Original"=>$ori,
            "Translated"=>$entry["entry"][$ori],
            "Context"=>$entry["id"],
            "File"=>$file,
            "Size"=>$entry["size"],
            "Index"=>$index
        ];
    }

    private function SearchEntry(array $arr, int $id){
        foreach ($arr as $entry){
            if($entry->getId() == $id)
                return $entry;
        }
        return null;
    }
}
