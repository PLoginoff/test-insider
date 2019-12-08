<?php

namespace App\Entity;

class Prediction
{
    /** @var Team */
    private $team;

    /** @var float */
    private $prediction;

    public function setTeam(Team $team) : self
    {
        $this->team = $team;
        return $this;
    }

    public function getTeam() : Team
    {
        return $this->team;
    }

    public function setValue(float $prediction) : self
    {
        $this->prediction = $prediction;
        return $this;
    }

    public function getValue() : float
    {
        return $this->prediction;
    }
}
