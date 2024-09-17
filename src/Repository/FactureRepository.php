<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\Dsn;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    private $mailer;
    public function __construct(ManagerRegistry $registry, MailerInterface $mailer)
    {
        parent::__construct($registry, Facture::class);
        $this->mailer = $mailer;
    }

//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
// Tri croissant par date de paiement
public function findAllOrderByDatePaiementAsc()
{
    $qb = $this->createQueryBuilder('f')
        ->orderBy('f.datePaiement', 'ASC');

    return $qb->getQuery()->getResult();
}

// Tri décroissant par date de paiement
public function findAllOrderByDatePaiementDesc()
{
    $qb = $this->createQueryBuilder('f')
        ->orderBy('f.datePaiement', 'DESC');

    return $qb->getQuery()->getResult();
}
public function sendEmail(MailerInterface $mailer, $emailDestinataire)
{
    // Créer l'e-mail
    $email = (new Email())
        ->from('from@example.com')
        ->to($emailDestinataire)
        ->subject('Hello')
        ->text('Hello, this is a test email.');

    // Envoyer l'e-mail
    $mailer->send($email);
}

}


