<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AccountRepository;
use App\Repository\OperationRepository;
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
        $accounts = $this->getUser()->getAccounts();

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



    /**
     * @Route("front/single/{id}", name="single", requirements={"id"="\d+"})
     */
    public function single(int $id=1, AccountRepository $accountRepository): Response
    {
        $account = $accountRepository->find($id);

        $operation = new Operation;
        

        return $this->render('front/single.html.twig', [
            'account' => $account,
            'operation'=> $operation,
        ]);
    }
}
