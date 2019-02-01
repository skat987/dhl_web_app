<?php
namespace App\Controller;

use App\Controller\AppController;

// for additionnal methods
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * CustomerFiles Controller
 *
 * @property \App\Model\Table\CustomerFilesTable $CustomerFiles
 *
 * @method \App\Model\Entity\CustomerFile[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomerFilesController extends AppController
{
    /**
     * IsAuthorized method
     * 
     * Define the allowed methods for the authenticated user.
     * 
     * @param string|array $user user's authenticated informations
     * @return bool if the authenticated user is authorized or not.
     */
    public function isAuthorized($user)
    {
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['add', 'delete', 'downloadCustomerFile'];
        } else {
            $actionsAllowed = ['downloadCustomerFile'];
        }
        $action = (isset($actionsAllowed)) ? $this->request->getParam('action') : null;
        if (isset($action)) {
            return in_array($action, $actionsAllowed);
        } else {
            return false;
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Firms', 'CustomerDirectories']
        ];
        $customerFiles = $this->paginate($this->CustomerFiles);

        $this->set(compact('customerFiles'));
    }

    /**
     * View method
     *
     * @param string|null $id Customer File id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerFile = $this->CustomerFiles->get($id, [
            'contain' => ['Firms', 'CustomerDirectories']
        ]);

        $this->set('customerFile', $customerFile);
    }

    /**
     * Add method
     * 
     * Create up to 5 new Customer File entities.
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($firmId = null)
    {
        for ($i = 0; $i < MAX_UPLOADS_COUNT; $i++) {
            $customerFiles[$i] = $this->CustomerFiles->newEntity();
        }
        if ($this->request->is('post')) {
            $data = $this->getDataCustomerFile($this->request->getData());
            $customerFiles = $this->CustomerFiles->patchEntities($customerFiles, $data);
            $fileError = [];
            foreach ($customerFiles as $customerFile) {
                if (!$this->CustomerFiles->save($customerFile)) {
                    array_push($fileError, $customerFile->name);
                }
            }
            if (count($fileError) > 0) {  
                $message = $this->getUploadMessageError($fileError);        
                $this->Flash->error(__($message, $fileError));
            } else {
                $this->Flash->success(__('Tous les documents ont été sauvegardés.'));  
            }     
            
            return $this->redirect($this->referer());
        }
        $firm = $this->CustomerFiles->Firms->get($firmId);
        $customerDirectories = $this->CustomerFiles->CustomerDirectories->findListByFirmId($firmId, ['limit' => 200]);
        $this->set(compact('customerFiles', 'firm', 'customerDirectories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer File id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerFile = $this->CustomerFiles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerFile = $this->CustomerFiles->patchEntity($customerFile, $this->request->getData());
            if ($this->CustomerFiles->save($customerFile)) {
                $this->Flash->success(__('The customer file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer file could not be saved. Please, try again.'));
        }
        $firms = $this->CustomerFiles->Firms->find('list', ['limit' => 200]);
        $customerDirectories = $this->CustomerFiles->CustomerDirectories->find('list', ['limit' => 200]);
        $this->set(compact('customerFile', 'firms', 'customerDirectories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer File id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerFile = $this->CustomerFiles->get($id);
        if ($this->CustomerFiles->delete($customerFile)) {
            $this->Flash->success(__('Le document a été supprimé.'));
        } else {
            $this->Flash->error(__('Le document n\'a pas pu être supprimé. Veuillez ré-essayer.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * DownloadCustomerFile method
     * 
     * Open a download box to download a decrypted copy of the file.
     * 
     * @param string|null $id Customer File id
     * @return \Cake\Http\Response|null Redirects to the current page.
     */
    public function downloadCustomerFile($id = null)
    {
        $customerFile = $this->CustomerFiles->get($id);
        $tempPath = TMP_UPLOADS . $customerFile->file->name;
        if (!file_exists($tempPath)) {
            $tempFile = new File($tempPath, true);
            $isDecrypt = $this->decryptCustomerFile($customerFile->file->path, $customerFile->file_key, $tempPath);
            if ($isDecrypt) {
                $this->setHeaders($tempFile);
            } else {
                $tempFile->delete();
                $this->Flash->error(__('Une erreur s\'est produite lors du téléchargement.'));
            }
        } else {
            $this->Flash->error(__('Une erreur s\'est produite lors du téléchargement.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * DecryptCustomerFile method
     * 
     * Create a decrypted copy of a file before download
     * 
     * @param string $source Path to the selected file.
     * @param string $key Encryption key
     * @return string|bool The path of the copy on successful decryption or false.
     */
    private function decryptCustomerFile($source, $key, $dest)
    {
        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            if ($fpIn = fopen($source, 'rb')) {
                $iv = fread($fpIn, CHUNK_ENCRYPTION_SIZE);
                while (!feof($fpIn)) {
                    $cipherText = fread($fpIn, CHUNK_ENCRYPTION_SIZE * (FILE_ENCRYPTION_BLOCKS + 1));
                    $plainText = openssl_decrypt($cipherText, DEFAULT_ENCRYPTION_METHOD, $key, OPENSSL_RAW_DATA, $iv);
                    $iv = substr($cipherText, 0, CHUNK_ENCRYPTION_SIZE);
                    fwrite($fpOut, $plainText);
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            fclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dest;
    }

    /**
     * SetHeaders method
     * 
     * Force the download of a file
     * 
     * @param \Cake\Filesystem\File $file File to download.
     * @return \Cake\Http\Response|void
     */
    private function setHeaders(File $file)
    {
        header('Content-Disposition: attachment; filename="' . $file->name . '";');
        header('Content-Type: ' . mime_content_type($file->path));
        header('Content-Transfert-Encoding: binary');
        header('Content-Length: ' . $file->size());
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        ob_flush();
        flush();
        readfile($file->path);
        $file->delete();
        exit();
    }

    /**
     * GetDataCustomerFile method
     * 
     * Rearrange the data in an array to match the properties of a CustomerFile entity
     * 
     * @param Array $data array of request data
     * @return Array $dataReordered array of request data reorganized
     */
    private function getDataCustomerFile($data) 
    {
        $fileCount = 0;
        $dataReordered = [];
        for ($i = 0; $i < MAX_UPLOADS_COUNT; $i++) {
            if ($data['file_' . $i]['error'] === 0) {
                $dataReordered[$fileCount]['firm_id'] = $data['firm_id'];
                $dataReordered[$fileCount]['customer_directory_id'] = $data['customer_directory_id'];
                $dataReordered[$fileCount]['file'] = $data['file_' . $i];
                $dataReordered[$fileCount]['added_by'] = $this->Auth->user('id');
                $dataReordered[$fileCount]['file_key'] = Security::randomBytes(CHUNK_ENCRYPTION_SIZE);
                $fileCount++;
            }
        }
        return $dataReordered;
    }

    /**
     * GetUploadMessageError method
     * 
     * Get the message to display when an error occurs on an upload.
     * 
     * @param Array $errors files that didn't be uploaded
     * @return string $message message to display
     */
    private function getUploadMessageError($errors)
    {
        $message = 'Les documents suivant n\'ont pas pu être sauvegarder : ';
        foreach ($errors as $key => $error) {
            $message .= '{' . $key . '}';
            if ($key < (count($errors) - 1)) {
                $message .= ', ';
            }
        }
        return $message;
    }
}
