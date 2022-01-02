<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FacesattendancesFixture
 */
class FacesattendancesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'inout_time' => '2021-12-31 04:53:38',
                'inout_type' => 1,
                'created' => '2021-12-31 04:53:38',
                'modified' => '2021-12-31 04:53:38',
            ],
        ];
        parent::init();
    }
}
