<?php

namespace App\Controller\Admin;

use App\Controller\CoreController;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'admin_client_'), IsGranted('IS_AUTHENTICATED')]
class ClientController extends CoreController
{
    const DEFAULT_ROUTE = 'admin_client_index';
    const REDIRECT_ROUTE = 'admin_client_index';

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('admin/client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, ClientRepository $clientRepository): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client, true);

            return $this->redirectToRoute('admin_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/_create-form-modal', name: 'create_form_modal')]
    public function _createFormModal(Request $request): Response
    {
        $object = new Client();

        $form = $this->createForm(ClientType::class, $object, ['action' => $this->generateUrl('admin_client_create_form_modal'), 'attr' => ['data-turbo-frame' => '_top']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($object);
            $this->em->flush();

            $this->addFlash('success', "Nouveau client créé !");
            return $this->redirectToRoute(self::REDIRECT_ROUTE, [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/_modal-form.html.twig', [
            'edit' => false,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Client $client, int $id): Response
    {
        return $this->render('admin/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository, int $id): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client, true);

            return $this->redirectToRoute('admin_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository, int $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('admin_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
