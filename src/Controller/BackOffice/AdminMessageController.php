<?php

namespace App\Controller\BackOffice;

use App\Entity\Category;
use App\Entity\Message;
use App\Repository\CategoryRepository;
use App\Repository\MessageRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\BackOffice\FormHandlerService;

#[Route('admin/')]
class AdminMessageController extends AbstractController
{


    #[Route('message_show/{id}', name: 'app_message_show_admin')]
    public function show(
        Message $message,
        Security $security,
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('BackOffice/Message/message_show.html.twig', [
            'title' => 'Fiche d\'un message',
            'message' => $message,
        ]);
    }

    #[Route('message_list', name: 'app_message_list_admin')]
    public function list(
        MessageRepository $messageRepo,
        Security           $security,
        Request            $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $messages = $messageRepo->searchByName($request->query->get('title', ''));

        return $this->render('BackOffice/Message/message_list.html.twig', [
            'title' => 'Liste des catégories',
            'message' => $messages,
            'libelle' => $request->query->get('id', ''),
        ]);
    }


    #[Route('delete_message/{id}', name: 'app_delete_message', methods: ['POST'])]
    public function delete(
        ?Message              $message,
        Security               $security,
        EntityManagerInterface $em
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if (!$message) {
            return $this->redirectToRoute('app_dashboard_admin');
        }

        $em->remove($message);
        $em->flush();
        return $this->redirectToRoute('app_message_list_admin');
    }
}
