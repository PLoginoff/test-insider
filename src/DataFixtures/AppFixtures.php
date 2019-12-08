<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $team1 = (new Team())->setName('Chelsea')   ->setLuck(0.3)->setStrength(0.9);
        $team2 = (new Team())->setName('Arsenal')   ->setLuck(0.4)->setStrength(0.9);
        $team3 = (new Team())->setName('Manchester')->setLuck(0.5)->setStrength(0.9);
        $team4 = (new Team())->setName('Liverpool') ->setLuck(0.6)->setStrength(0.9);

        $manager->persist($team1);
        $manager->persist($team2);
        $manager->persist($team3);
        $manager->persist($team4);

        $manager->flush();
    }
}
