<?php
namespace App\Controller;

use App\Controller\AppController;

// Folder & File Components
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

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
     */
    public function isAuthorized($user)
    {
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['add', 'delete', 'getFirmDirectories', 'createDir', 'deleteDir', 'downloadCustomerFile'];
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
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customerFile = $this->CustomerFiles->newEntity();
        if ($this->request->is('post')) {
            $customerFile = $this->CustomerFiles->patchEntity($customerFile, $this->request->getData());
            $customerFile->added_by = $this->Auth->user('id');         
            if ($this->CustomerFiles->save($customerFile)) {          
                $this->Flash->success(__('Le fichier a bien été sauvegardé.'));
            } else {
                $this->Flash->error(__('Le fichier n\'a pas pu être sauvegardé. Veuillez ré-essayer.'));  
            }     
            return $this->redirect($this->referer());
        }
        $firms = $this->CustomerFiles->Firms->find('list', ['limit' => 200]);
        $this->set(compact('customerFile', 'firms'));
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
        $this->set(compact('customerFile', 'firms', 'file'));
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
            $this->Flash->success(__('Le fichier a été supprimé.'));
        } else {
            $this->Flash->error(__('Le fichier n\'a pas pu être supprimé. Veuillez ré-essayer.'));
        }
        return $this->redirect($this->referer());
    }

    /**
     * GetFirmDirectories method
     */
    public function getFirmDirectories($firmId)
    {
        $dir = new Folder(UPLOADS . $firmId);
        $directories = $dir->read()[0];
        $this->set(compact('directories'));
    }

    /**
     * CreateDir method
     */
    public function createDir()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $dir = new Folder(UPLOADS . $data['firmId']);
            $newDir = new Folder($dir->pwd() . DS . $data['newDir'], true);
            $resp = [
                'message' => 'Le dossier ' . $data['newDir'] . ' a bien été créé.',
                'value' => $data['newDir']
            ];
            $this->set(compact('resp'));
        }
    }

    /**
     * DeleteDir method
     */
    public function deleteDir($firmId, $dirName)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dir = new Folder(UPLOADS . $firmId . DS . $dirName);
        if (count($dir->read()[1]) > 0) {
            $this->Flash->error(__('Le dossier {0} contient des documents. Il ne peut pas être supprimé', $dirName));
        } else {
            if ($dir->delete()) {
                $this->Flash->success(__('Le dossier {0} a bien été supprimé', $dirName));
            }
        }
        return $this->redirect($this->referer());
    }

    private function decryptCustomerFile($source, $key, $dest)
    {
        $key = substr(hash('sha3-512', $key, true), 0, 16);
        $error = false;
        if ($fpOut = fopen($dest, 'w')) {
            if ($fpIn = fopen($source, 'rb')) {
                $iv = fread($fpIn, 16);
                while (!feof($fpIn)) {
                    $cipherText = fread($fpIn, 16 * (10000 + 1));
                    $plainText = openssl_decrypt($cipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
                    $iv = substr($cipherText, 0, 16);
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

    public function downloadCustomerFile($id = null)
    {
        $customerFile = $this->CustomerFiles->get($id, [
            'contain' => []
        ]);
        $key = 'aZeRtY789yTrEzA';
        $tempPath = UPLOADS . 'Temp' . DS . $customerFile->file->name;
        if (!file_exists($tempPath)) {
            $download = new File($tempPath, true);
            $tempFile = $this->decryptCustomerFile($customerFile->file->path, $key, $tempPath);
            $this->setDownloadHeaders($download);
            $download->delete();
        } else {
            $this->Flash->error(__('Une erreur s\'est produite lors du téléchargement.'));
        }
        return $this->redirect($this->referer());
    }

    private function setDownloadHeaders(File $file)
    {
        $mimeType = (mime_content_type($file->path) == 'text/plain') ? 'text/plain' : 'application/octet-stream';
        header('Content-Disposition: attachment; filename="' . $file->name . '";');
        header('Content-Type: ' . $mimeType);
        header('Content-Transfert-Encoding: binary');
        header('Content-Length: ' . $file->size());
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        flush();
        readfile($file->path);
    }
}
