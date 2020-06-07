<?php

namespace App\Controller;

use App\Entity\PoFile;
use App\Entity\Projects;
use Cz\Git\GitException;
use Cz\Git\GitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Gettext\Generator\JsonGenerator;
use Gettext\Generator\PoGenerator;
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

        $this->GeneratePoFiles($project, $this->getParameter('git_repository'));
    }

    /**
     * @param Projects $project
     * @param string $gitPath
     * @param null $manager
     * @throws GitException
     * Generate the po entries from the pofile
     */
    public function GeneratePoFiles(Projects $project, string $gitPath, $manager=null){

        $entityManager = ($manager == null)?$this->getDoctrine()->getManager():$manager;

        //Obtain the folder route
        $folderRoute = $gitPath.'/'.self::SanitizeName($project->getName());

        //Check the git folder
        if ( !is_dir(  $gitPath ) ) {
            mkdir(  $gitPath );
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

            $poFile = $this->CheckPoExists($path, $project, $entityManager);
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
     * @param $doctrine
     * @return PoFile|object
     */
    function CheckPoExists(string $path, Projects $project, $doctrine){
        $poFile = $doctrine
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
     * @param Projects $project
     * Update the translations files
     */
    public function UpdateTranslationsAction(Projects $project){
        $poFiles = $project->getPoFiles();
        $loader = new PoLoader();

        foreach ($poFiles as $poFile){
            $po = $loader->loadFile($poFile->getPath());
            $entries = $poFile->getEntries();

            foreach ($entries as $entry){
                if($entry["Translated"] != null) {
                    $translation = $po->find($entry["Context"], $entry["Original"]);
                    if ($translation != null) {
                        $translation->translate($entry["Translated"]);
                        $po->addOrMerge($translation);
                    }
                }
            }

            $poGenerator = new PoGenerator();
            $poGenerator->generateFile($po, $poFile->getPath());
        }
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
