<?php
namespace Collector\AdminBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CalendarEventListener {

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $memberIds = $request->get('memberIds');
        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $records = $this->entityManager->getRepository('CollectorEntityBundle:Record')
            ->createQueryBuilder('record')
            ->where('record.scheduleDate BETWEEN :startDate and :endDate AND record.member IN( :memberIds)')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->setParameter('memberIds',$memberIds)
            ->getQuery()->getResult();

        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        foreach($records as $record) {
            $scheduleDate = $record->getScheduleDate();
            $scheduleStartAt = $record->getScheduleStartAt();
            $scheduleEndAt = $record->getScheduleEndAt();

            $startAt = new \DateTime();
            $startAt->setDate($scheduleDate->format("Y"),$scheduleDate->format("m"),$scheduleDate->format("d"));
            $startAt->setTime($scheduleStartAt->format("H"),$scheduleStartAt->format("i"));

            $endAt = new \DateTime();
            $endAt->setDate($scheduleDate->format("Y"),$scheduleDate->format("m"),$scheduleDate->format("d"));
            $endAt->setTime($scheduleEndAt->format("H"),$scheduleEndAt->format("i"));

            $eventEntity = new EventEntity($record->getIncident()->__toString(), $startAt, $endAt);

            //optional calendar event settings
            $eventEntity->setAllDay(false); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#99FFFF'); //set the background color of the event's label
            $eventEntity->setFgColor('#000000'); //set the foreground color of the event's label
            $eventEntity->setUrl($this->container->get('router')->generate('admin_collector_entity_record_edit',array('id'=>$record->getId()))); // url to send user to when event label is clicked
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
} 