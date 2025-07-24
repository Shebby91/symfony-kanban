<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CardRepository;
use App\Repository\CommentRepository;
use App\Repository\LaneRepository;
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
    
    

    #[Route('/comment/new', name: 'app_card_comment_new')]
    #[Route('/comment/{id}/new', name: 'app_card_comment_new_card_comment')]
    public function add( Request $request, EntityManagerInterface $em, ?int $id, CardRepository $cardRepository): Response
    { 
        $comment = new Comment();
        $availableCards = null;  

        if ($id) {
            $card = $cardRepository->find($id);
            if ($card) {
                $comment->setCard($card);
                $availableCards = [$card];
            }
        } else {
            $availableCards = $cardRepository->findAll();
        }
        
        $form = $this->createForm(CommentType::class, $comment, ['available_cards' => $availableCards,]);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($em->getRepository(User::class)->find(1));
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_board_show_board', ['id' => $card->getLane()->getBoard()->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form,
        ]);
    }
}
