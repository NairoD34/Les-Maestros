<?php

namespace App\Controller\BackOffice;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

// Contrôleur pour gérer les opérations liées aux messages dans le back-office.
#[Route('admin/')]
class AdminMessageController extends AbstractController
{

    // Affiche les détails d'un message spécifique.
    #[Route('message_show/{id}', name: 'app_message_show_admin')]
    public function show(
        Message  $message,
        Security $security,
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.
            return $this->redirectToRoute('app_index');
        }

        if (!$message) {
            // Redirige vers la liste si le message n'existe pas.
            return $this->redirectToRoute('app_message_list_admin');
        }

        return $this->render('BackOffice/Message/message_show.html.twig', [
            'title' => 'Fiche d\'un message',
            'message' => $message,
        ]);
    }


    // Affiche la liste des messages.
    #[Route('message_list', name: 'app_message_list_admin')]
    public function list(
        MessageRepository $messageRepo,
        Security          $security,
        Request           $request
    ): Response
    {

        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.
            return $this->redirectToRoute('app_index');
        }

        $messages = $messageRepo->searchByName($request->query->get('title', ''));

        return $this->render('BackOffice/Message/message_list.html.twig', [
            'title' => 'Liste des messages',
            'message' => $messages,
        ]);
    }


    // Supprime un message.
    #[Route('delete_message/{id}', name: 'app_delete_message', methods: ['POST'])]
    public function delete(
        ?Message               $message,
        Security               $security,
        EntityManagerInterface $em
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            // Redirige vers l'accueil si l'utilisateur n'a pas le rôle ADMIN.            
            return $this->redirectToRoute('app_index');
        }

        if (!$message) {
            // Redirige vers la liste des messages si ce dernier n'existe pas.
            return $this->redirectToRoute('app_message_list_admin');
        }

        $em->remove($message);
        $em->flush();
        return $this->redirectToRoute('app_message_list_admin');
    }
}
