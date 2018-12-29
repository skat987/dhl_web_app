<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

// for additionnals method
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * CustomerFile Entity
 *
 * @property int $id
 * @property string $file_name
 * @property string $file_extension
 * @property string $file_key
 * @property int $firm_id
 * @property int $added_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Firm $firm
 * 
 * @property \Cake\Filesystem\File $file
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
        'file_extension' => true,
        'file_key' => true,
        'firm_id' => true,
        'dir_name' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'firm' => true,
        'file' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     * 
     * @var array
     */
    protected $_hidden = [
        'file_key'
    ];

    /**
     * Accessor of the file property
     */
    protected function _getFile($file = null)
    {
        if (!$this->isNew()) { 
            $basePath = UPLOADS . $this->_properties['firm_id'] . DS;
            $baseName = $this->_properties['file_name'] . '.' . $this->_properties['file_extension'];           
            $path = (isset($this->_properties['dir_name'])) ? $basePath . $this->_properties['dir_name'] . DS . $baseName : $basePath . $baseName;
            return new File($path);
        } else {
            if (isset($file)) {
                return $file;
            }
        }
    }

    /**
     * Mutator of the file-key property 
     */
    protected function _setFileKey($value)
    {
        if (strlen($value) >= CHUNK_ENCRYPTION_SIZE) {
            return substr(Security::hash($value, 'sha3-512'), 0, CHUNK_ENCRYPTION_SIZE);
        }
    }
}
