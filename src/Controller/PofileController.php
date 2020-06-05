<?php

namespace App\Controller;

use App\Entity\PoFile;
use App\Entity\Projects;
use Cz\Git\GitException;
use Cz\Git\GitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Gettext\Generator\JsonGenerator;
use Gettext\Loader\PoLoader;
use RecursiveDirectoryIterator as dirIterator;
use RecursiveIteratorIterator as recursiveIterator;

class PofileController extends EasyAdminController
{
    /**
     * @param PoFile $entity
     * @throws GitException
     */
    protected function persistEntity($entity) {

        //Get the project
        $project = $entity->getProject();

        $this->GeneratePoFiles($project);
    }

    /**
     * @param Projects $project
     * @throws GitException
     *
     * Generate the po entries from the pofile
     */
    private function GeneratePoFiles(Projects $project){

        $entityManager = $this->getDoctrine()->getManager();

        //Obtain the folder route
        $folderRoute = $this->getParameter('git_repository').'/'.self::SanitizeName($project->getName());

        //Check the git folder
        if ( !is_dir(  $this->getParameter('git_repository') ) ) {
            mkdir(  $this->getParameter('git_repository') );
        }

        //Check the project folder
        if ( !is_dir( $folderRoute ) ) {
            mkdir( $folderRoute );

            //Obtain the files from git
            $repo = GitRepository::cloneRepository($project->getRepository(), $folderRoute);
            $repo->checkout($project->getBranch());
        }
        else {
            //Open the current git folder
            $repo = new GitRepository($folderRoute);
            $repo->pull();
        }

        //Search the all po files
        $array = $this->searchPo($folderRoute);

        $poController = new PoEntryController();

        foreach ($array as $path) {
            //Load a .po file and export to .json
            $translations = (new PoLoader())->loadFile($path);

            $json = (new JsonGenerator())->generateString($translations);

            $poFile = $this->CheckPoExists($path, $project);
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

    /**
     * @param string $path
     * @param Projects $project
     * @return PoFile|object
     */
    function CheckPoExists(string $path, Projects $project){
        $poFile = $this->getDoctrine()
            ->getRepository(PoFile::class)
            ->findOneBy(array('Path' => $path));

        if($poFile != null)
            return $poFile;

        $poFile = new PoFile();
        $poFile->setName(basename($path));
        $poFile->setPath($path);
        $poFile->setPosition(0);
        $poFile->setProject($project);
        return $poFile;
    }

    /**
     * @param string $str
     * @return string|string[]|void
     *
     * Sanitize the name
     * https://stackoverflow.com/a/19018736
     */
    public static function SanitizeName ($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
