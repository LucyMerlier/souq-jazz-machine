<?php

namespace App\DataFixtures;

use App\Entity\History;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HistoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $history = new History();
        $history->setContent(
            '<p>Le <strong>Souq\' Jazz Machine</strong>
            est né en 2001 sous l’impulsion d’une dizaine de saxophonistes,
            rapidement rejoints par une rythmique (batterie, basse, piano, guitare)
            et une section de cuivres afin de satisfaire aux besoins de la scène.</p>
            <p>Après avoir assuré de nombreuses prestations dans les bars,
            soirées privées et animations diverses, dans un style avant tout jazz,
            cette bande d’amis a évolué vers la formation big band que nous connaissons aujourd’hui.
            Accompagnant maintenant un crooner et deux chanteuses de choc,
            l’ambiance est latine, jazz et funky, dans un répertoire allant de Duke Ellington à James Brown,
            en passant par John Coltrane, Ray Charles ou Stevie Wonder.</p>'
        );
        $manager->persist($history);

        $manager->flush();
    }
}
