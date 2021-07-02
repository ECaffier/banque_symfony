<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $accountRepository = $this->getDoctrine()->getRepository(Account::class);
        $accounts = $accountRepository->findAll();

        return $this->render('front/index.html.twig', [
            'accounts' => $accounts,
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

        if($form->isSubmitted() && $form->isValid()){
            $account->setDateCreation(new \DateTime());
            $account->setType($account->getType());
            $account->setAmount($account->getAmount());
            $account->setAccountNumber($account->getAccountNumber());
            $account->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();
        }   


        return $this->render('createAccount/createAccount.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
