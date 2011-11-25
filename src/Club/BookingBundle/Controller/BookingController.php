<?php

namespace Club\BookingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BookingController extends Controller
{
  /**
   * @Template()
   * @Route("/booking")
   */
   public function indexAction()
   {
     $em = $this->getDoctrine()->getEntityManager();

     $date = new \DateTime();
     $location = $em->find('ClubUserBundle:Location', 2);

     $fields = $em->getRepository('ClubBookingBundle:Field')->getFieldsOverview($location, $date);

     $data = $em->getRepository('ClubBookingBundle:Field')->getDayData($location, $date);

     $period = $this->get('club_booking.interval')->getTimePeriod($data['start_time'], $data['end_time'], new \DateInterval('PT60M'));

     return array(
       'period' => $period,
       'fields' => $fields,
       'date' => $date
    );
   }

   /**
    * @Template()
    * @Route("/booking/book/{interval_id}/{date}")
    */
   public function bookAction($interval_id,$date)
   {
     $em = $this->getDoctrine()->getEntityManager();

     $date = new \DateTime($date);
     $interval = $em->find('ClubBookingBundle:Interval', $interval_id);

     return array(
       'interval' => $interval,
       'date' => $date
     );
   }

   /**
    * @Template()
    * @Route("/booking/guest/{interval_id}/{date}")
    */
   public function guestAction($interval_id,$date)
   {
     $em = $this->getDoctrine()->getEntityManager();

     $date = new \DateTime($date);
     $interval = $em->find('ClubBookingBundle:Interval', $interval_id);

     $booking = new \Club\BookingBundle\Entity\Booking();
     $booking->setUser($this->get('security.context')->getToken()->getUser());
     $booking->setInterval($interval);
     $booking->setDate($date);
     $booking->setGuest(true);

     $em->persist($booking);
     $em->flush();

     $this->get('session')->setFlash('notice', 'Your booking has been created');

     return $this->redirect($this->generateUrl('club_booking_booking_index'));
   }
}