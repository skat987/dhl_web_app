<?php
namespace App\Controller;

use App\Controller\AppController;

// Folder & File Components
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
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
            $actionsAllowed = ['add', 'delete', 'addDirectory', 'deleteDirectory', 'downloadCustomerFile', 'storageView'];
        } else {
            $actionsAllowed = ['downloadCustomerFile', 'storageView'];
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
            'contain' => ['Firms']
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
            'contain' => ['Firms']
        ]);
        $this->set('customerFile', $customerFile);
    }

    /**
     * Add method
     * 
     * Create a new Customer File entity.
     *
     * @return \Cake\Http\Response|null Redirects on successful add, to the current page.
     */
    public function add($firmId = null)
    {
        $customerFile = $this->CustomerFiles->newEntity();
        if ($this->request->is('post')) {
            $customerFile = $this->CustomerFiles->patchEntity($customerFile, $this->request->getData());
            $customerFile->added_by = $this->Auth->user('id');    
            $customerFile->file_key = Security::randomBytes(CHUNK_ENCRYPTION_SIZE); 
            if ($this->CustomerFiles->save($customerFile)) {          
                $this->Flash->success(__('Le fichier a bien été sauvegardé.'));
            } else {
                $this->Flash->error(__('Le fichier n\'a pas pu être sauvegardé. Veuillez ré-essayer.'));  
            }     
            return $this->redirect($this->referer());
        }
        $firm = $this->CustomerFiles->Firms->get($firmId, [
            'contain' => []
        ]);
        $this->set(compact('customerFile', 'firm'));
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
                $this->Flash->success(__('Le fichier a bien été sauvegardé.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le fichier n\'a pas pu être sauvegardé. Veuillez ré-essayer.'));
        }
        $firms = $this->CustomerFiles->Firms->find('list', ['limit' => 200]);
        $this->set(compact('customerFile', 'firms'));
    }

    /**
     * Delete method
     * 
     * Delete the selected customer file.
     *
     * @param string|null $id Customer File id.
     * @return \Cake\Http\Response|null Redirects to the current page.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerFile = $this->CustomerFiles->get($id);
        if ($this->CustomerFiles->delete($customerFile)) {
            $this->Flash->success(__('Le fichier a été supprimé.'));
        } else {
            $this->Flash->error(__('Le fichier n\'a pas pu être supprimé. Veuillez ré-essayer.'));
        }
        return $this->redirect($this->referer());
    }

    /**
     * CreateDir method
     * 
     * Create a new directory in the firm's files storage.
     * 
     * @return \Cake\Http\Response|void
     */
    public function addDirectory($firmId)
    {
        $firm = $this->CustomerFiles->Firms->get($firmId, [
            'contain' => []
        ]);
        if ($this->request->is('post')) {
            $dirName = $this->request->getData('dirName');
            $dir = new Folder();
            if ($dir->create($firm->storage->path . DS . $dirName)) {
                $this->Flash->success(__('Le dossier {0} a bien été créé.', $dirName));
            } else {
                $this->Flash->error(__('Une erreur est survenue. Le dossier {0} n\'a pas pu être créé.', $dirName));
            }
            return $this->redirect($this->referer());
        }
        $this->set('firm', $firm);
    }

    /**
     * DeleteDirectory method
     * 
     * Delete a directory from the firm's files storage.
     * 
     * @param string $firmId Firm id
     * @param string $dirName Directory name
     * @return \Cake\Http\Response|null Redirects to the current page.  
     */
    public function deleteDirectory($firmId, $dirName)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dir = new Folder(UPLOADS . $firmId . DS . $dirName);
        if (count($dir->read()[1]) > 0) {
            $this->Flash->error(__('Le dossier {0} contient des documents. Il ne peut pas être supprimé', $dirName));
        } else {
            if ($dir->delete()) {
                $this->Flash->success(__('Le dossier {0} a bien été supprimé', $dirName));
            } else {
                $this->Flash->error(__('Une erreur est survenue. Le dossier {0} n\'a pas pu être supprimé.', $dirName));
            }
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
        $customerFile = $this->CustomerFiles->get($id, [
            'contain' => []
        ]);
        $tempPath = TMP_UPLOADS . $customerFile->file->name;
        if (!file_exists($tempPath)) {
            $tempFile = new File($tempPath, true);
            $isDecrypt = $this->decryptCustomerFile($customerFile->file->path, $customerFile->file_key, $tempPath);
            if ($isDecrypt) {
                $this->setHeaders($tempFile);
            } else {
                $this->Flash->error(__('Une erreur s\'est produite lors du téléchargement.'));
            }
            $tempFile->delete();
        } else {
            $this->Flash->error(__('Une erreur s\'est produite lors du téléchargement.'));
        }
        return $this->redirect($this->referer());
    }

    public function storageView($firmId = null)
    {
        $this->paginate = [
            'order' => [
                'CustomerFiles.dir_name' => 'desc',
                'CustomerFiles.created' => 'desc'
            ],
            'maxLimit' => 1
        ];
        $query = $this->CustomerFiles->find()
            ->where(['firm_id =' => $firmId])
            ->group('dir_name')
            ->orderDesc('dir_name');
        $customerFiles = $this->paginate($query);
        $firm = $this->CustomerFiles->Firms->get($firmId);
        $this->set(compact('customerFiles', 'firm'));        
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
        flush();
        readfile($file->path);
    }
}
