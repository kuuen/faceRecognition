<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Kinmuhyou Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $kinmu_date
 * @property int|null $syukin_time_id
 * @property int|null $taikin_time_id
 *
 * @property \App\Model\Entity\SyukinTime $syukin_time
 * @property \App\Model\Entity\TaikinTime $taikin_time
 * @property \App\Model\Entity\User $user
 */
class Kinmuhyou extends Entity
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
        'kinmu_date' => true,
        'syukin_time' => true,
        'taikin_time' => true,
        'syukin_time_id' => true,
        'taikin_time_id' => true,
        'user' => true,
    ];
}
