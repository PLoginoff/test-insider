<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamWeekRepository")
 */
class TeamWeek
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="teamWeeks")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Week", inversedBy="teamWeeks")
     */
    private $week;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gd = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $pts = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $played = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $won = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $drawn = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $lost = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

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

    public function getGd(): ?int
    {
        return $this->gd;
    }

    public function setGd(?int $gd): self
    {
        $this->gd = $gd;

        return $this;
    }

    public function getPts(): ?int
    {
        return $this->pts;
    }

    public function setPts(int $pts): self
    {
        $this->pts = $pts;

        return $this;
    }

    public function getPlayed(): ?int
    {
        return $this->played;
    }

    public function setPlayed(int $played): self
    {
        $this->played = $played;

        return $this;
    }

    public function getWon(): ?int
    {
        return $this->won;
    }

    public function setWon(int $won): self
    {
        $this->won = $won;

        return $this;
    }

    public function getDrawn(): ?int
    {
        return $this->drawn;
    }

    public function setDrawn(int $drawn): self
    {
        $this->drawn = $drawn;

        return $this;
    }

    public function getLost(): ?int
    {
        return $this->lost;
    }

    public function setLost(int $lost): self
    {
        $this->lost = $lost;

        return $this;
    }

}
