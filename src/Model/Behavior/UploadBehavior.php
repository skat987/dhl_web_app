<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

// Folder & File
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

// for additionnals method
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * Upload behavior
 */
class UploadBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        
    ];    

    /**
     * BeforeMarshal method
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['file'])) {
            $data['file_name'] = $data['file']['name'];
        }
        if (isset($data['dir_name'])) {
            $data['dir_name'] = ($data['dir_name'] == 'null') ? null : $data['dir_name'];
        }
    }

    /**
     * AfterSave method
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        // if ($entity->isNew()) {
        //     $this->createFile($entity);
        // }
        // if (!$entity->isNew()) {
        //     $this->updateFile($entity);
        // }
    }

    /**
     * CreateFile method
     */
    private function createFile($entity)
    {
        $dir = new Folder(WWW_ROOT . 'uploads' . DS . $entity->firm_id);   
        $path = (is_null($entity->dir_name)) ? $dir->addPathElement($dir->pwd(), $entity->file_name) : $dir->addPathElement($dir->pwd(), [$entity->dir_name,  $entity->file_name]);
        move_uploaded_file($entity->file['tmp_name'], $path);
    }

    /**
     * UpdateFile method
     */
    private function updateFile($entity)
    {
        $dir = new Folder(WWW_ROOT . 'uploads' . DS . $entity->firm_id);        
        $path = (is_null($entity->getOriginal('dir_name'))) ? $dir->addPathElement($dir->pwd(), $entity->getOriginal('file_name')) : $dir->addPathElement($dir->pwd(), [$entity->getOriginal('dir_name'), $entity->getOriginal('file_name')]);
        $file = new File($path);

        if ($entity->isDirty('dir_name')) {
            if ($entity->dir_name != $entity->getOriginal('dir_name')) {
                $newDir = new Folder($dir->pwd() . DS . $entity->dir_name);
                $file->copy($newDir->pwd() . DS . $entity->file_name);
                $file->delete();
            }
        }
        
        if ($entity->isDirty('firm_id')) {
            $newDir = new Folder(WWW_ROOT . 'uploads' . DS . $entity->firm_id);

            if (!is_null($entity->dir_name)) {
                $newDir = new Folder($newDir->pwd() . DS . $entity->dir_name);
            }
            
            $file->copy($newDir->pwd() . DS . $entity->file_name);
            $file->delete();
        }
    }

    /**
     * AfterDelete method
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $dir = new Folder(WWW_ROOT . 'uploads' . DS . $entity->firm_id);        
        $path = (is_null($entity->dir_name)) ? $dir->addPathElement($dir->pwd(), $entity->file_name) : $dir->addPathElement($dir->pwd(), [$entity->dir_name, $entity->file_name]);
        $file = new File($path);
        $file->delete();
    }
}
