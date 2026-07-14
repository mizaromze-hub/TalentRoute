<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Internship Entity
 *
 * @property int $id
 * @property int $student_id
 * @property int $company_id
 * @property string|null $status
 * @property \Cake\I18n\Date|null $start_date
 * @property \Cake\I18n\Date|null $end_date
 *
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\Company $company
 */
class Internship extends Entity
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
        'student_id' => true,
        'company_id' => true,
        'status' => true,
        'start_date' => true,
        'end_date' => true,
        'student' => true,
        'company' => true,
    ];
}
