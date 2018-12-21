<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

// for additionnals method
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;
use Cake\Filesystem\Folder;

/**
 * Firms Model
 *
 * @property \App\Model\Table\CustomerFilesTable|\Cake\ORM\Association\HasMany $CustomerFiles
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Firm get($primaryKey, $options = [])
 * @method \App\Model\Entity\Firm newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Firm[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Firm|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Firm|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Firm patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Firm[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Firm findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FirmsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('firms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('CustomerFiles', [
            'foreignKey' => 'firm_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'firm_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 45)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('workers_count')
            ->allowEmpty('workers_count');

        $validator
            ->integer('customer_files_count')
            ->allowEmpty('customer_files_count');

        $validator
            ->integer('added_by')
            ->requirePresence('added_by', 'create')
            ->notEmpty('added_by');

        return $validator;
    }

     /**
     * AfterSave method
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $newDir = new Folder(UPLOADS . $entity->id, true);
        }
    }    
}
