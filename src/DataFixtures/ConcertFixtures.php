<?php

namespace App\DataFixtures;

use App\Entity\Concert;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConcertFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $concert = new Concert();
        $concert->setDate((new DateTime('2022-04-16'))->setTime(20, 0));
        $concert->setIsValidated(true);
        $concert->setCity('Azay-le-Rideau');
        $concert->setDescription(
            'Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Doloribus nemo dolor consequuntur voluptatum est ut alias, officia pariatur natus!
            Magni omnis natus aperiam aliquam excepturi facilis cum dolorem non fuga, similique accusamus dicta iure!'
        );
        $manager->persist($concert);
        $this->addReference('concert_1', $concert);

        $concert = new Concert();
        $concert->setDate((new DateTime('2022-06-25'))->setTime(20, 30));
        $concert->setIsValidated(true);
        $concert->setCity('Bréhémont');
        $concert->setDescription(
            'Sit ea ab provident, fuga enim impedit ipsa, eius suscipit atque tempora facilis qui dolore labore.
            Magni omnis natus aperiam aliquam excepturi facilis cum dolorem non fuga, similique accusamus dicta iure!'
        );
        $manager->persist($concert);
        $this->addReference('concert_2', $concert);

        $concert = new Concert();
        $concert->setDate((new DateTime('2021-06-18'))->setTime(8, 0));
        $concert->setIsValidated(true);
        $concert->setCity('Aix-en-Provence');
        $concert->setDescription(
            'Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Doloribus nemo dolor consequuntur voluptatum est ut alias, officia pariatur natus!
            Sit ea ab provident, fuga enim impedit ipsa, eius suscipit atque tempora facilis qui dolore labore.
            Magni omnis natus aperiam aliquam excepturi facilis cum dolorem non fuga, similique accusamus dicta iure!'
        );
        $manager->persist($concert);
        $this->addReference('concert_3', $concert);

        $concert = new Concert();
        $concert->setDate((new DateTime('2022-12-07'))->setTime(19, 0));
        $concert->setIsValidated(false);
        $concert->setCity('Cinq-Mars-la-Pile');
        $concert->setDescription(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.
            Suspendisse lectus tortor, dignissim sit amet.'
        );
        $manager->persist($concert);
        $this->addReference('concert_4', $concert);

        $manager->flush();
    }
}
