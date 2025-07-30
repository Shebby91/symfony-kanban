<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\CardAssignment;
use App\Form\CardAssignmentType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CardAssignmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CardAssignmentController extends AbstractController
{
    #[Route('/assignments', name: 'app_assignment_index')]
    public function index(CardAssignmentRepository $cardAssignmentRepository): Response
    {
        $assignments = $cardAssignmentRepository->findAll();

        return $this->render('card_assignment/index.html.twig', [
            'assignments' => $assignments,
        ]);
    }
    
    #[Route('/assign/{id}/user', name: 'app_card_assign_user_new_assignment')]
    public function assignUser(Request $request, EntityManagerInterface $em, ?int $id, CardRepository $cardRepository): Response
    {
        $assignment = new CardAssignment();

        $availableCards = null;  

        if ($id) {
            $card = $cardRepository->find($id);
            if ($card) {
                $assignment->setCard($card);
                $availableCards = [$card];
            }
        } else {
            $availableCards = $cardRepository->findAll();
        }
        
        $form = $this->createForm(CardAssignmentType::class, $assignment, ['available_cards' => $availableCards,]);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $assignment->setAssignedAt(new \DateTimeImmutable());
            $em->persist($assignment);
            $em->flush();

            return $this->redirectToRoute('app_board_show_board', ['id' => $card->getLane()->getBoard()->getId()]);
        }

        return $this->render('card_assignment/new.html.twig', [
            'form' => $form,
        ]);
    }
}
