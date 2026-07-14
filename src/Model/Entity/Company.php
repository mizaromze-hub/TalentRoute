<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $company_name
 * @property string $registration_number
 * @property string $address_line1
 * @property string|null $address_line2
 * @property string|null $postcode
 * @property string $city
 * @property string $state
 * @property string|null $contact_person
 * @property string|null $phone_number
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Internship[] $internships
 */
class Company extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'company_name' => true,
        'registration_number' => true,
        'address_line1' => true,
        'address_line2' => true,
        'postcode' => true,
        'city' => true,
        'state' => true,
        'contact_person' => true,
        'phone_number' => true,
        'user' => true,
        'internships' => true,
    ];
}
