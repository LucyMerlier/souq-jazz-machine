<?php

namespace App\Repository;

use App\Entity\Instrument;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private InstrumentRepository $instrumentRepository;

    public function __construct(
        ManagerRegistry $registry,
        InstrumentRepository $instrumentRepository
    ) {
        parent::__construct($registry, User::class);
        $this->instrumentRepository = $instrumentRepository;
    }

    public function findByQuery(?string $query = '', ?Instrument $instrument = null): array
    {
        return $this->createQueryBuilder('user')
            ->join('user.instrument', 'instrument')
            ->where('user.firstname LIKE :query')
            ->orWhere('user.lastname LIKE :query')
            ->orWhere('user.pseudonym LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->andWhere('instrument.id IN (:instruments)')
            ->setParameter(
                'instruments',
                $instrument ? [$instrument->getId()] : $this->instrumentRepository->findAllIds()
            )
            ->orderBy('instrument.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllOrderByInstrument(): array
    {
        return $this->createQueryBuilder('user')
            ->join('user.instrument', 'instrument')
            ->orderBy('instrument.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
