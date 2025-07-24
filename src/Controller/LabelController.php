<?php

namespace App\Controller;

use App\Entity\Lane;
use App\Entity\User;
use App\Entity\Board;
use App\Entity\Label;
use App\Form\LabelType;
use App\Entity\CardLabel;
use App\Form\CardLabelType;
use App\Repository\CardRepository;
use App\Repository\BoardRepository;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LabelController extends AbstractController
{
    #[Route('/labels', name: 'app_label_index')]
    public function index(LabelRepository $labelRepository): Response
    {
        $labels = $labelRepository->findAll();

        return $this->render('label/index.html.twig', [
            'labels' => $labels,
        ]);
    }

    #[Route('/new/label', name: 'app_label_new', methods: ['GET', 'POST'])]
    #[Route('/new/label/{id}', name: 'app_label_new_for_board', methods: ['GET', 'POST'])]
    #[Route('/new/label/{id}/assign/{cardId}/new', name: 'app_card_label_new_assign')]
    public function new(Request $request, EntityManagerInterface $em, ?int $id, ?int $cardId, CardRepository $cardRepository, BoardRepository $boardRepository): Response {
                
        $label = new Label();
        $availableBoards = null;  
        $card = null;
        $cardLabel = null;

        if ($id) {
            $board = $boardRepository->find($id);
            if ($board) {
                $label->setBoard($board);
                $availableBoards = [$board];
            }
        } else {
            $availableBoards = $boardRepository->findAll();
        }

        if ($cardId) {
            $card = $cardRepository->find($cardId);
            if ($card) {
                $cardLabel = new CardLabel();
                $cardLabel->setCard($card);
                $cardLabel->setLabel($label);
            }

        }

        $form = $this->createForm(LabelType::class, $label, [
            'available_boards' => $availableBoards,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($label);
            if ($cardLabel) {
                $em->persist($cardLabel);
            }
            $em->flush();

            return $this->redirectToRoute('app_board_show_board', ['id' => $id]);
        }

        return $this->render('label/new.html.twig', [
            'form' => $form,
        ]);
    }
}
