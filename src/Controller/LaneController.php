<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Lane;
use App\Entity\Board;
use App\Form\LaneType;
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
    public function new(Request $request, EntityManagerInterface $em): Response {
        
        $lane = new Lane();
        $form = $this->createForm(LaneType::class, $lane);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lane);
            $em->flush();
            return $this->redirectToRoute('app_board_index');
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
