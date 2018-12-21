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
use Cake\Filesystem\File;

/**
 * CustomerFiles Model
 *
 * @property \App\Model\Table\FirmsTable|\Cake\ORM\Association\BelongsTo $Firms
 *
 * @method \App\Model\Entity\CustomerFile get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerFile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerFile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerFile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerFile|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerFile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerFile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerFile findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerFilesTable extends Table
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

        $this->setTable('customer_files');
        $this->setDisplayField('file_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->scalar('file_name')
            ->maxLength('file_name', 100)
            ->requirePresence('file_name', 'create')
            ->notEmpty('file_name');

        $validator
            ->scalar('file_extension')
            ->maxLength('file_extension', 10)
            ->requirePresence('file_extension', 'create')
            ->notEmpty('file_extension');

        $validator
            ->scalar('dir_name')
            ->maxLength('dir_name', 100)
            ->allowEmpty('dir_name');

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
     * BeforeMarshal method
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['file'])) {
            $data['file_name'] = pathinfo($data['file']['name'], PATHINFO_FILENAME);
            $data['file_extension'] = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
        }
        if (isset($data['dir_name'])) {
            $data['dir_name'] = ($data['dir_name'] == 'null') ? null : $data['dir_name'];
        }
    }

    /**
     * BeforeSave method
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $basePath = UPLOADS . $entity->firm_id . DS;
            $baseName = pathinfo($entity->file['name'], PATHINFO_BASENAME);
            $path = (isset($entity->dir_name)) ? $basePath . $entity->dir_name . DS . $baseName : $basePath . $baseName;
            if (!move_uploaded_file($entity->file['tmp_name'], $path)) {
                return false;
            } 
        }
    }

    /**
     * AfterSave method
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isDirty('firm_id')) {
            $firm = $this->Firms->get($entity->firm_id);
            $firm->customer_files_count = $this->find()->where(['firm_id' => $firm->id])->count();
            $this->Firms->save($firm);
        }
    }

    /**
     * AfterDelete method
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->file->delete()) {
            $firm = $this->Firms->get($entity->firm_id);
            $firm->customer_files_count = $this->find()->where(['firm_id' => $firm->id])->count();
            $this->Firms->save($firm);
        }
    }
}
