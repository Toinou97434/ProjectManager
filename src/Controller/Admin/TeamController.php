<?php

namespace App\Controller\Admin;

use App\Entity\UserTeam;
use App\Form\UserTeamType;
use App\Repository\UserTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/team')]
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_admin_team_index', methods: ['GET'])]
    public function index(UserTeamRepository $userTeamRepository): Response
    {
        return $this->render('admin/team/index.html.twig', [
            'user_teams' => $userTeamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserTeamRepository $userTeamRepository): Response
    {
        $userTeam = new UserTeam();
        $form = $this->createForm(UserTeamType::class, $userTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userTeamRepository->add($userTeam, true);

            return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/team/new.html.twig', [
            'user_team' => $userTeam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_team_show', methods: ['GET'])]
    public function show(UserTeam $userTeam): Response
    {
        return $this->render('admin/team/show.html.twig', [
            'user_team' => $userTeam,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserTeam $userTeam, UserTeamRepository $userTeamRepository): Response
    {
        $form = $this->createForm(UserTeamType::class, $userTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userTeamRepository->add($userTeam, true);

            return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/team/edit.html.twig', [
            'user_team' => $userTeam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_team_delete', methods: ['POST'])]
    public function delete(Request $request, UserTeam $userTeam, UserTeamRepository $userTeamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userTeam->getId(), $request->request->get('_token'))) {
            $userTeamRepository->remove($userTeam, true);
        }

        return $this->redirectToRoute('app_admin_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
