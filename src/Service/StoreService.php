<?php

namespace App\Service;

use App\Entity\Match;
use App\Entity\Team;
use App\Entity\TeamWeek;
use App\Entity\Week;
use App\Repository\TeamRepository;
use App\Repository\WeekRepository;
use Doctrine\ORM\EntityManagerInterface;

class StoreService
{
    /** @var WeekRepository */
    protected $weekRepository;

    /** @var TeamRepository */
    protected $teamRepository;

    /** @var MatchesService */
    protected $matchesService;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(
        WeekRepository $weekRepository,
        TeamRepository $teamRepository,
        MatchesService $matchesService,
        EntityManagerInterface $entityManager
    ) {
        $this->weekRepository = $weekRepository;
        $this->teamRepository = $teamRepository;
        $this->matchesService = $matchesService;
        $this->entityManager = $entityManager;
    }

    protected function checkAndInitWeeks()
    {
        $week = $this->weekRepository->findOneBy(['number' => 1]);

        if (!$week) {
            // store weeks and matches
            $weeks = $this->matchesService->draw(4);

            $teams = $this->teamRepository->findAll();
            foreach ($weeks as $number => $w) {
                $week = new Week();
                $week->setNumber($number);
                foreach ($w as $m) {
                    $match = new Match();
                    $match->setWeek($week);
                    $match->setTeam1($teams[$m[0] - 1]);
                    $match->setTeam2($teams[$m[1] - 1]);
                    $this->entityManager->persist($match);
                }
                $this->entityManager->persist($week);
                $this->entityManager->flush();
                $this->entityManager->refresh($week); // todo reverse side doest't work?
            }
        }
    }

    /**
     * Play week and add prev results
     *
     * @param int $number
     * @return Week|null
     */
    public function playWeek($number = 1)
    {
        $this->checkAndInitWeeks(); //

        $week = $this->weekRepository->findOneBy(['number' => $number]);

        if (!$week->getTeamWeeks()->count()) {
            $week = $this->playCurrentWeek($week);

            // add prev results to this week
            if ($number > 1) {
                $prevWeek = $this->weekRepository->findOneBy(['number' => $number - 1]);
                $prevTeamWeeks = [];
                foreach ($prevWeek->getTeamWeeks() as $ptm) {
                    $prevTeamWeeks[$ptm->getTeam()->getId()] = $ptm;
                }
                foreach ($week->getTeamWeeks() as $tm) {
                    $prevTeam = $prevTeamWeeks[$tm->getTeam()->getId()];
                    $tm->setPts($prevTeam->getPts() + $tm->getPts());
                    $tm->setPlayed($prevTeam->getPlayed() + $tm->getPlayed());
                    $tm->setWon($prevTeam->getWon() + $tm->getWon());
                    $tm->setDrawn($prevTeam->getDrawn() + $tm->getDrawn());
                    $tm->setLost($prevTeam->getLost() + $tm->getLost());
                    $tm->setGd($prevTeam->getGd() + $tm->getGd());
                }
            }
            $this->entityManager->persist($week);
            $this->entityManager->flush();
            $this->entityManager->clear();
            $week = $this->weekRepository->findOneBy(['number' => $number]);
        }

        return $week;
    }

    /**
     * Emulate current week
     *
     * @param Week $team1
     * @param Team $team2
     * @return Week
     */
    public function playCurrentWeek(Week $week): Week
    {
        foreach ($week->getMatches() as $match) {
            $result = $this->matchesService->play($match->getTeam1(), $match->getTeam2());

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

            // todo copy-paste
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
