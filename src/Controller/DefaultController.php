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
     * @Route("/week/{weekNumber}", name="week")
     */
    public function index(
        EmulateService $emulateService,
        PredictionService $predictionService,
        int $weekNumber = 1
    ) {
        $weekNumber  = $weekNumber > 6 || $weekNumber < 1 ? 1 : $weekNumber; // fixme
        $week        = $emulateService->playWeek($weekNumber);
        $teamweeks   = $week->getTeamWeeks()->toArray();
        $predictions = $predictionService->prediction($week);
        usort($teamweeks, function (TeamWeek $a, TeamWeek $b) {
            return $a->getPts() === $b->getPts() ? $a->getGd() < $b->getGd() : $a->getPts() < $b->getPts();
        });
        return $this->render('default/index.html.twig', [
            'teamweeks'     => $teamweeks,
            'week'          => $week,
            'predictions'   => $predictions,
        ]);
    }
}
