<?php

use App\Repositories\StandRepository;

class StandRepositoryTest extends TestCase
{
    /**
     * EventRepository unit test.
     *
     * @return void
     */
    public function testFindByInternalId()
    {
      $stand = StandRepository::findByInternalId(2, 34);
      $this->assertInstanceOf(\App\Stand::class, $stand);
    }

}
