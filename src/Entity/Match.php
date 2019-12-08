<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * a match between team1 and team2
 *
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 */
class Match
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team1;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="matches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Week", inversedBy="matches")
     */
    private $week;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goals1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goals2;

    public function __construct()
    {
        $this->team1 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam1(): ?Team
    {
        return $this->team1;
    }

    public function setTeam1(?Team $team1): self
    {
        $this->team1 = $team1;

        return $this;
    }

    public function getTeam2(): ?Team
    {
        return $this->team2;
    }

    public function setTeam2(?Team $team2): self
    {
        $this->team2 = $team2;

        return $this;
    }

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getGoals1(): ?int
    {
        return $this->goals1;
    }

    public function setGoals1(int $goals1): self
    {
        $this->goals1 = $goals1;

        return $this;
    }

    public function getGoals2(): ?int
    {
        return $this->goals2;
    }

    public function setGoals2(int $goals2): self
    {
        $this->goals2 = $goals2;

        return $this;
    }
}
