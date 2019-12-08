<?php

namespace App\Controller;

use App\Entity\TeamWeek;
use App\Repository\TeamWeekRepository;
use App\Repository\WeekRepository;
use App\Service\StoreService;
use App\Service\PredictionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @Route("/week/{weekNumber}", name="week")
     *
     * @param StoreService $emulateService
     * @param PredictionService $predictionService
     * @param int $weekNumber
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        StoreService $emulateService,
        PredictionService $predictionService,
        TeamWeekRepository $teamWeekRepository,
        int $weekNumber = 1
    ) {
        $weekNumber  = $weekNumber > 6 || $weekNumber < 1 ? 1 : $weekNumber; // fixme

        $week        = $emulateService->playWeek($weekNumber);
        $teamweeks   = $teamWeekRepository->getForWeek($week);
        $predictions = $predictionService->prediction($week);

        return $this->render('default/index.html.twig', [
            'teamweeks'     => $teamweeks,
            'week'          => $week,
            'predictions'   => $predictions,
        ]);
    }
}
