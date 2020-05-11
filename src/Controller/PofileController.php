<?php

namespace App\Controller;

use App\Entity\Pofile;
use App\Form\PofileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pofile);
            $entityManager->flush();

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
}
