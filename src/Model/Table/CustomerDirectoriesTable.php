<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

// for additionnal methods
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;
use Cake\Filesystem\Folder;

/**
 * CustomerDirectories Model
 *
 * @property \App\Model\Table\FirmsTable|\Cake\ORM\Association\BelongsTo $Firms
 * @property \App\Model\Table\CustomerFilesTable|\Cake\ORM\Association\HasMany $CustomerFiles
 *
 * @method \App\Model\Entity\CustomerDirectory get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerDirectory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerDirectory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDirectory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerDirectory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerDirectory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDirectory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDirectory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerDirectoriesTable extends Table
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

        $this->setTable('customer_directories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Firms', [
            'foreignKey' => 'firm_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CustomerFiles', [
            'foreignKey' => 'customer_directory_id'
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
            ->integer('added_by')
            ->requirePresence('added_by', 'create')
            ->notEmpty('added_by');

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
        $rules->add($rules->existsIn(['firm_id'], 'Firms'));

        return $rules;
    }

    /**
     * BeforeSave method
     * 
     * Performs actions before the entity is backed up to the database.
     * It creates the folder object linked to the entity.
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $newFolder = new Folder();
            $path = UPLOADS . $entity->firm_id . DS . $entity->name;
            if (!file_exists($path)) {
                if (!$newFolder->create($path)) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * AfterSave method
     * 
     * Performs actions after the entity has been saved in the database.
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $firm = $this->Firms->get($entity->firm_id);
        $firm->customer_directories_count = $this->find()->where(['firm_id' => $firm->id])->count();
        $this->Firms->save($firm);
    }

    /**
     * BeforeDelete method
     * 
     * Performs actions before the entity is deleted.
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (count($entity->folder->read()[1]) > 0) {
            return false;
        } else {
            if (!$entity->folder->delete()) {
                return false;
            }
        }
    }

    /**
     * AfterDelete method
     * 
     * Performs actions after the entity has been deleted. 
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $firm = $this->Firms->get($entity->firm_id);
        $firm->customer_directories_count = $this->find()->where(['firm_id' => $firm->id])->count();
        $this->Firms->save($firm);
    }
}
