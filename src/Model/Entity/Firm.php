<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

// for additionnals method
use Cake\Filesystem\Folder;

/**
 * Firm Entity
 *
 * @property int $id
 * @property string $name
 * @property int $workers_count
 * @property int $customer_files_count
 * @property int $added_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\CustomerFile[] $customer_files
 * @property \App\Model\Entity\User[] $users
 */
class Firm extends Entity
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
        'name' => true,
        'workers_count' => true,
        'customer_files_count' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'customer_files' => true,
        'users' => true,
        'dir' => true
    ];

    /**
     * Accessor for the dir property
     */
    protected function _getDir()
    {
        if (!$this->isNew()) {
            return new Folder(UPLOADS . $this->_properties['id']);
        }
    }
}
