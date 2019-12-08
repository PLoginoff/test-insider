<?php

namespace App\Service;

use App\Entity\Match;
use App\Entity\Team;
use App\Entity\TeamWeek;
use App\Entity\Week;
use App\Repository\TeamRepository;
use App\Repository\WeekRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmulateService
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

    public function getWeek($number = 1)
    {
        $week = $this->weekRepository->findOneBy(['number' => $number]);

        if (!$week->getTeamWeeks()->count()) {
            $week = $this->matchesService->playCurrentWeek($week);
            // add prev results to this week
            if ($number > 1) {
                $prevWeek = $this->weekRepository->findOneBy(['number' => $number - 1]);
                $prevTeamWeeks = [];
                foreach ($prevWeek->getTeamWeeks() as $ptm) {
                    $prevTeamWeeks[$ptm->getTeam()->getId()] = $ptm;
                }
                foreach ($week->getTeamWeeks() as $tm) {
                    $tm->setPts($prevTeamWeeks[$tm->getTeam()->getId()]->getPts() + $tm->getPts());
                    $tm->setPlayed($prevTeamWeeks[$tm->getTeam()->getId()]->getPlayed() + $tm->getPlayed());
                    $tm->setWon($prevTeamWeeks[$tm->getTeam()->getId()]->getWon() + $tm->getWon());
                    $tm->setDrawn($prevTeamWeeks[$tm->getTeam()->getId()]->getDrawn() + $tm->getDrawn());
                    $tm->setLost($prevTeamWeeks[$tm->getTeam()->getId()]->getLost() + $tm->getLost());
                    $tm->setGd($prevTeamWeeks[$tm->getTeam()->getId()]->getGd() + $tm->getGd());
                }
            }
            $this->entityManager->persist($week);
            $this->entityManager->flush();
        }

        return $week;
    }

    public function init()
    {
        $week = $this->weekRepository->findOneBy(['number' => 1]);

        // store weeks and matches
        if (!$week) {
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
            }
            $this->entityManager->flush();
        }
    }
}
