<?php

namespace App\Service;

use App\Entity\Prediction;
use App\Entity\Week;

class PredictionService
{
    /**
     * Prediction for Week
     *
     * First version:
     *  - only pts
     *
     * @param Week $week
     * @return Prediction[]
     */
    public function prediction(Week $week): array
    {
        $allPts = 0;
        foreach ($week->getTeamWeeks() as $tw) {
            $allPts += $tw->getPts();
        }

        $result = [];
        foreach ($week->getTeamWeeks() as $tw) {
            $prediction = new Prediction();
            $prediction->setTeam($tw->getTeam());
            $prediction->setValue($tw->getPts() / $allPts);
            $result[] = $prediction;
        }

        usort($result, function (Prediction $a, Prediction $b) {
            return $a->getValue() < $b->getValue();
        });

        return $result;
    }
}
