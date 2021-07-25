<?php

namespace App\DataFixtures;

use App\Entity\Catchphrase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CatchphraseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $catchphrase = new Catchphrase();
        $catchphrase->setTitle('Bienvenue au Souq\' Jazz Machine!');
        $catchphrase->setPhrase('Le Big Band qui vous fait voyager!');
        $manager->persist($catchphrase);

        $manager->flush();
    }
}
