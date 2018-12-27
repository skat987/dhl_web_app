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

/**
 * Users Model
 *
 * @property \App\Model\Table\UserTypesTable|\Cake\ORM\Association\BelongsTo $UserTypes
 * @property \App\Model\Table\FirmsTable|\Cake\ORM\Association\BelongsTo $Firms
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('UserTypes', [
            'foreignKey' => 'user_type_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Firms', [
            'foreignKey' => 'firm_id',
            'joinType' => 'INNER'
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
            ->scalar('first_name')
            ->maxLength('first_name', 45)
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 45)
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 45)
            ->allowEmpty('full_name');
        
        $validator
            ->scalar('phone')
            ->maxLength('phone', 45)
            ->allowEmpty('phone');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['user_type_id'], 'UserTypes'));
        $rules->add($rules->existsIn(['firm_id'], 'Firms'));

        return $rules;
    }

    /**
     * BeforeSave method
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isDirty('first_name') || $entity->isDirty('last_name')) {
            $entity->full_name = $entity->first_name . ' ' . $entity->last_name;
        }

        if (!$entity->isNew() && $entity->isDirty('firm_id')) {
            $oldFirm = $this->Firms->get($entity->getOriginal('firm_id'));
            $oldFirm->workers_count = $this->find()->where(['firm_id' => $oldFirm->id])->count() - 1;
            $this->Firms->save($oldFirm);
        }

    }

    /**
     * AfterSave method
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if($entity->isDirty('firm_id')) {
            $firm = $this->Firms->get($entity->firm_id);
            $firm->workers_count = $this->find()->where(['firm_id' => $firm->id])->count();
            $this->Firms->save($firm);
        }
    }
}