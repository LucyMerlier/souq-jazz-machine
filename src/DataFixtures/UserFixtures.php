<?php

namespace App\DataFixtures;

use App\DataFixtures\InstrumentFixtures;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $lolo = new User();
        $lolo
            ->setFirstname('Laurent')
            ->setLastname('Souquières')
            ->setEmail('laurent@souquières.jazz')
            ->setPseudonym('Lolo')
            ->setCatchphrase('Qui c\'est les meilleurs? Évidemment, c\'est les verts!')
            ->setPhone('+33 6 12 45 78 32')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_BAND_LEADER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $lolo,
                'machine'
            ))
        ;
        $manager->persist($lolo);

        $wiwi = new User();
        $wiwi
            ->setFirstname('William')
            ->setLastname('Brocherioux')
            ->setEmail('william@brocherioux.jazz')
            ->setPseudonym('Wiwi')
            ->setCatchphrase('J\'aime quand ça claque')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $wiwi,
                'machine'
            ))
        ;
        $manager->persist($wiwi);

        $geo = new User();
        $geo
            ->setFirstname('Georges')
            ->setLastname('Peltier')
            ->setEmail('georges@peltier.jazz')
            ->setPhone('+33 6 87 65 21 54')
            ->setInstrument($this->getReference('guitar'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $geo,
                'machine'
            ))
        ;
        $manager->persist($geo);

        $lucy = new User();
        $lucy
            ->setFirstname('Lucy')
            ->setLastname('Merlier')
            ->setEmail('lucy@merlier.jazz')
            ->setPseudonym('Lu')
            ->setPhone('+33 6 96 52 74 85')
            ->setInstrument($this->getReference('bass'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $lucy,
                'machine'
            ))
        ;
        $manager->persist($lucy);

        $adrien = new User();
        $adrien
            ->setFirstname('Adrien')
            ->setLastname('Croteau')
            ->setEmail('adrien@croteau.jazz')
            ->setCatchphrase('Il jouait du piano debout...')
            ->setPhone('+33 6 63 52 74 96')
            ->setInstrument($this->getReference('piano'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $adrien,
                'machine'
            ))
        ;
        $manager->persist($adrien);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [InstrumentFixtures::class];
    }
}
