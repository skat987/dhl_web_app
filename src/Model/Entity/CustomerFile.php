<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerFile Entity
 *
 * @property int $id
 * @property string $file_name
 * @property int $firm_id
 * @property int $tag
 * @property int $added_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Firm $firm
 */
class CustomerFile extends Entity
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
        'file_name' => true,
        'firm_id' => true,
        'tag' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'firm' => true
    ];
}
