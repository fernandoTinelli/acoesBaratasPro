<?php

namespace App\Controller;

use App\Helper\UserFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
  Request,
  Response
};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
  private UserFactory $userFactory;
  private UserRepository $userRepository;

  public function __construct(UserFactory $userFactory, UserRepository $userRepository)
  {
    $this->userFactory = $userFactory;
    $this->userRepository = $userRepository;
  }

  #[Route('/registration', name: 'app_registration', methods: ['GET'])]
  public function index(Request $request): Response
  {
    return $this->render('login/registration.html.twig');
  }

  #[Route('/registration', name: 'app_register', methods: ['POST'])]
  public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
  {
    $dataRequest = $request->request->all();
    $user = $this->userFactory->create($dataRequest, $passwordHasher);

    $this->userRepository->add($user, true);

    $this->addFlash(
      'success',
      'Cadastro realizado com sucesso!'
    );

    return $this->redirectToRoute('app_login', [], 201);
  }
}
