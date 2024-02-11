<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class Test extends AbstractController
{
    private $logger;
    private $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/hello', name: 'app_lucky_number')]
    public function testLog(): void
    {
        // Test if Monolog integration logs to Sentry
        $this->logger->error('My custom logged error.');

        // Test if an uncaught exception logs to Sentry
        throw new \RuntimeException('Example exception..');
    }

    #[Route('/hello2', name: 'app_lucky_number2')]
    public function testLog2(): void
    {
        // Test if Monolog integration logs to Sentry
        $this->logger->error('My custom logged error.');

        // Test if an uncaught exception logs to Sentry
        throw new \RuntimeException('Example exception!!!');
    }

    #[Route('/users', name: 'get_users',methods: "GET")]
    public function getUsers(UsersRepository $usersRepository): JsonResponse
    {
        $users = $usersRepository->findAll();
        $serializedUsers = json_decode($this->serializer->serialize($users, 'json'));

        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/user', name: 'get_user',methods: "GET")]
    public function getUsersAction(UsersRepository $usersRepository): JsonResponse
    {
        $users = $usersRepository->findOneBy(['id'=>1]);
        $serializedUsers = json_decode($this->serializer->serialize($users, 'json'));

        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
    #[Route('/user', name: 'add_user',methods: "POST")]
    public function addUsersAction( EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new Users();
        $user->setId(5);
        $user->setAge(33);
        $user->setName('Jaba');
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User created successfully'], JsonResponse::HTTP_CREATED);
    }
}
