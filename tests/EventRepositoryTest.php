<?php

use App\Repositories\EventRepository;

class EventRepositoryTest extends TestCase
{
    /**
     * EventRepository unit test.
     *
     * @return void
     */
    public function testUpcomingEventsList()
    {
      $events = EventRepository::upcomingEventsList('2016-08-01');
      $this->assertEquals(2, count($events));
    }

    public function testUpcomingEventsListDate()
    {
      $events = EventRepository::upcomingEventsList('2016-09-06');
      $this->assertEquals(1, count($events));
    }

    public function testEventDetails()
    {
      $event = EventRepository::eventDetails(1);
      $this->assertEquals($event['id'], 1);

      $event = EventRepository::eventDetails(2);
      $this->assertEquals($event['id'], 2);
    }

}
