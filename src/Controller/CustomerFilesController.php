<?php
namespace App\Controller;

use App\Controller\AppController;

// for additionnal methods
use Cake\Filesystem\File;
use Cake\Utility\Security;
use Cake\Mailer\Email;

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
        $actionsAllowed = in_array($user['user_type_id'], [1, 2]) ? ['add', 'delete', 'downloadCustomerFile', 'getFileItemElement'] : ['downloadCustomerFile', 'getFileItemElement'];
        $action = $this->request->getParam('action');
        if ($user['user_type_id'] == 3) {
            $firmId = $this->request->getParam('firm_id');
            if ($user['firm_id'] != $firmId) {
                return false;
            }
        }
        return in_array($action, $actionsAllowed);
    }

    /**
     * Add method
     * 
     * Create up to 10 new Customer File entities.
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($firmId = null)
    {
        for ($i = 0; $i < MAX_UPLOADS_COUNT; $i++) {
            $customerFiles[$i] = $this->CustomerFiles->newEntity();
        }
        if ($this->request->is('post')) {
            $resp = [
                'firmId' => $firmId,
                'filesCount' => null,
                'dirId' => null,
                'items' => [],
                'error' => null 
            ];
            $data = $this->getDataCustomerFile($this->request->getData());
            $resp['dirId'] = empty($data[0]['customer_directory_id']) ? null : $data[0]['customer_directory_id'];
            $customerFiles = $this->CustomerFiles->patchEntities($customerFiles, $data);
            $fileError = $items = [];
            $success = false;
            $i = 0;
            $createdDate = null;
            foreach ($customerFiles as $customerFile) {
                if (!$this->CustomerFiles->save($customerFile)) {
                    array_push($fileError, $customerFile->name);
                } else {
                    if (!$success) {
                        $success = true;
                    }
                    if (!isset($createdDate)) {
                        $createdDate = $customerFile->created;
                    }
                    $items[$i] = [
                        'fileId' => $customerFile->id,
                        'fileName' => $customerFile->name,
                        'fileExt' => $customerFile->extension
                    ];
                    $i++;
                }
            }
            if ($success) {
                $this->sendAddNotifications($firmId, $items, $resp['dirId'], $createdDate);
                $resp['items'] = $items;
                $resp['filesCount'] = $this->CustomerFiles->Firms->get($firmId)->customer_files_count;
            }
            if (count($fileError) > 0) {  
                $resp['error'] = $this->getUploadMessageError($fileError);      
            }   
            $this->set(compact('resp'));
        }
        $firm = $this->CustomerFiles->Firms->get($firmId);
        $customerDirectories = $this->CustomerFiles->CustomerDirectories
            ->findListByFirmId($firmId)
            ->order('CustomerDirectories.name');
        $this->set(compact('customerFiles', 'firm', 'customerDirectories'));
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
        if ($this->request->is(['post'])) {
            $customerFile = $this->CustomerFiles->get($id);
            if ($this->CustomerFiles->delete($customerFile)) {
                $resp = [
                    'result' => 'success',
                    'text' => __('Le document {0}.{1} a été supprimé.', [$customerFile->name, $customerFile->extension]),
                    'filesCount' => $this->CustomerFiles->Firms->get($customerFile->firm_id)->customer_files_count,
                    'firmId' => $customerFile->firm_id
                ];
            } else {                
                $resp = [
                    'result' => 'error',
                    'text' => __('Le document {0} n\'a pas pu être supprimé.', $customerFile->name)
                ];
            }
            $this->set(compact('resp'));
        }
    }

    /**
     * DownloadCustomerFile method
     * 
     * Open a download box to download a decrypted copy of the file.
     * 
     * @param string|null $id Customer File id
     * @return \Cake\Http\Response|null Redirects to the current page.
     */
    public function downloadCustomerFile($firmId = null, $id = null)
    {
        $customerFile = $this->CustomerFiles->find()
            ->where([
                'firm_id' => $firmId,
                'id' => $id
            ])
            ->first();
        if (!is_null($customerFile)) {
            if (!file_exists($customerFile->file->path)) {
                $this->Flash->error(__('Le document {0} est introuvable. Veuillez signaler ce message.', $customerFile->name));
            } else {
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
            }
        } else {
            $this->Flash->error(__('Le document est introuvable. Veuillez contacter votre administrateur.'));
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
        $message = (count($errors) > 1) ? 'Les documents suivant n\'ont pas pu être sauvegardés: ' : 'Le document suivant n\'a pas pu être sauvegardé: ';
        foreach ($errors as $key => $error) {
            $message .= '<br/> - ' . $error;
        }
        return $message;
    }

    /**
     * SendAddNotifications method
     * 
     * Send an e-mail to notify the addition of a new document
     * 
     * @param string|null $firmId Firm id
     * @param Array $customerFiles added files
     */
    private function sendAddNotifications($firmId = null, $items = null, $dirId = null, $created = null)
    { 
        $firm = $this->CustomerFiles->Firms->get($firmId, [
            'contain' => [
                'Users' => function($q) {
                    return $q->select(['firm_id', 'email'])
                        ->where(['Users.has_email_notification' => true]);
                }
            ]
        ]);
        $email = new Email('default');
        foreach ($firm->users as $u) {
            $email->addTo($u->email);
        }
        if (count($email->getTo()) > 0) {
            $email->setFrom(['no-reply@exdoc-tahiti.com' => 'exdoc-tahiti.com'])
                ->setSubject('TRANSFERT DOCUMENTS');
            $lineBreak = "\n";
            $signature = 'Cordialement,' . $lineBreak . 'DHL Global Forwarding Polynésie' . $lineBreak . 'DHL Express' . $lineBreak . 'Immeuble Tavararo' . $lineBreak . 'BP 62255' . $lineBreak . '98702 FAA\'A Centre' . $lineBreak . 'Tahiti/Polynésie française (French Polynesia)';
            $lineBreak = "\n\n";
            $message = 'Chèr(e) client(e),' . $lineBreak;
            $uploads = '';
            foreach ($items as $key => $item) {
                $uploads .= __('"{0}.{1}"', [$item['fileName'], $item['fileExt']]);
                $uploads .= __('{0}', ($key < (count($items) - 1)) ? ', ' : '');
            }
            if (isset($dirId)) {
                $customerDirectory = $this->CustomerFiles->CustomerDirectories->get($dirId);
                $message .= (count($items) > 1) ? 'Les documents {0} ({1}) ont été déposés dans le dossier "{2}" de votre espace client WEB d\'échanges de documents, le {3}.' : 'Le document {0} ({1}) a été déposé dans le dossier "{2}" de votre espace client WEB d\'échanges de documents, le {3}.';
                $content = __($message, [$uploads, substr($customerDirectory->name, 0, strpos($customerDirectory->name, '_')), $customerDirectory->name, $created->format('d/m/y')]);
            } else {
                $message .= (count($items) > 1) ? 'Les documents {0} ont été déposés dans votre espace client WEB d\'échanges de documents, le {1}.' : 'Le document {0} a été déposé dans votre espace client WEB d\'échanges de documents, le {1}.';
                $content = __($message, [$uploads, $created->format('d/m/y')]);
            }
            $email->send($content . $lineBreak . $signature);
        }
    }

    /**
     * Get a file item element with ajax request
     */
    public function getFileItemElement() {
        $this->autoRender = false;
        $fileData = $this->request->getQuery();
        $firmId = $fileData['firmId'];
        $customerFileId = $fileData['fileId'];
        $customerFileName = $fileData['fileName'];
        $customerFileExt = $fileData['fileExt'];
        $this->set(compact('firmId', 'customerFileId', 'customerFileName', 'customerFileExt'));
        $this->render('/Element/fileItem');
    }
}
