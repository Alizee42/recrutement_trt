<?php

namespace App\Controller;

use App\Repository\RecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RecruteurController extends AbstractController
{
    private $recruteurRepository;

    public function __construct(RecruteurRepository $recruteurRepository)
    {
        $this->recruteurRepository = $recruteurRepository;
    }

    /**
     * @Route("/recruteurs", name="recruteur", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
       
        $email = $data['email'];
        $password = $data['password'];
        $compagnyName = $data['compagnyName'];
        $compagnyAdress = $data['compagnyAdress'];
        $isValid = $data['isValid'];
        if (empty($email)) {
            throw new NotFoundHttpException('Bad request');
        }

        $this->recruteurRepository->save($email, $password,$compagnyName,$compagnyAdress,$isValid);

        return new JsonResponse(['status' => 'Recruteur created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/recruteurs/{id}", name="get_one_recruteur", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $recruteur = $this->recruteurRepository->findOneBy(['id' => $id]);

        if($recruteur == null) {
            return new JsonResponse(['status' => 'Administrateur not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $recruteur->getId(),
            'email' => $recruteur->getEmail(),
            'password' => $recruteur->getPassword(),
            'compagnyName' => $recruteur->getCompagnyName(),
            'compagnyAdress' => $recruteur->getCompagnyAdress(),
            'isValid' => $recruteur->isIsValid(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/recruteurs", name="get_all_recruteur", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $recruteurs = $this->recruteurRepository->findAll();
        $data = [];

        foreach ($recruteurs as $recruteur) {
            $data[] = [
                'id' => $recruteur->getId(),
                'email' => $recruteur->getEmail(),
                'password' => $recruteur->getPassword(),
                'compagnyName' => $recruteur->getCompagnyName(),
                'compagnyAdress' => $recruteur->getCompagnyAdress(),
                'isValid' => $recruteur->isIsValid(),
                ];
            
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/recruteurs/{id}", name="update_recruteur", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $recruteur = $this->recruteurRepository->findOneBy(['id' => $id]);

        if($recruteur == null) {
            return new JsonResponse(['status' => 'recruteur not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        
        empty($data['email']) ? true : $recruteur->setEmail($data['email']);
        empty($data['password']) ? true : $recruteur->setPassword($data['password']);
        empty($data['compagnyName']) ? true : $recruteur->setCompagnyName($data['compagnyName']);
        empty($data['compagnyAdress']) ? true : $recruteur->setCompagnyAdress($data['compagnyAdress']);
        empty($data['isValid']) ? true : $recruteur->isIsValid($data['isValid']);

        $updatedRecruteur = $this->recruteurRepository->update($recruteur);

        return new JsonResponse($updatedRecruteur, Response::HTTP_OK);
    }

    /**
     * @Route("/recruteurs/{id}", name="delete_recruteur", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $recruteur = $this->recruteurRepository->findOneBy(['id' => $id]);

        if($recruteur == null) {
            return new JsonResponse(['status' => 'recruteur not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->recruteurRepository->remove($recruteur);

        return new JsonResponse(['status' => 'recruteur deleted'], Response::HTTP_NO_CONTENT);
    }
}
