<?php

namespace App\Controller;

use App\Repository\ConsultantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantController extends AbstractController
{
    private $consultantRepository;

    public function __construct(ConsultantRepository $consultantRepository)
    {
        $this->consultantRepository = $consultantRepository;
    }

    #[Route('/consultants', name: 'add_consultant', methods: ['POST'])]
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

        $this->consultantRepository->save($firstName, $lastName, $email, $password);

        return new JsonResponse(['status' => 'Consultant created!'], Response::HTTP_CREATED);
    }

    #[Route('/consultants/{id}', name: 'get_one_consultant', methods: ['GET'])]
    public function get($id): JsonResponse
    {
        $consultant = $this->consultantRepository->findOneBy(['id' => $id]);

        if ($consultant == null) {
            return new JsonResponse(['status' => 'Administrateur not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $consultant->getId(),
            'firstName' => $consultant->getFirstName(),
            'lastName' => $consultant->getLastName(),
            'email' => $consultant->getEmail(),
            'password' => $consultant->getPassword(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    #[Route('/consultants', name: 'get_all_consultant', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $consultants = $this->consultantRepository->findAll();
        $data = [];

        foreach ($consultants as $consultant) {
            $data[] = [
                'id' => $consultant->getId(),
                'firstName' => $consultant->getFirstName(),
                'lastName' => $consultant->getLastName(),
                'email' => $consultant->getEmail(),
                'password' => $consultant->getPassword(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/consultants/{id}', name: 'update_consultants', methods: ['PUT'])]
    public function update($id, Request $request): JsonResponse
    {
        $consultant = $this->consultantRepository->findOneBy(['id' => $id]);

        if ($consultant == null) {
            return new JsonResponse(['status' => 'consultant not found!'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        empty($data['firstName']) ? true : $consultant->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $consultant->setLastName($data['lastName']);
        empty($data['email']) ? true : $consultant->setEmail($data['email']);
        empty($data['password']) ? true : $consultant->setPassword($data['password']);

        $updatedconsultant = $this->consultantRepository->update($consultant);

        return new JsonResponse(['status' => 'consultant mis à jour'], Response::HTTP_OK);
    }

    #[Route('/consultants/{id}', name: 'delete_consultant', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $consultant = $this->consultantRepository->findOneBy(['id' => $id]);

        if ($consultant == null) {
            return new JsonResponse(['status' => 'consultant not found!'], Response::HTTP_NOT_FOUND);
        }

        $this->consultantRepository->remove($consultant);

        return new JsonResponse(['status' => 'consultant deleted'], Response::HTTP_NO_CONTENT);
    }

    #[Route('/consultants/login', name: 'login_consultant', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];

        $utilisateur = $this->consultantRepository->findOneBy(
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if ($utilisateur == null) {
            return new JsonResponse(['status' => 'Email ou mot de passe invalide'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$utilisateur->getRole() == "consultant") {
            return new JsonResponse(['status' => 'Role utilisateur incorrect'], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['status' => 'Compte consultant trouvé'], Response::HTTP_OK);
    }
}
