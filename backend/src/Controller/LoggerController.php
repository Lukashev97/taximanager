<?php

namespace App\Controller;

use App\Repository\LoggerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/logs')]
class LoggerController extends AbstractController
{

    #[Route('', name: 'logger_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, LoggerRepository $loggerRepository): JsonResponse
    {
        try {
            $logList = $loggerRepository->findAll();

            $jsonLogs = $serializer->serialize($logList, 'json', ['groups' => ['logger:read', 'car:read', 'driver:read']]);

            return new JsonResponse($jsonLogs, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }
}
