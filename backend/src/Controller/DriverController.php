<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\Logger;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/drivers')]
class DriverController extends AbstractController
{

    #[Route('', name: 'driver_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, DriverRepository $driverRepository): JsonResponse
    {
        try {
            $driverList = $driverRepository->findAll();

            $jsonDrivers = $serializer->serialize($driverList, 'json', ['groups' => ['driver:read', 'car:read', 'model:read', 'brand:read']]);

            return new JsonResponse($jsonDrivers, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

    #[Route('', name: 'driver_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        try {
            $data = $request->getContent();
            $driverPayload = json_decode($data, true);

            $driver = $serializer->deserialize($data, Driver::class, 'json', [
                'groups' => 'driver:write',
                'object_to_populate' => new Driver()
            ]);

            if (isset($driverPayload['car'])) {
                $car = $entityManager->getRepository(Car::class)->find($driverPayload['car']);
                if (!$car) {
                    return new JsonResponse(['error' => 'Car not found'], 404);
                }
                $driver->setCar($car);
            }

            $errors = $validator->validate($driver);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            $entityManager->persist($driver);
            $entityManager->flush();

            return new JsonResponse(['insertedId' => 'ok'], 201);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }


    #[Route('/{id}', name: 'driver_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $driver = $entityManager->getRepository(Driver::class)->find($id);

            if (!$driver) {
                return new JsonResponse(['error' => 'Driver not found'], 404);
            }

            $data = $request->getContent();
            $driverData = json_decode($data, true);
            $car = null;

            if (isset($driverData['car'])) {
                $car = $entityManager->getRepository(Car::class)->find($driverData['car']);
                if (!$car) {
                    return new JsonResponse(['error' => 'Car not found'], 404);
                }
                $driver->setCar($car);
            }

            $serializer->deserialize($data, Driver::class, 'json', [
                'groups' => ['driver:write' . 'car:write'],
                'object_to_populate' => $driver
            ]);

            $errors = $validator->validate($driver);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            // if we change a car of the particular driver we should write down the logs afterwards 

            if (isset($car)) {
                $newLog = new Logger();
                $formattedText = 'The car has been replaced for a driver\n %s \nCurrent car information: %s';
                $newLog->setDriver($driver);
                $newLog->setCar($car);
                $newLog->setText(sprintf($formattedText, $driver, $car));
                $entityManager->persist($newLog);
            }

            $entityManager->flush();

            return new JsonResponse(['status' => 'Driver has been updated'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

}
