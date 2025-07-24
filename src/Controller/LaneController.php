<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Lane;
use App\Entity\Board;
use App\Form\LaneType;
use App\Repository\BoardRepository;
use App\Repository\LaneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LaneController extends AbstractController
{
    #[Route('/lanes', name: 'app_lane_index')]
    public function index(LaneRepository $laneRepository): Response
    {
        $lanes = $laneRepository->findAll();

        return $this->render('lane/index.html.twig', [
            'lanes' => $lanes,
        ]);
    }

    #[Route('/new/lane', name: 'app_lane_new')]
    #[Route('/new/lane/{id}', name: 'app_lane_new_for_board')]
    public function new(Request $request, EntityManagerInterface $em, ?int $id, BoardRepository $boardRepository): Response {
        
        $lane = new Lane();

        $availableBoards = null;  

        if ($id) {
            $board = $boardRepository->find($id);
            if ($board) {
                $lane->setBoard($board);
                $availableBoards = [$board];
            }
        } else {
            $availableBoards = $boardRepository->findAll();
        }


        $form = $this->createForm(LaneType::class, $lane, [
            'available_boards' => $availableBoards,
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lane);
            $em->flush();
        return $this->redirectToRoute('app_board_show_board', ['id' => $id]);
        }

        return $this->render('lane/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/lane/{id}', name: 'app_lane_show')]
    public function show(Lane $lane): Response
    {

        return $this->render('lane/show.html.twig', [
            'lane' => $lane,
            'cards' => $lane->getCards(), // relation in Entity
        ]);
    }
}
