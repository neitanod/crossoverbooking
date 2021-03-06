<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApisTest extends TestCase
{
    /**
     * APIs functional test.
     *
     * @return void
     */
    public function testEventsList()
    {
        $this->visit('/API/event/list')
             ->see('{"events":');
    }

    public function testEventDetails()
    {
        $this->visit('/API/event/1')
             ->see('"name":');
    }

}
