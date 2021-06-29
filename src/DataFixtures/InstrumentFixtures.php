<?php

namespace App\DataFixtures;

use App\Entity\Instrument;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstrumentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $voice = new Instrument();
        $voice->setName('Chant');
        $manager->persist($voice);
        $this->addReference('voice', $voice);

        $sax = new Instrument();
        $sax->setName('Saxophone');
        $manager->persist($sax);
        $this->addReference('sax', $sax);

        $trumpet = new Instrument();
        $trumpet->setName('Trompette');
        $manager->persist($trumpet);
        $this->addReference('trumpet', $trumpet);

        $trombone = new Instrument();
        $trombone->setName('Trombone');
        $manager->persist($trombone);
        $this->addReference('trombone', $trombone);


        $piano = new Instrument();
        $piano->setName('Piano');
        $manager->persist($piano);
        $this->addReference('piano', $piano);

        $guitar = new Instrument();
        $guitar->setName('Guitare');
        $manager->persist($guitar);
        $this->addReference('guitar', $guitar);

        $bass = new Instrument();
        $bass->setName('Basse');
        $manager->persist($bass);
        $this->addReference('bass', $bass);

        $drums = new Instrument();
        $drums->setName('Batterie');
        $manager->persist($drums);
        $this->addReference('drums', $drums);

        $manager->flush();
    }
}
