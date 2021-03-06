<?php

namespace Club\BookingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BookingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingRepository extends EntityRepository
{
    public function getAllBetween(\DateTime $start, \DateTime $end, \Club\BookingBundle\Entity\Field $field)
    {
        return $this->_em->createQueryBuilder()
            ->select('b')
            ->from('ClubBookingBundle:Booking','b')
            ->where('(b.status >= :status) AND ((b.first_date <= :start AND b.end_date >= :end) OR (b.first_date <= :start AND b.end_date <= :end AND b.end_date >= :start) OR (b.first_date >= :start AND b.end_date >= :end AND b.first_date < :end) OR b.first_date >= :start AND b.end_date <= :end AND b.end_date >= :start)')
            ->orderBy('b.first_date')
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::CONFIRMED)
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->leftJoin('b.field', 'f')
            ->andWhere('f.id = :field')
            ->setParameter('field', $field->getId())
            ->getQuery()
            ->getResult();
    }

    public function getAllByLocationDate(\Club\UserBundle\Entity\Location $location, \DateTime $date)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.field', 'f')
            ->leftJoin('f.location', 'l')
            ->where('l.id = :location')
            ->andWhere('b.status >= :status')
            ->andWhere('b.first_date BETWEEN :start AND :stop')
            ->setParameter('location', $location->getId())
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::CONFIRMED)
            ->setParameter('start', $date->format('Y-m-d 00:00:00'))
            ->setParameter('stop', $date->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult();
    }

    public function getIntervals(\Club\BookingBundle\Entity\Booking $booking)
    {
    }

    public function getAllFutureBookings(\Club\UserBundle\Entity\User $user, \DateTime $start)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.users', 'u')
            ->where('b.first_date >= :start')
            ->andWhere('b.status >= :status')
            ->andWhere('(b.user = :user OR u.id = :user)')
            ->setParameter('user', $user->getId())
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::CONFIRMED)
            ->setParameter('start', $start)
            ->getQuery()
            ->getResult();
    }

    public function getLatest(\Club\UserBundle\Entity\User $user, $limit=10)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('b')
            ->leftJoin('b.users', 'u')
            ->where('b.first_date < :date')
            ->andWhere('b.status >= :status')
            ->andWhere('(b.user = :user OR u.id = :user)')
            ->orderBy('b.first_date', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user', $user->getId())
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::CONFIRMED)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function getOldPending()
    {
        $date = new \DateTime();
        $i = new \DateInterval('PT30M');
        $date->sub($i);

        return $this->createQueryBuilder('b')
            ->where('b.created_at < :time')
            ->andWhere('b.status = :status')
            ->setParameter('time', $date)
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::PENDING)
            ->getQuery()
            ->getResult();
    }

    public function getAll(\Club\UserBundle\Entity\User $user)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.users', 'u')
            ->andWhere('b.status >= :status')
            ->andWhere('(b.user = :user OR u.id = :user)')
            ->setParameter('user', $user->getId())
            ->setParameter('status', \Club\BookingBundle\Entity\Booking::CONFIRMED)
            ->getQuery()
            ->getResult();
    }
}
