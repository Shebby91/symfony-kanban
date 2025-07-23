<?php

namespace App\Controller;

use App\Entity\Lane;
use App\Entity\User;
use App\Entity\Board;
use App\Entity\Label;
use App\Form\LabelType;
use App\Entity\CardLabel;
use App\Form\CardLabelType;
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
    /*#[Route('/new/label/{id}', name: 'app_label_new_for_board', methods: ['GET', 'POST'])]*/
    public function new(Request $request, EntityManagerInterface $em): Response {
                
        $label = new Label();
        $form = $this->createForm(LabelType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($label);
            $em->flush();

            return $this->redirectToRoute('app_label_index');
        }

        return $this->render('label/new.html.twig', [
            'form' => $form,
        ]);
    }
}
