<?php

namespace App\Controller\Admin;

use App\Entity\UserJob;
use App\Form\UserJobType;
use App\Repository\UserJobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/job')]
class JobController extends AbstractController
{
    #[Route('/', name: 'app_admin_job_index', methods: ['GET'])]
    public function index(UserJobRepository $userJobRepository): Response
    {
        return $this->render('admin/job/index.html.twig', [
            'user_jobs' => $userJobRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserJobRepository $userJobRepository): Response
    {
        $userJob = new UserJob();
        $form = $this->createForm(UserJobType::class, $userJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userJobRepository->add($userJob, true);

            return $this->redirectToRoute('app_admin_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/job/new.html.twig', [
            'user_job' => $userJob,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_job_show', methods: ['GET'])]
    public function show(UserJob $userJob): Response
    {
        return $this->render('admin/job/show.html.twig', [
            'user_job' => $userJob,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserJob $userJob, UserJobRepository $userJobRepository): Response
    {
        $form = $this->createForm(UserJobType::class, $userJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userJobRepository->add($userJob, true);

            return $this->redirectToRoute('app_admin_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/job/edit.html.twig', [
            'user_job' => $userJob,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_job_delete', methods: ['POST'])]
    public function delete(Request $request, UserJob $userJob, UserJobRepository $userJobRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userJob->getId(), $request->request->get('_token'))) {
            $userJobRepository->remove($userJob, true);
        }

        return $this->redirectToRoute('app_admin_job_index', [], Response::HTTP_SEE_OTHER);
    }
}
