<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Student Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $matrix_number
 * @property string $name
 * @property string $faculty
 * @property string $course
 * @property int $semester
 * @property string|null $phone_number
 * @property string|null $qr_code_path
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Internship[] $internships
 */
class Student extends Entity
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
        'matrix_number' => true,
        'name' => true,
        'faculty' => true,
        'course' => true,
        'semester' => true,
        'phone_number' => true,
        'qr_code_path' => true,
        'user' => true,
        'internships' => true,
    ];
}
