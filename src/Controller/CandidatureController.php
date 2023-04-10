<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CandidatureController extends AbstractController
{
    private $candidatureRepository;

    public function __construct(CandidatureRepository $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    /**
     * @Route("/candidatures", name="candidature", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $statut = $data['statut'];
       
       
        if (empty($statut)) {
            throw new NotFoundHttpException('Bad request');
        }

        $this->candidatureRepository->save($statut);

        return new JsonResponse(['status' => 'Candidature created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/candidatures/{id}", name="get_one_candidature", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $candidature = $this->candidatureRepository->findOneBy(['id' => $id]);

        if($candidature == null) {
            return new JsonResponse(['status' => 'Candidature not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $candidature->getId(),
            'statut' => $candidature->getStatut(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/candidatures", name="get_all_candidature", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $candidatures = $this->candidatureRepository->findAll();
        $data = [];

        foreach ($candidatures as $candidature) {
            $data[] = [
                'id' => $candidature->getId(),
                'statut' => $candidature->getStatut(),
                ];
            
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/candidatures/{id}", name="update_candidature", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $candidature = $this->candidatureRepository->findOneBy(['id' => $id]);

        if($candidature == null) {
            return new JsonResponse(['status' => 'candidature not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        empty($data['statut']) ? true : $candidature->setStatut($data['statut']);
   
        $updatedcandidature = $this->candidatureRepository->update($candidature);

        return new JsonResponse($updatedcandidature, Response::HTTP_OK);
    }

    /**
     * @Route("/candidatures/{id}", name="delete_candidature", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $candidature = $this->candidatureRepository->findOneBy(['id' => $id]);

        if($candidature == null) {
            return new JsonResponse(['status' => 'candidature not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->candidatureRepository->remove($candidature);

        return new JsonResponse(['status' => 'candidature deleted'], Response::HTTP_NO_CONTENT);
    }
}

