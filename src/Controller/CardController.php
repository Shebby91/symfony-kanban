<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Lane;
use App\Entity\User;
use App\Entity\Label;
use App\Form\CardType;
use App\Entity\CardLabel;
use App\Repository\CardRepository;
use App\Repository\LaneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CardController extends AbstractController
{
    #[Route('/cards', name: 'app_card_index')]
    public function index(CardRepository $cardRepository): Response
    {
        $cards = $cardRepository->findAll();

        return $this->render('card/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/{cardId}/{labelId}/assign/new', name: 'app_card_label_assign')]
    public function assign(int $cardId, int $labelId, Request $request, EntityManagerInterface $em): Response
    {
        $card = $em->getRepository(Card::class)->find($cardId);

        $label = $em->getRepository(Label::class)->find($labelId);

        if (!$card || !$label) {
            throw $this->createNotFoundException('Card or Label not found.');
        }

        $cardLabel = new CardLabel();
        $cardLabel->setCard($card);
        $cardLabel->setLabel($label);

        $em->persist($cardLabel);
        $em->flush();

        return $this->redirectToRoute('app_board_show_board', ['id' => $card->getLane()->getBoard()->getId()]);
    }

    #[Route('/new/card', name: 'app_card_new')]
    #[Route('/new/card/{id}', name: 'app_card_new_for_lane')]
    public function new(Request $request, EntityManagerInterface $em, ?int $id, LaneRepository $laneRepository): Response {
        
        $card = new Card();
        
        $availableLanes = null;  

        if ($id) {
            $lane = $laneRepository->find($id);
            if ($lane) {
                $card->setLane($lane);
                $availableLanes = [$lane];
            }
        } else {
            $availableLanes = $laneRepository->findAll();
        }

        $form = $this->createForm(CardType::class, $card, ['available_lanes' => $availableLanes,]);
        $form->handleRequest($request);  
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $card->setCreatedAt(new \DateTimeImmutable());
            $card->setCreatedBy($em->getRepository(User::class)->find(1));
            
            $em->persist($card);
            $em->flush();

            return $this->redirectToRoute('app_board_index');
        }

        return $this->render('card/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/card/{id}', name: 'app_card_show')]
    public function show(Card $card): Response
    {

        //TODO TABLE WITH ADD EACH LABEL TO THIS CARD
        
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }
}
