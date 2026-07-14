<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StudentsFixture
 */
class StudentsFixture extends TestFixture
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
                'matrix_number' => 'Lorem ipsum dolor ',
                'name' => 'Lorem ipsum dolor sit amet',
                'faculty' => 'Lorem ipsum dolor sit amet',
                'course' => 'Lorem ipsum dolor sit amet',
                'semester' => 1,
                'phone_number' => 'Lorem ipsum d',
                'qr_code_path' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
