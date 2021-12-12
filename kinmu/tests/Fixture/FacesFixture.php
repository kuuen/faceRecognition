<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FacesFixture
 */
class FacesFixture extends TestFixture
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
                'facepath' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-12-12 02:46:00',
                'modified' => '2021-12-12 02:46:00',
            ],
        ];
        parent::init();
    }
}
