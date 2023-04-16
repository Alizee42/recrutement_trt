<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    private $annonceRepository;

    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }

    
    #[Route('/annonces', name:'add_annonces', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'];
        $location = $data['location'];
        $description = $data['description'];
        $isValid = $data['isValid'];
       
        if (empty($title)) {
            throw new NotFoundHttpException('Bad request');
        }

        $this->annonceRepository->save($title, $location, $description, $isValid);

        return new JsonResponse(['status' => 'Annonce created!'], Response::HTTP_CREATED);
    }

   
    #[Route('/annonces/{id}', name:'get_one_annonces', methods: ['GET'])]
    public function get($id): JsonResponse
    {
        $annonce = $this->annonceRepository->findOneBy(['id' => $id]);

        if($annonce == null) {
            return new JsonResponse(['status' => 'Annonce not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $annonce->getId(),
            'title' => $annonce->getTitle(),
            'location' => $annonce->getLocation(),
            'description' => $annonce->getDescription(),
            'isValid' => $annonce->isIsValid(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/annonces', name:'get_all_annonces', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $annonces = $this->annonceRepository->findAll();
        $data = [];

        foreach ($annonces as $annonce) {
            $data[] = [
                'id' => $annonce->getId(),
                'title' => $annonce->getTitle(),
                'location' => $annonce->getLocation(),
                'description' => $annonce->getDescription(),
                'isValid' => $annonce->isIsValid(),
                ];
            
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    
    #[Route('/annonces/{id}', name:'update_annonces', methods: ['PUT'])]
    public function update($id, Request $request): JsonResponse
    {
        $annonce = $this->annonceRepository->findOneBy(['id' => $id]);

        if($annonce == null) {
            return new JsonResponse(['status' => 'Annonce not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        empty($data['title']) ? true : $annonce->setTitle($data['title']);
        empty($data['location']) ? true : $annonce->setLocation($data['location']);
        empty($data['description']) ? true : $annonce->setDescription($data['description']);
        empty($data['isValid']) ? true : $annonce->isIsValid($data['isValid']);
       
        $updatedAnnonce = $this->annonceRepository->update($annonce);

        return new JsonResponse(['status' => 'annonce mise à jour'], Response::HTTP_OK); 
    }

    
    #[Route('/annonces/{id}', name:'delete_annonces', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $annonce = $this->annonceRepository->findOneBy(['id' => $id]);

        if($annonce == null) {
            return new JsonResponse(['status' => 'Annonce not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->annonceRepository->remove($annonce);

        return new JsonResponse(['status' => 'Annonce deleted'], Response::HTTP_NO_CONTENT);
    }
}
