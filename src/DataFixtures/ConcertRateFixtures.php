<?php

namespace App\DataFixtures;

use App\DataFixtures\ConcertFixtures;
use App\Entity\ConcertRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConcertRateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $rate = new ConcertRate();
        $rate->setConcert($this->getReference('concert_1'));
        $rate->setCategory('Adulte');
        $rate->setPrice(8);
        $manager->persist($rate);

        $rate = new ConcertRate();
        $rate->setConcert($this->getReference('concert_1'));
        $rate->setCategory('Enfant (-12 ans)');
        $rate->setPrice(5);
        $manager->persist($rate);

        $rate = new ConcertRate();
        $rate->setConcert($this->getReference('concert_2'));
        $rate->setCategory('Adulte');
        $rate->setPrice(4);
        $manager->persist($rate);

        $rate = new ConcertRate();
        $rate->setConcert($this->getReference('concert_2'));
        $rate->setCategory('Enfant (-12 ans)');
        $rate->setPrice(0);
        $manager->persist($rate);

        $rate = new ConcertRate();
        $rate->setConcert($this->getReference('concert_3'));
        $rate->setPrice(0);
        $manager->persist($rate);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ConcertFixtures::class];
    }
}
