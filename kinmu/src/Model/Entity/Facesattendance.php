<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Facesattendance Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property \Cake\I18n\FrozenTime $inout_time
 * @property int|null $inout_type
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Facesattendance extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'inout_time' => true,
        'inout_type' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];

    // CakePHP3では、こうです
    // Entityにて
    protected function _getTest()
    {
        return "DATE_FORMAT(inout_time, '%e')";
    }
}
