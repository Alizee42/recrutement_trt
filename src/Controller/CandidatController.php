<?php

namespace App\Controller;

use App\Repository\CandidatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
class CandidatController extends AbstractController
{
    private $candidatRepository;

    public function __construct(CandidatRepository $candidatRepository)
    {
        $this->candidatRepository = $candidatRepository;
    }

    /**
     * @Route("/candidats", name="candidat", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
        $cv = $data['cv'];
        $isValid = $data['isValid'];
       
        if (empty($firstName)) {
            throw new NotFoundHttpException('Bad request');
        }

        $this->candidatRepository->save($firstName, $lastName, $email, $password, $cv, $isValid);

        return new JsonResponse(['status' => 'Candidat created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/candidats/{id}", name="get_one_candidat", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $candidat = $this->candidatRepository->findOneBy(['id' => $id]);

        if($candidat == null) {
            return new JsonResponse(['status' => 'Candidat not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $candidat->getId(),
            'firstName' => $candidat->getFirstName(),
            'lastName' => $candidat->getLastName(),
            'email' => $candidat->getEmail(),
            'password' => $candidat->getPassword(),
            'cv' => $candidat->getCv(),
            'isValid' => $candidat->isIsValid(),

            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/candidats", name="get_all_candidat", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $candidats = $this->candidatRepository->findAll();
        $data = [];

        foreach ($candidats as $candidat) {
            $data[] = [
                'id' => $candidat->getId(),
                'firstName' => $candidat->getFirstName(),
                'lastName' => $candidat->getLastName(),
                'email' => $candidat->getEmail(),
                'password' => $candidat->getPassword(),
                'cv' => $candidat->getCv(),
                'isValid' => $candidat->isIsValid(),
                ];
            
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/candidats/{id}", name="update_candidat", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $candidat = $this->candidatRepository->findOneBy(['id' => $id]);

        if($candidat == null) {
            return new JsonResponse(['status' => 'Candidat not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        empty($data['FirstName']) ? true : $candidat->setfirstName($data['firstName']);
        empty($data['lastName']) ? true : $candidat->setLastName($data['lastName']);
        empty($data['email']) ? true : $candidat->setEmail($data['email']);
        empty($data['password']) ? true : $candidat->setPassword($data['password']);
        empty($data['cv']) ? true : $candidat->setCv($data['cv']);
        empty($data['isValid']) ? true : $candidat->isIsValid($data['isValid']);
       
        $updatedCandidat = $this->candidatRepository->update($candidat);

        return new JsonResponse($updatedCandidat, Response::HTTP_OK);
    }

    /**
     * @Route("/candidats/{id}", name="delete_candidat", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $candidat = $this->candidatRepository->findOneBy(['id' => $id]);

        if($candidat == null) {
            return new JsonResponse(['status' => 'candidat not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->candidatRepository->remove($candidat);

        return new JsonResponse(['status' => 'administrateur deleted'], Response::HTTP_NO_CONTENT);
    }
}
