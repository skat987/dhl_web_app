<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Auth\LegacyPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property int $phone
 * @property string $email
 * @property string $password
 * @property int $user_type_id
 * @property int $firm_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UserType $user_type
 * @property \App\Model\Entity\Firm $firm
 */
class User extends Entity
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
        'first_name' => true,
        'last_name' => true,
        'full_name' => true,
        'phone' => true,
        'email' => true,
        'password' => true,
        'user_type_id' => true,
        'firm_id' => true,
        'created' => true,
        'modified' => true,
        'user_type' => true,
        'firm' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * Method to hash user password
     * 
     * Set the user's password property.
     * The passed value must have a length of 8 or greater than.
     * 
     * @param string $value Password to assigned to the entity.
     * @return string Hashed by the custom password hasher using 'sha3-512' algo.
     */
    protected function _setPassword($value)
    {
        if (strlen($value) >= 8) {
            return LegacyPasswordHasher::hash($value);
        }
    }
}
