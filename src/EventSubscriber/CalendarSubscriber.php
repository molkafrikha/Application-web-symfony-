<?php

namespace App\EventSubscriber;

use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\ReclamationRepository;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $reclamationRepo ;
    private $router;

    public function __construct(ReclamationRepository $recl , UrlGeneratorInterface $router )
    {

        $this->reclamationRepo=$recl;
        $this->router=$router;
        
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();        
        $filters = $calendar->getFilters();

        $reclamations = $this->reclamationRepo->findAll();

       foreach( $reclamations as $r){

        $recalmationsEvents = new Event(
    
            $r->getIntitule(),
            $r->getDate()
            
        );
    
        $recalmationsEvents->setOptions([
        'backgroundColor' => 'red',
        'borderColor' => 'red',
        ]);
        
        $recalmationsEvents->addOption(
            'url',
            $this->router->generate('app_reclamation')
        ); 

        $calendar->addEvent($recalmationsEvents);
        }
    }
}