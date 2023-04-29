<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdministrateurController extends AbstractController
{
    private $administrateurRepository;

    public function __construct(AdministrateurRepository $administrateurRepository)
    {
        $this->administrateurRepository = $administrateurRepository;
    }

   
    #[Route('/administrateurs', name:'add_administrateur', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
       
        if (empty($firstName)) {
            throw new NotFoundHttpException('Bad request');
        }

        $this->administrateurRepository->save($firstName, $lastName, $email, $password);

        return new JsonResponse(['status' => 'Administrateur created!'], Response::HTTP_CREATED);
    }

    #[Route('/administrateurs/{id}', name:'get_one_administrateur', methods: ['GET'])]
    public function get($id): JsonResponse
    {
        $administrateur = $this->administrateurRepository->findOneBy(['id' => $id]);

        if($administrateur == null) {
            return new JsonResponse(['status' => 'Administrateur not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $administrateur->getId(),
            'firstName' => $administrateur->getFirstName(),
            'lastName' => $administrateur->getLastName(),
            'email' => $administrateur->getEmail(),
            'password' => $administrateur->getPassword(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/administrateurs', name:'get_all_administrateur', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $administrateurs = $this->administrateurRepository->findAll();
        $data = [];

        foreach ($administrateurs as $administrateur) {
            $data[] = [
                'id' => $administrateur->getId(),
                'firstName' => $administrateur->getFirstName(),
                'lastName' => $administrateur->getLastName(),
                'email' => $administrateur->getEmail(),
                'password' => $administrateur->getPassword(),
                ];
            
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/administrateurs/{id}', name:'update_administrateur', methods: ['PUT'])]
    public function update($id, Request $request): JsonResponse
    {
        $administrateur = $this->administrateurRepository->findOneBy(['id' => $id]);

        if($administrateur == null) {
            return new JsonResponse(['status' => 'Administrateur not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        empty($data['firstName']) ? true : $administrateur->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $administrateur->setLastName($data['lastName']);
        empty($data['email']) ? true : $administrateur->setEmail($data['email']);
        empty($data['password']) ? true : $administrateur->setPassword($data['password']);
       
        $updatedAdministrateur = $this->administrateurRepository->update($administrateur);

        return new JsonResponse(['status' => 'administrateur mis à jour'], Response::HTTP_OK);
    }

    #[Route('/administrateurs/{id}', name:'delete_administrateur', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $administrateur = $this->administrateurRepository->findOneBy(['id' => $id]);

        if($administrateur == null) {
            return new JsonResponse(['status' => 'administrateur not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->administrateurRepository->remove($administrateur);

        return new JsonResponse(['status' => 'administrateur deleted'], Response::HTTP_NO_CONTENT);
    }

    #[Route('/administrateurs/login', name:'login_administrateur', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];

        $utilisateur = $this->administrateurRepository->findOneBy(
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if($utilisateur == null) {
            return new JsonResponse(['status' => 'Email ou mot de passe invalide'], Response::HTTP_UNAUTHORIZED);
        }

        if(!$utilisateur->getRole() == "administrateur") {
            return new JsonResponse(['status' => 'Role utilisateur incorrect'], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['status' => 'Compte administrateur trouvé'], Response::HTTP_OK);
    }


}
