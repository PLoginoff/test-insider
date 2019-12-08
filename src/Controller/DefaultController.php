<?php

namespace App\Controller;

use App\Entity\TeamWeek;
use App\Repository\WeekRepository;
use App\Service\EmulateService;
use App\Service\PredictionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @Route("/week/{week}", name="week")
     */
    public function index(
        WeekRepository $weekRepository,
        EmulateService $emulateService,
        PredictionService $predictionService,
        int $week = 1
    ) {
        $emulateService->init();

        if ($week > 6 || $week < 1) {
            $week = 1;
        }

        $week       = $emulateService->getWeek($week);
        $teamweeks  = $week->getTeamWeeks()->toArray();
        usort($teamweeks, function (TeamWeek $a, TeamWeek $b) {
            return $a->getPts() === $b->getPts() ? $a->getGd() < $b->getGd() : $a->getPts() < $b->getPts();
        });
        return $this->render('default/index.html.twig', [
            'teamweeks' => $teamweeks,
            'week'      => $week,
            'predictions' => $predictionService->prediction($week),
        ]);
    }
}