<?php

namespace App\DataFixtures;

use App\DataFixtures\InstrumentFixtures;
use App\Entity\Offer;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $offer = new Offer();
        $offer->setInstrument($this->getReference('trombone'));
        $offer->setDescription(
            'Tu joues du trombone basse?
            Tu rêves de faire vibrer les foules en folie?
            Ne cherche plus! Le Souq\' est fait pour toi!'
        );
        $offer->setCreatedAt(new DateTimeImmutable('now'));
        $manager->persist($offer);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [InstrumentFixtures::class];
    }
}
