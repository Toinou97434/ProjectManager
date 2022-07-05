<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectTimesheet;
use App\Form\TimesheetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/timesheet', name: 'timesheet_'), IsGranted('IS_AUTHENTICATED')]
class TimesheetController extends CoreController
{
    const DEFAULT_ROUTE = 'timesheet_index';
    const REDIRECT_ROUTE = 'timesheet_index';

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $objects = $this->em->getRepository(Project::class)->getUserProjects($this->getUser());

        return $this->render('timesheet/index.html.twig', [
            'objects' => $objects
        ]);
    }

    #[Route('/project-form-modal', name: 'form_modal')]
    public function _timesheetFormModal(Request $request): Response
    {
        $object = new ProjectTimesheet();

        $form = $this->createForm(TimesheetType::class, $object, ['action' => $this->generateUrl('timesheet_form_modal'), 'attr' => ['data-turbo-frame' => '_top']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($object);
            $this->em->flush();

            $this->addFlash('success', "Nouveau temps ajouté !");
            return $this->redirectToRoute(self::REDIRECT_ROUTE, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('timesheet/_modal-form.html.twig', [
            'edit' => false,
            'form' => $form
        ]);
    }
}
