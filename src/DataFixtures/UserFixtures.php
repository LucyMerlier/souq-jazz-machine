<?php

namespace App\DataFixtures;

use App\DataFixtures\InstrumentFixtures;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function load(ObjectManager $manager)
    {
        // VOICES

        $vero = new User();
        $vero
            ->setFirstname('Véronique')
            ->setLastname('Bono')
            ->setEmail('vero@bono.jazz')
            ->setPhone('+33 6 87 65 21 54')
            ->setInstrument($this->getReference('voice'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $vero,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($vero);
        $this->addReference('vero', $vero);

        $jess = new User();
        $jess
            ->setFirstname('Jessica')
            ->setLastname('Fenton')
            ->setEmail('jessica@fenton.jazz')
            ->setPhone('+33 6 87 65 21 54')
            ->setInstrument($this->getReference('voice'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $jess,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($jess);
        $this->addReference('jess', $jess);

        $marc = new User();
        $marc
            ->setFirstname('Marc')
            ->setLastname('Fillatre')
            ->setEmail('marc@fil.jazz')
            ->setPhone('+33 6 87 65 21 54')
            ->setInstrument($this->getReference('voice'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $marc,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($marc);
        $this->addReference('marc', $marc);

        // SAXOPHONES

        $lolo = new User();
        $lolo
            ->setFirstname('Laurent')
            ->setLastname('Souquières')
            ->setEmail('laurent@souquieres.jazz')
            ->setPseudonym('Lolo')
            ->setCatchphrase('Qui c\'est les meilleurs? Évidemment, c\'est les verts!')
            ->setPhone('+33 6 12 45 78 32')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_BAND_LEADER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $lolo,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($lolo);
        $this->addReference('lolo', $lolo);

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
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($wiwi);
        $this->addReference('wiwi', $wiwi);

        $coco = new User();
        $coco
            ->setFirstname('Coralie')
            ->setLastname('Durand')
            ->setEmail('coralie@durand.jazz')
            ->setPseudonym('Coco')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $coco,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($coco);
        $this->addReference('coco', $coco);

        $jc = new User();
        $jc
            ->setFirstname('Jean-Christophe')
            ->setLastname('Le Dantec')
            ->setEmail('jc@ledantec.jazz')
            ->setPseudonym('Tonton')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $jc,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($jc);
        $this->addReference('jc', $jc);

        $pat = new User();
        $pat
            ->setFirstname('Patricia')
            ->setLastname('Mailhebiau')
            ->setEmail('pat@mail.jazz')
            ->setPseudonym('Patoche')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('sax'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $pat,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($pat);
        $this->addReference('pat', $pat);

        // TRUMPETS

        $jp = new User();
        $jp
            ->setFirstname('Jean-Pascal')
            ->setLastname('Edeline')
            ->setEmail('jp@ed.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $jp,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($jp);
        $this->addReference('jp', $jp);

        $maelys = new User();
        $maelys
            ->setFirstname('Maelys')
            ->setLastname('Fontaine')
            ->setEmail('maelys@fontaine.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $maelys,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($maelys);
        $this->addReference('maelys', $maelys);

        $micha = new User();
        $micha
            ->setFirstname('Michael')
            ->setLastname('Kuakuvi')
            ->setEmail('mika@kua.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setPseudonym('Mikaka')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $micha,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($micha);
        $this->addReference('micha', $micha);

        $fifi = new User();
        $fifi
            ->setFirstname('Philippe')
            ->setLastname('Lecreux')
            ->setEmail('fifi@fifi.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setPseudonym('Fifi')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $fifi,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($fifi);
        $this->addReference('fifi', $fifi);

        $vinz = new User();
        $vinz
            ->setFirstname('Vincent')
            ->setLastname('Roussel')
            ->setEmail('vinz@roussel.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setPseudonym('Vinz')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $vinz,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($vinz);
        $this->addReference('vinz', $vinz);

        $nono = new User();
        $nono
            ->setFirstname('Arnaud')
            ->setLastname('Zephirin')
            ->setEmail('no@no.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setPseudonym('Nono')
            ->setInstrument($this->getReference('trumpet'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $nono,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($nono);
        $this->addReference('nono', $nono);

        // TROMBONES

        $raph = new User();
        $raph
            ->setFirstname('Raphaël')
            ->setLastname('Boutonnet')
            ->setEmail('raphael@boutonnet.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('trombone'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $raph,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($raph);
        $this->addReference('raph', $raph);

        $emilien = new User();
        $emilien
            ->setFirstname('Émilien')
            ->setLastname('Regnault')
            ->setEmail('emilien@regnault.jazz')
            ->setPhone('+33 6 23 56 89 21')
            ->setInstrument($this->getReference('trombone'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $emilien,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($emilien);
        $this->addReference('em', $emilien);

        // PIANO

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
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($adrien);
        $this->addReference('ad', $adrien);

        // GUITAR

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
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($geo);
        $this->addReference('geo', $geo);

        // BASS

        $lucy = new User();
        $lucy
            ->setFirstname('Lucy')
            ->setLastname('Merlier')
            ->setEmail('lucy@merlier.jazz')
            ->setCatchphrase('Call me daddy')
            ->setPseudonym('Lu')
            ->setPhone('+33 6 96 52 74 85')
            ->setInstrument($this->getReference('bass'))
            ->setRoles(['ROLE_SUPERADMIN'])
            ->setPassword($this->passwordHasher->hashPassword(
                $lucy,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($lucy);
        $this->addReference('lu', $lucy);

        $marco = new User();
        $marco
            ->setFirstname('Marco')
            ->setLastname('Chaveau')
            ->setEmail('marco@chaveau.jazz')
            ->setPhone('+33 6 96 52 74 85')
            ->setInstrument($this->getReference('bass'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $marco,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($marco);
        $this->addReference('marco', $marco);

        // DRUMS

        $amaury = new User();
        $amaury
            ->setFirstname('Amaury')
            ->setLastname('Le Dantec')
            ->setEmail('amaury@ledantec.jazz')
            ->setCatchphrase('poom ts tss ka ts tss poom ts tss ka ts tss')
            ->setPhone('+33 6 63 52 74 96')
            ->setInstrument($this->getReference('drums'))
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordHasher->hashPassword(
                $amaury,
                'machine'
            ))
            ->setIsVerified(true)
            ->setUpdatedAt(new DateTime('now'))
        ;
        $manager->persist($amaury);
        $this->addReference('amo', $amaury);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [InstrumentFixtures::class];
    }
}
