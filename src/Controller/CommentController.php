<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommentController extends AbstractController
{
    #[Route('/comments', name: 'app_comment_index')]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }
    
    #[Route('/{cardId}/comment/new', name: 'app_card_comment_new')]
    public function add(int $cardId, Request $request, EntityManagerInterface $em): Response
    {
        $card = $em->getRepository(Card::class)->find($cardId);
        $user = $em->getRepository(User::class)->find(1); // Dummy-User

        if (!$card || !$user) {
            throw $this->createNotFoundException('Card or User not found.');
        }

        $comment = new Comment();
        $comment->setContent("Hallo Welt, dies ist ein Kommentar, bitte löschen.");
        $comment->setCard($card);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('app_board_show', ['id' => $card->getLane()->getBoard()->getId()]);
    }
}
