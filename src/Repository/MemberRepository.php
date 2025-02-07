<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    /**
     * Rehashes the user's password automatically over time.
     *
     * @param PasswordAuthenticatedUserInterface $member
     * @param string $newHashedPassword
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $member, string $newHashedPassword): void
    {
        if (!$member instanceof Member) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $member::class));
        }

        $member->setHash($newHashedPassword);
        $this->_em->persist($member);
        $this->_em->flush();
    }
}
