<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Form\CreateAccountType;
use App\Entity\Account;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AccountRepository;
use App\Repository\OperationRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
            $account->setAccountNumber(random_int(1111111, 9999999));
            $account->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();
            
            return $this->redirectToRoute('index');
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
    /**
     * @Route("/operation/{id)}", name="operation", requirements={"id"="\d+"})
     */

    public function addoperation( Request $request, OperationRepository $accountRepository, int $accountId): Response
    {
        $account = new Account();
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        // On traite les données soumises lors de la requêtes dans l'objet form
        $form->handleRequest($request);
        // Si on a soumis un formulaire et que tout est OK
        if($form->isSubmitted() && $form->isValid()) {
            $operation->setDateOperation(new \DateTime());
            $operation->setAccount($this->getAccount()->getOperation());
            $account = $accountRepository->find($accountId);
            $operation->setAccount($account);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            // Attention les requêtes ne sont exécutées que lors du flush donc à ne pas oublier
            $entityManager->flush();

            return $this->redirectToRoute('index');
            
        }
        return $this->render('front/operation.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
