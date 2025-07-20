<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
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
    
    

    #[Route('/comment/new', name: 'app_card_comment_new')]
    public function add( Request $request, EntityManagerInterface $em): Response
    { 
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($em->getRepository(User::class)->find(1));
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_board_index');
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form,
        ]);
    }
}
