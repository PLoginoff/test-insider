<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $luck;

    /**
     * @ORM\Column(type="float")
     */
    private $strength;

    /**
     * ORM\OneToMany(targetEntity="App\Entity\Match", orphanRemoval=true)
     */
    private $matches;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamWeek", mappedBy="team")
     */
    private $teamWeeks;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
        $this->teamWeaks = new ArrayCollection();
        $this->teamWeeks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLuck(): ?float
    {
        return $this->luck;
    }

    public function setLuck(float $luck): self
    {
        $this->luck = $luck;

        return $this;
    }

    public function getStrength(): ?float
    {
        return $this->strength;
    }

    public function setStrength(float $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * @return Collection|Match[]
     */
    public function getMatches(): Collection
    {
        return $this->matches;
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
            $teamWeek->setTeam($this);
        }

        return $this;
    }

    public function removeTeamWeek(TeamWeek $teamWeek): self
    {
        if ($this->teamWeeks->contains($teamWeek)) {
            $this->teamWeeks->removeElement($teamWeek);
            // set the owning side to null (unless already changed)
            if ($teamWeek->getTeam() === $this) {
                $teamWeek->setTeam(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
