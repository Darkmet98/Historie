<?php

namespace App\Controller;

use App\Entity\Projects;
use Cz\Git\GitException;
use Cz\Git\GitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Config\Definition\Exception\Exception;

class ProjectsController extends EasyAdminController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Generate the PoEntries to the database
     */
    public function GeneratePoFilesAction()
    {
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(Projects::class)->find($id);

        try {
            $poFile = new PofileController();
            $poFile->GeneratePoFiles($entity, $this->getParameter('git_repository'), $this->em);

            $this->addFlash(
                'notice',
                $entity->getName().' updated and/or inserted new components!'
            );
        }
        catch (\Exception $e){
            $this->addFlash(
                'warning',
                'There has been an issue while updating the components! Are your repository and branch set up correctly?'
            );
        }


        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws GitException
     * Update the local po's and submit to the git
     */
    public function UploadPoAction(){

        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(Projects::class)->find($id);

        try {
            $poFile = new PofileController();
            $poFile->UpdateTranslationsAction($entity);
            $this->Commit($entity->getName(), $this->getUser()->getUsername(), $entity->getRepository());

            $this->addFlash(
                'notice',
                'Inserted the new translations from the '.$entity->getName().' to git!'
            );
        }
        catch (Exception $e){
            $this->addFlash(
                'warning',
                'There has been an issue while updating the components! Are your repository and branch set up correctly?'
            );
        }


        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);
    }

    /**
     * @param $projectName
     * @param $mail
     * @param $repository
     * @throws GitException
     *
     * Commit to the git
     */
    private function Commit($projectName, $mail, $repository){

        $folderRoute = $this->getParameter('git_repository').'/'.PofileController::SanitizeName($projectName);
        $repo = new GitRepository($folderRoute);
        $repo->addAllChanges();
        $repo->commit("Updated translation ".$projectName." for the user ".$mail."at ".date("D M d, Y G:i"));
        $repo->push($repository);
    }
}
