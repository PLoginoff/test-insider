<?php

namespace App\Tests;

use App\Entity\Match;
use App\Entity\Team;
use App\Entity\Week;
use App\Service\MatchesService;
use PHPUnit\Framework\TestCase;

class MatchesServiceTest extends TestCase
{
    /** @var MatchesService */
    protected $service;

    public function testSomething()
    {
        $weeks = $this->service->draw(4);
        foreach ($weeks as $w) {
            $count = count(array_unique(array_merge($w[0], $w[1])));
            $this->assertEquals(4, $count); // no duplicates
        }
        $this->assertEquals(6, count($weeks)); // 6 weeks
    }

    public function testPlay()
    {
        $team1 = new Team();
        $team1->setLuck(0.7);
        $team2 = new Team();
        $team2->setLuck(0.65);

        $r = $this->service->play($team1, $team2);
        $this->assertTrue($r[0] >= $r[1]);

        $team2->setLuck(0.75);
        $r = $this->service->play($team1, $team2);
        $this->assertTrue($r[0] <= $r[1]);
    }

    public function testPlayWeek()
    {
        $team1 = (new Team())->setLuck(0.4);
        $team2 = (new Team())->setLuck(0.5);
        $team3 = (new Team())->setLuck(0.6);
        $team4 = (new Team())->setLuck(0.7);

        $match1 = (new Match())->setTeam1($team1)->setTeam2($team2);
        $match2 = (new Match())->setTeam1($team3)->setTeam2($team4);

        $week   = (new Week())->addMatch($match1)->addMatch($match2);

        $week = $this->service->playCurrentWeek($week);

        $this->assertNotEmpty($week);
    }

    public function setUp()
    {
        $this->service = new MatchesService();
    }
}
