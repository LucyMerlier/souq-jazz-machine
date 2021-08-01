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
        $voice->setCategory('other');
        $manager->persist($voice);
        $this->addReference('voice', $voice);

        $sax = new Instrument();
        $sax->setName('Saxophone');
        $sax->setCategory('wind');
        $manager->persist($sax);
        $this->addReference('sax', $sax);

        $trumpet = new Instrument();
        $trumpet->setName('Trompette');
        $trumpet->setCategory('wind');
        $manager->persist($trumpet);
        $this->addReference('trumpet', $trumpet);

        $trombone = new Instrument();
        $trombone->setName('Trombone');
        $trombone->setCategory('wind');
        $manager->persist($trombone);
        $this->addReference('trombone', $trombone);


        $piano = new Instrument();
        $piano->setName('Piano');
        $piano->setCategory('rhythm');
        $manager->persist($piano);
        $this->addReference('piano', $piano);

        $guitar = new Instrument();
        $guitar->setName('Guitare');
        $guitar->setCategory('rhythm');
        $manager->persist($guitar);
        $this->addReference('guitar', $guitar);

        $bass = new Instrument();
        $bass->setName('Basse');
        $bass->setCategory('rhythm');
        $manager->persist($bass);
        $this->addReference('bass', $bass);

        $drums = new Instrument();
        $drums->setName('Batterie');
        $drums->setCategory('rhythm');
        $manager->persist($drums);
        $this->addReference('drums', $drums);

        $manager->flush();
    }
}
