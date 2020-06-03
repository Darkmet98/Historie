<?php

namespace App\Controller;

use App\Entity\Pofile;
use Cz\Git\GitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Gettext\Generator\JsonGenerator;
use Gettext\Loader\PoLoader;
use RecursiveDirectoryIterator as dirIterator;
use RecursiveIteratorIterator as recursiveIterator;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pofile")
 */
class PofileController extends EasyAdminController
{
    /**
     * @param Pofile $entity
     * @throws \Cz\Git\GitException Generate the po entries on the pofile
     */
    protected function persistEntity($entity) {

        //Get the project
        $project = $entity->getProjectid()[0];

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
}
