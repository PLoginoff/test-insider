<?php

namespace App\Service;

use App\Entity\Team;

class MatchesService
{
    /**
     * Premier League draw:
     *  - each team plays twice with each other team
     *  - thus for 4 teams each will play 6 times
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
}
