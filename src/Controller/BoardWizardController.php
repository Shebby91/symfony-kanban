<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Board;
use App\Form\LaneType;
use App\Form\BoardType;
use App\Form\LabelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoardWizardController extends AbstractController
{
    #[Route('/wizard/board/step/{step}', name: 'app_board_wizard', requirements: ['step' => '\d+'])]
    public function wizard(Request $request, SessionInterface $session, EntityManagerInterface $em, int $step = 1) {
       
        $boardData = $session->get('board_wizard', []);

        switch ($step) {
            case 1:
                $board = new Board();
                $form = $this->createForm(BoardType::class, $board);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $session->set('board_wizard', ['board' => $board]);
                    return $this->redirectToRoute('app_board_wizard', ['step' => 2]);
                }

                return $this->render('wizard/step1.html.twig', [
                    'form' => $form->createView()
                ]);

            case 2:
                $lanes = $boardData['lanes'] ?? [];
                $labels = $boardData['labels'] ?? [];

                $laneForm = $this->createForm(LaneType::class, null, ['available_boards' => null]);
                $laneForm->handleRequest($request);
                if ($laneForm->isSubmitted() && $laneForm->isValid()) {
                    $lanes[] = $laneForm->getData();
                    $boardData['lanes'] = $lanes;
                    $session->set('board_wizard', $boardData);
                    return $this->redirectToRoute('app_board_wizard', ['step' => 2]);
                }

                $labelForm = $this->createForm(LabelType::class, null, ['available_boards' => null]);
                $labelForm->handleRequest($request);
                if ($labelForm->isSubmitted() && $labelForm->isValid()) {
                    $labels[] = $labelForm->getData();
                    $boardData['labels'] = $labels;
                    $session->set('board_wizard', $boardData);
                    return $this->redirectToRoute('app_board_wizard', ['step' => 2]);
                }

                return $this->render('wizard/step2.html.twig', [
                    'lane_form' => $laneForm->createView(),
                    'label_form' => $labelForm->createView(),
                    'lanes' => $lanes,
                    'labels' => $labels
                ]);

            case 3:
                // Save board + lanes
                $data = $session->get('board_wizard');
                $board = $data['board'];
                $board->setCreatedAt(new \DateTimeImmutable());
                $board->setOwner($em->getRepository(User::class)->find($board->getOwner()->getId()));
                $em->persist($board);
                foreach ($data['lanes'] as $lane) {
                    $lane->setBoard($board);
                    $em->persist($lane);
                }

                // Labels zuweisen und speichern
                foreach ($data['labels'] as $label) {
                    $label->setBoard($board);
                    $em->persist($label);
                }

                $em->flush();
                $session->remove('board_wizard');

                return $this->render('wizard/success.html.twig', [
                    'board' => $board
                ]);
        }

        return $this->redirectToRoute('app_board_wizard', ['step' => 1]);
    }
}