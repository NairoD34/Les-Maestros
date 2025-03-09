<?php

namespace App\Service\BackOffice;

use App\Entity\Users;
use App\Repository\MessageRepository;
use App\Repository\OrderRepository;
use App\Repository\UsersRepository;

// Classe pour gérer les opérations liées aux KPIs dans le back-office.
class KpiService
{
    private $orderRepository;
    private $messageRepository;
    private $usersRepository;

    public function __construct(UsersRepository   $usersRepository,
                                OrderRepository   $orderRepository,
                                MessageRepository $messageRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->messageRepository = $messageRepository;
        $this->usersRepository = $usersRepository;
    }

    // Methode pour obtenir toutes les données des KPIs.
    public function getAllData()
    {
        $userCount = $this->usersRepository->countUsers();
        $orderCount = $this->orderRepository->countOrders();
        $TIRevenue = $this->orderRepository->countTaxIncludedRevenue();
        $messageCount = $this->messageRepository->countMessages();

        $data = [
            "users" => $userCount,
            "orders" => $orderCount,
            "revenue" => $TIRevenue,
            "messages" => $messageCount
        ];
        return $data;
    }

}
