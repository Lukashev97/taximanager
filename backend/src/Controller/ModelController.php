<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Brand;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/models')]
class ModelController extends AbstractController
{

    #[Route('', name: 'model_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, ModelRepository $modelRepository): JsonResponse
    {
        try {
            $models = $modelRepository->findAll();

            $jsonModels = $serializer->serialize($models, 'json', ['groups' => ['model:read', 'brand:read']]);

            return new JsonResponse($jsonModels, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }


    #[Route('', name: 'model_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        try {
            $data = $request->getContent();
            $modelPayload = json_decode($data, true);

            $brand = $entityManager->getRepository(Brand::class)->find($modelPayload['brand']);
            if (!$brand) {
                return new JsonResponse(['error' => 'Brand not found'], 404);
            }

            $model = $serializer->deserialize($data, Model::class, 'json', [
                'groups' => 'model:write',
                'object_to_populate' => new Model()
            ]);

            $model->setBrand($brand);

            $errors = $validator->validate($model);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            $entityManager->persist($model);
            $entityManager->flush();

            return new JsonResponse(['insertedId' => 'ok'], 201);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'model_show', methods: ['GET'])]
    public function show(
        int $id,
        SerializerInterface $serializer,
        ModelRepository $modelRepository
    ): JsonResponse {

        try {
            $model = $modelRepository->findOneBy(['id' => $id]);

            if (!isset($model)) {
                return new JsonResponse(['error' => 'Model not found'], 404);
            }

            $jsonModel = $serializer->serialize($model, 'json', ['groups' => ['brand:read', 'model:read']]);

            return new JsonResponse($jsonModel, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'model_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $model = $entityManager->getRepository(Model::class)->find($id);

            if (!$model) {
                return new JsonResponse(['error' => 'Model not found'], 404);
            }

            $entityManager->remove($model);
            $entityManager->flush();

            return new JsonResponse(['status' => 'Model was deleted'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 404);
        }

    }

}
