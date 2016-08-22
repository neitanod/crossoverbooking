<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends TestCase
{
    /**
     * General functional test.
     *
     * @return void
     */
    public function testHome()
    {
        $this->visit('/')
             ->see('Please select')
             ->see('Book your place');
    }

    public function testHall()
    {
        $this->visit('/event/1')
             ->see('Select your desired location');
    }

    public function testPanelAuth()
    {
        $this->visit('/panel')
             ->see('<div class="panel-heading">Login</div>');
    }

    public function testPanelPreloader()
    {
        $user = \App\User::first();
        $this->be($user);
        $this->visit('/panel')
             ->see('Loading...');
    }
}
