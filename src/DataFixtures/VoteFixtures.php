<?php

namespace App\DataFixtures;

use App\DataFixtures\ConcertFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Availability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function load(ObjectManager $manager)
    {
        $vote = (new Availability())
            ->setVoter($this->getReference('vero'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('vero'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('jess'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('jess'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(false)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('marc'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('marc'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('lolo'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('lolo'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('wiwi'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('wiwi'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('coco'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('coco'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('jc'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('micha'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(false)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('micha'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(false)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('fifi'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('vinz'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(false)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('vinz'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('nono'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('nono'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('em'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('ad'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('ad'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('vero'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('vero'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('geo'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('geo'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('lu'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('marco'))
            ->setConcert($this->getReference('concert_4'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $vote = (new Availability())
            ->setVoter($this->getReference('marco'))
            ->setConcert($this->getReference('concert_5'))
            ->setVote(true)
        ;

        $manager->persist($vote);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, ConcertFixtures::class];
    }
}
