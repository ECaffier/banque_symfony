<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Account;
use App\Form\CreateAccountType;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/front", name="front")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [

        ]);
    }

    /**
     * @Route("/createAccount", name="createAccount")
     */
    public function createAccount(Request $request): Response
    {

        $account = new Account();
        $form = $this->createForm(CreateAccountType::class, $account);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($account);
        $entityManager->flush();

        return $this->render('createAccount/createAccount.html.twig',[
            'registrationForm' => $form->createView(),
        ]);
    }
}
