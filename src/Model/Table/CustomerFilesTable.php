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
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * CustomerFiles Model
 *
 * @property \App\Model\Table\FirmsTable|\Cake\ORM\Association\BelongsTo $Firms
 * @property \App\Model\Table\CustomerDirectoriesTable|\Cake\ORM\Association\BelongsTo $CustomerDirectories
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
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Firms', [
            'foreignKey' => 'firm_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CustomerDirectories', [
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
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('extension')
            ->maxLength('extension', 10)
            ->requirePresence('extension', 'create')
            ->notEmpty('extension');

        $validator
            ->scalar('key')
            ->maxLength('key', 16)
            ->requirePresence('key', 'create')
            ->notEmpty('key');

        $validator
            ->integer('customer_directory_id')
            ->allowEmpty('customer_directory_id', 'create');

        $validator
            ->integer('added_by')
            ->requirePresence('added_by', 'create')
            ->notEmpty('added_by');

        $validator 
            ->requirePresence('file', 'create')
            ->notEmpty('file');

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
        $rules->add($rules->existsIn(['customer_directory_id'], 'CustomerDirectories'));

        return $rules;
    }

    /**
     * BeforeMarshal method
     * 
     * Performs actions before the data is converted to an entity.
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['file'])) {
            if ($this->isTypeAllowed($data['file']['tmp_name'])) {                
                $data['name'] = pathinfo($data['file']['name'], PATHINFO_FILENAME);
                $data['extension'] = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
            } else {
                return false;
            }
        }
    }

    /**
     * BeforeSave method
     * 
     * Performs actions before the entity is backed up to the database.
     * It realizes the storage of the file and its encryption.
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $destinationBasePath = UPLOADS . $entity->firm_id . DS;
        $baseName = pathinfo($entity->file['name'], PATHINFO_BASENAME);
        $destinationPath = ($entity->has('customer_directory_id')) ? $destinationBasePath . $entity->customer_directory->name . DS . $baseName : $destinationBasePath . $baseName;
        $tempPath = TMP_UPLOADS . $baseName;
        if (!file_exists($tempPath)) {
            if (move_uploaded_file($entity->file['tmp_name'], $tempPath)) {
                $newFile = new File($destinationPath, true);
                $isEncrypted = $this->encryptCustomerFile($tempPath, $entity->key, $destinationPath);
                if (!$isEncrypted) {
                    $newFile->delete();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
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
        $firm->customer_files_count = $this->find()->where(['firm_id' => $firm->id])->count();
        $this->Firms->save($firm);
    }

    /**
     * BeforeDelete method
     * 
     * Performs actions before the entity is deleted.
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$entity->file->delete()) {
            return false;
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
        $firm->customer_files_count = $this->find()->where(['firm_id' => $firm->id])->count();
        $this->Firms->save($firm);
    }

    /**
     * IsTypeAllowed method
     * 
     * Check that the file type is allowed.
     * 
     * @param string $file Path to the file to check.
     * @return bool If the type is in the allowed list.
     */
    private function isTypeAllowed($file)
    {
        $typeAllowed = [
            'text/plain',
            'text/csv', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            'image/gif',
            'image/jpeg',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.text',
            'image/png',
            'application/pdf',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];  

        return in_array(mime_content_type($file), $typeAllowed);
    }

    /**
     * EncryptCustomerFile method
     * 
     * Encrypt the contents of a file by block using AES-256-CBC.
     * The original file is deleted at the end of the operation.
     * The encryption key is hashed in sha 3.
     * 
     * @param string $source Path to the file to encrypt
     * @param string $key Encryption key.
     * @param string $dest Path to the output file
     * @return string|bool $dest if the action is successful or 'false'
     */
    private function encryptCustomerFile($source, $key, $dest)
    {
        $iv = Security::randomBytes(CHUNK_ENCRYPTION_SIZE);
        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            fwrite($fpOut, $iv);
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    $plainText = fread($fpIn, CHUNK_ENCRYPTION_SIZE * FILE_ENCRYPTION_BLOCKS);
                    $cipherText = openssl_encrypt($plainText, DEFAULT_ENCRYPTION_METHOD, $key, OPENSSL_RAW_DATA, $iv);
                    $iv = substr($cipherText, 0, CHUNK_ENCRYPTION_SIZE);
                    fwrite($fpOut, $cipherText);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            if (fclose($fpOut)) {
                unlink($source);
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
        
        return $error ? false : $dest;
    }
}
