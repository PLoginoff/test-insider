<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\TeamWeek;
use App\Entity\Week;

class MatchesService
{
    /**
     * Premier League draw:
     *  - each team plays twice with each other team
     *  - thus for four teams each will play six times
     *
     * @param $teams
     * @param $perWeak
     *
     * @return array [{weak} => [[1,2], [3,4]]
     */
    public function draw($teams): array
    {
        $allGames = [];
        for ($i = 1; $i <= $teams; $i++) {
            for ($y = 1; $y <= $teams; $y++) {
                if ($y !== $i) {
                    $allGames[] = [$i, $y];
                }
            }
        }

        shuffle($allGames);

        $weeks = []; // result
        $weekTeam = []; // for check

        $z = 1;
        while ($game = array_shift($allGames)) {
            if (!isset($weekTeam[$z]) || (!in_array($game[0], $weekTeam[$z]) && !in_array($game[1], $weekTeam[$z]))) {
                $weeks[$z][] = $game;
                $weekTeam[$z][] = $game[0];
                $weekTeam[$z][] = $game[1];
            } else {
                array_push($allGames, $game);
            }
            if (count($weekTeam[$z]) === $teams) {
                $z++;
            }
        }
        return $weeks;
    }

    /**
     * the most controversial point to be finalized:
     *    the first version is only around good luck :-)
     *
     * @param Team $team1
     * @param Team $team2
     * @return array [score1, score2]
     */
    public function play(Team $team1, Team $team2): array
    {
        $team1->getLuck();
        $team2->getStrength();

        $totalGoals  = mt_rand(0, 6);
        $first       = ceil($totalGoals / 2);
        $second      = $totalGoals - $first;

        if ($team1->getLuck() > $team2->getLuck()) {
            return [$first, $second];
        } else {
            return [$second, $first];
        }
    }

    /**
     * Emulate week.
     *
     * @param Week $team1
     * @param Team $team2
     * @return array [score1, score2]
     */
    public function playCurrentWeek(Week $week): Week
    {
        $matches = $week->getMatches();

        // play!
        foreach ($matches as $match) {
            $result = $this->play($match->getTeam1(), $match->getTeam2());

            $match->setGoals1($result[0]);
            $match->setGoals2($result[1]);

            $teamWeek1 = new TeamWeek();
            $teamWeek1->setTeam($match->getTeam1());
            $teamWeek1->setWeek($week);
            $teamWeek1->setGD(($result[0] - $result[1]));
            if ($result[0] > $result[1]) {
                $teamWeek1->setPTS(3); // won
                $teamWeek1->setWon(1);
            } elseif ($result[0] == $result[1]) {
                $teamWeek1->setPTS(1); //
                $teamWeek1->setDrawn(1);
            } else {
                $teamWeek1->setLost(1);
            }

            $week->addTeamWeek($teamWeek1);

            $teamWeek2 = new TeamWeek();
            $teamWeek2->setTeam($match->getTeam2());
            $teamWeek2->setWeek($week);
            $teamWeek2->setGD(($result[1] - $result[0]));
            if ($result[0] < $result[1]) {
                $teamWeek2->setPTS(3); // won
                $teamWeek2->setWon(1);
            } elseif ($result[0] == $result[1]) {
                $teamWeek2->setPTS(1); //
                $teamWeek2->setDrawn(1);
            } else {
                $teamWeek2->setLost(1);
            }

            $week->addTeamWeek($teamWeek2);
        }

        return $week;
    }
}
