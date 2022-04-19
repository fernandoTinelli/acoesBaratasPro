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
  public function __construct(private UserFactory $userFactory, private UserRepository $userRepository)
  {
  }

  #[Route('/registration', name: 'login_registration_index', methods: ['GET'])]
  public function index(Request $request): Response
  {
    return $this->render('login/registration.html.twig');
  }

  #[Route('/registration', name: 'login_registration_create', methods: ['POST'])]
  public function create(Request $request, UserPasswordHasherInterface $passwordHasher)
  {
    $user = $this->userFactory->create($request->request->all(), $passwordHasher);

    $this->userRepository->add($user, true);

    $this->addFlash(
      'success',
      'Cadastro realizado com sucesso!'
    );

    return $this->redirectToRoute('login_index', [], Response::HTTP_CREATED);
  }
}
