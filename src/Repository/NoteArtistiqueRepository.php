<?php

namespace App\Repository;

use App\Entity\NoteArtistique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoteArtistique>
 *
 * @method NoteArtistique|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteArtistique|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteArtistique[]    findAll()
 * @method NoteArtistique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteArtistiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteArtistique::class);
    }
}
