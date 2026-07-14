<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InternshipsFixture
 */
class InternshipsFixture extends TestFixture
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
                'student_id' => 1,
                'company_id' => 1,
                'status' => 'Lorem ipsum dolor sit amet',
                'start_date' => '2026-07-12',
                'end_date' => '2026-07-12',
            ],
        ];
        parent::init();
    }
}
