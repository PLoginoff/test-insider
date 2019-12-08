<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeekRepository")
 */
class Week
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="week")
     */
    private $matches;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamWeek", mappedBy="week", cascade={"persist"})
     */
    private $teamWeeks;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
        $this->teamWeeks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Match[]
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(Match $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches[] = $match;
            $match->setWeek($this);
        }

        return $this;
    }

    public function removeMatch(Match $match): self
    {
        if ($this->matches->contains($match)) {
            $this->matches->removeElement($match);
            // set the owning side to null (unless already changed)
            if ($match->getWeek() === $this) {
                $match->setWeek(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TeamWeek[]
     */
    public function getTeamWeeks(): Collection
    {
        return $this->teamWeeks;
    }

    public function addTeamWeek(TeamWeek $teamWeek): self
    {
        if (!$this->teamWeeks->contains($teamWeek)) {
            $this->teamWeeks[] = $teamWeek;
            $teamWeek->setWeek($this);
        }

        return $this;
    }

    public function removeTeamWeek(TeamWeek $teamWeek): self
    {
        if ($this->teamWeeks->contains($teamWeek)) {
            $this->teamWeeks->removeElement($teamWeek);
            // set the owning side to null (unless already changed)
            if ($teamWeek->getWeek() === $this) {
                $teamWeek->setWeek(null);
            }
        }

        return $this;
    }
}
