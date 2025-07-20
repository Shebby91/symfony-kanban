<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\CardAssignment;
use App\Form\CardAssignmentType;
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
    
    #[Route('/assign/user', name: 'app_card_assign_user')]
    public function assignUser(Request $request, EntityManagerInterface $em): Response
    {
        $assignment = new CardAssignment();
        $form = $this->createForm(CardAssignmentType::class, $assignment);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $assignment->setAssignedAt(new \DateTimeImmutable());
            $em->persist($assignment);
            $em->flush();

            return $this->redirectToRoute('app_board_index');
        }

        return $this->render('card_assignment/new.html.twig', [
            'form' => $form,
        ]);
        
        $card = $em->getRepository(Card::class)->find($cardId);
        $user = $em->getRepository(User::class)->find(1);

        if (!$card || !$user) {
            throw $this->createNotFoundException('Card or User not found.');
        }

        $assignment = new CardAssignment();
        $assignment->setCard($card);
        $assignment->setUser($user);
        $assignment->setAssignedAt(new \DateTimeImmutable());

        $em->persist($assignment);
        $em->flush();

        return $this->redirectToRoute('app_board_show', [
            'id' => $card->getLane()->getBoard()->getId(),
        ]);
    }
}
