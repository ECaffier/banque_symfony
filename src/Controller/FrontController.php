<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        return $this->render('front/index.html.twig', [

        ]);
    }

    /**
     * @Route("/operation", name="operation")
     */
    public function addoperation(Request $request): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        // On traite les données soumises lors de la requêtes dans l'objet form
        $form->handleRequest($request);
        // Si on a soumis un formulaire et que tout est OK
        if($form->isSubmitted() && $form->isValid()) {
            $operation->setDateOperation(new \DateTime());
            // On enregistre le nouveau sujet
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            // Attention les requêtes ne sont exécutées que lors du flush donc à ne pas oublier
            $entityManager->flush();

            return $this->redirectToRoute('index');
            
        }


        return $this->render('front/operation.html.twig', [
            "form" => $form->createView() 
        ]);
    }
}
