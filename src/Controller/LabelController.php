<?php

namespace App\Controller;

use App\Entity\Lane;
use App\Entity\User;
use App\Entity\Board;
use App\Entity\Label;
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

    #[Route('/{boardId}/new/label', name: 'app_label_new')]
    public function new(int $boardId, Request $request, EntityManagerInterface $em): Response {
        $board = $em->getRepository(Board::class)->find($boardId);
        
        if (!$board) {
            throw $this->createNotFoundException('Board not found.');
        }

        $label = new Label();
        $label->setName('Neu');
        $label->setColor('#333333');
        $label->setBoard($board);

        $em->persist($label);
        $em->flush();

        return $this->redirectToRoute('app_board_show', ['id' => $boardId]);
    }
}
