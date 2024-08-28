<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/brands')]
class BrandController extends AbstractController
{
    #[Route('', name: 'brand_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer, BrandRepository $brandRepository): JsonResponse
    {
        try {
            $brands = $brandRepository->findAll();

            $jsonBrands = $serializer->serialize($brands, 'json', ['groups' => 'brand:read']);

            return new JsonResponse($jsonBrands, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }


    #[Route('', name: 'brand_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        try {
            $data = $request->getContent();

            $brand = $serializer->deserialize($data, Brand::class, 'json', ['groups' => 'brand:write']);

            $errors = $validator->validate($brand);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            $entityManager->persist($brand);
            $entityManager->flush();

            return new JsonResponse(['insertedId' => $brand->getId()], 201);

        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'brand_show', methods: ['GET'])]
    public function show(
        int $id,
        SerializerInterface $serializer,
        BrandRepository $brandRepository
    ): JsonResponse {

        try {
            $brand = $brandRepository->findOneBy(['id' => $id]);

            if (!isset($brand)) {
                return new JsonResponse(['error' => 'Brand not found'], 404);
            }

            $jsonBrand = $serializer->serialize($brand, 'json', ['groups' => 'brand:read']);

            return new JsonResponse($jsonBrand, 200, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => $e->getMessage()], 400);
        }
    }

}
