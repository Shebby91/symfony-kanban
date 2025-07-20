<?php

namespace App\Controller;

use App\Entity\Lane;
use App\Entity\Card;
use App\Entity\Board;
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

    #[Route('/{boardId}/new/lane', name: 'app_lane_new')]
    public function new( int $boardId, Request $request, EntityManagerInterface $em): Response {
        $board = $em->getRepository(Board::class)->find($boardId);

        if (!$board) {
            throw $this->createNotFoundException('Board not found.');
        }

        //TODO IF board id=null, show form with board drop down else without

        $title = $request->request->get('title', 'Later');

        $lane = new Lane();
        $lane->setTitle($title);
        $lane->setBoard($board);
        $lane->setPosition(0); // Position später sortierbar

        $em->persist($lane);
        $em->flush();

        return $this->redirectToRoute('app_board_show', ['id' => $boardId]);
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
