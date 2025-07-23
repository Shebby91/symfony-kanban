<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\Board;
use App\Form\BoardType;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BoardController extends AbstractController
{
    #[Route('/', name: 'app_board_index')]
    public function index(BoardRepository $boardRepository): Response
    {
        $boards = $boardRepository->findAll();
    
        return $this->render('board/index.html.twig', [
            'boards' => $boards,
        ]);
    }

    #[Route('/board/new', name: 'app_board_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $board->setCreatedAt(new \DateTimeImmutable());
            $em->persist($board);
            $em->flush();

            return $this->redirectToRoute('app_board_index');
        }

        return $this->render('board/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/board/{id}', name: 'app_board_show')]
    public function show(Board $board): Response
    {

        return $this->render('board/show.html.twig', [
            'board' => $board,
            'lanes' => $board->getLanes(), // relation in Entity
        ]);
    }

    #[Route('/board/{id}/show', name: 'app_board_show_board')]
    public function showBoard(Board $board, EntityManagerInterface $em): Response
    {
        //dd($board);
        return $this->render('board/board.html.twig', [
            'board' => $board,
        ]);
    }
}
