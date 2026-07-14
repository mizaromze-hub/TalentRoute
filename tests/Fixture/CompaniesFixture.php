<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CompaniesFixture
 */
class CompaniesFixture extends TestFixture
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
                'company_name' => 'Lorem ipsum dolor sit amet',
                'registration_number' => 'Lorem ipsum dolor sit amet',
                'address_line1' => 'Lorem ipsum dolor sit amet',
                'address_line2' => 'Lorem ipsum dolor sit amet',
                'postcode' => 'Lorem ip',
                'city' => 'Lorem ipsum dolor sit amet',
                'state' => 'Lorem ipsum dolor sit amet',
                'contact_person' => 'Lorem ipsum dolor sit amet',
                'phone_number' => 'Lorem ipsum d',
            ],
        ];
        parent::init();
    }
}
