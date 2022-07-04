<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'project_'), IsGranted('IS_AUTHENTICATED')]
class ProjectController extends CoreController
{
    const DEFAULT_ROUTE = 'project_index';
    const REDIRECT_ROUTE = 'project_index';

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $object = new Project();
        $form = $this->createForm(ProjectType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($object);

            $this->addFlash('success', "Nouveau projet créé !");
            return $this->redirectToRoute(self::REDIRECT_ROUTE, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/form.html.twig', [
            'edit' => false,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Request $request, Project $object): Response
    {
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Project $object): Response
    {
        $form = $this->createForm(ProjectType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', "Le projet a été modifié ({$object})");
            return $this->redirectToRoute(self::REDIRECT_ROUTE, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/form.html.twig', [
            'edit' => true,
            'form' => $form
        ]);
    }
}
