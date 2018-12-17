<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

// for additionnals method
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

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
        'dir_name' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'firm' => true,
        'file' => true
    ];

    /**
     * Accessor for the file property
     */
    protected function _getFile()
    {
        if (!$this->isNew()) {            
            $path = ($this->_properties['dir_name']) ? WWW_ROOT . 'uploads' . DS . $this->_properties['firm_id'] . DS . $this->_properties['dir_name'] . DS . $this->_properties['file_name'] : WWW_ROOT . 'uploads' . DS . $this->_properties['firm_id'] . DS . $this->_properties['file_name'];
            return new File($path);
        }
    }

    /**
     * Mutator for the file property
     */
    protected function _setFile($file)
    {
        if (isset($file)) {            
            $path = (isset($this->_properties['dir_name'])) ? WWW_ROOT . 'uploads' . DS . $this->_properties['firm_id'] . DS . $this->_properties['dir_name'] . DS . $file['name'] : WWW_ROOT . 'uploads' . DS . $this->_properties['firm_id'] . DS . $file['name'];
            move_uploaded_file($file['tmp_name'], $path);
            return new File($path);
        }
    }
}
