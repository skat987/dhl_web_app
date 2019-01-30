<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

// for additionnals method
use Cake\Filesystem\Folder;

/**
 * CustomerDirectory Entity
 *
 * @property int $id
 * @property string $name
 * @property int $firm_id
 * @property int $added_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Firm $firm
 * @property \App\Model\Entity\CustomerFile[] $customer_files
 * 
 * @property \Cake\Filesystem\Folder $folder
 */
class CustomerDirectory extends Entity
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
        'firm_id' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'firm' => true,
        'customer_files' => true,
        'folder' => true
    ];

    /**
     * Accessor of the folder property
     * 
     * Returns the folder linked to the entity.
     * 
     * @return \Cake\Filesystem\Folder folder object
     */
    protected function _getFolder()
    {
        if (!$this->isNew()) {
            $path = UPLOADS . $this->_properties['firm_id'] . DS . $this->_properties['name'];

            return new Folder($path);
        }
    }
}
