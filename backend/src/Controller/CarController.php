<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/cars')]
class CarController extends AbstractController
{
    #[Route('', name: 'car_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, CarRepository $carRepository): JsonResponse
    {
        try {
            $cars = $carRepository->findAll();

            $jsonCars = $serializer->serialize($cars, 'json', ['groups' => ['car:read', 'model:read', 'brand:read']]);

            return new JsonResponse($jsonCars, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }


    #[Route('', name: 'car_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        try {
            $data = $request->getContent();
            $carPayload = json_decode($data, true);

            $model = $entityManager->getRepository(Model::class)->find($carPayload['model']);
            if (!$model) {
                return new JsonResponse(['error' => 'Model not found'], 404);
            }

            $car = $serializer->deserialize($data, Car::class, 'json', [
                'groups' => 'car:write',
                'object_to_populate' => new Car()
            ]);

            $car->setModel($model);

            $errors = $validator->validate($car);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            $entityManager->persist($car);
            $entityManager->flush();

            return new JsonResponse(['insertedId' => 'ok'], 201);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }


    #[Route('/{id}', name: 'car_show', methods: ['GET'])]
    public function show(
        int $id,
        SerializerInterface $serializer,
        CarRepository $carRepository
    ): JsonResponse {

        try {
            $model = $carRepository->findOneBy(['id' => $id]);

            if (!isset($model)) {
                return new JsonResponse(['error' => 'Car not found'], 404);
            }

            $jsonModel = $serializer->serialize($model, 'json', ['groups' => ['car:read', 'model:read', 'brand:read']]);

            return new JsonResponse($jsonModel, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }
}
