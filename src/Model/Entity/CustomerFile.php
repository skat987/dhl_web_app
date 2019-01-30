<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

// for additionnals method
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * CustomerFile Entity
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property string $key
 * @property int $firm_id
 * @property int $customer_directory_id
 * @property int $added_by
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Firm $firm
 * @property \App\Model\Entity\CustomerDirectory $customer_directory
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
        'name' => true,
        'extension' => true,
        'key' => true,
        'firm_id' => true,
        'customer_directory_id' => true,
        'added_by' => true,
        'created' => true,
        'modified' => true,
        'firm' => true,
        'customer_directory' => true,
        'file' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     * 
     * @var array
     */
    protected $_hidden = [
        'key'
    ];

    /**
     * Accessor of the file property
     * 
     * Returns the file linked to the entity.
     * 
     * @param array|null $file file stored or to store on the application
     * @return \Cake\Filesystem\File|array If it's a new entity, returns the file's informations in an array, else return the file stored.
     */
    protected function _getFile($file = null)
    {
        if (!$this->isNew()) { 
            $basePath = UPLOADS . $this->_properties['firm_id'] . DS;
            $baseName = $this->_properties['name'] . '.' . $this->_properties['extension'];          
            $path = ($this->has('customer_directory_id')) ? $basePath . $this->_properties['customer_directory']['name'] . DS . $baseName : $basePath . $baseName;
            
            return new File($path);
        } else {
            if (isset($file)) {
                return $file;
            }
        }
    }

    /**
     * Mutator of the file_key property. 
     * 
     * @param string $value key to assign to the entity
     * @return string Hashed with the 'sha3-512' algo.
     */
    protected function _setKey($value)
    {
        if (strlen($value) >= CHUNK_ENCRYPTION_SIZE) {
            return substr(Security::hash($value, 'sha3-512'), 0, CHUNK_ENCRYPTION_SIZE);
        }
    }
}
