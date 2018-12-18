<?php
namespace App\Controller;

use App\Controller\AppController;

// Folder Components
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * Firms Controller
 *
 * @property \App\Model\Table\FirmsTable $Firms
 *
 * @method \App\Model\Entity\Firm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FirmsController extends AppController
{

    /**
     * IsAuthorized method
     */
    public function isAuthorized($user)
    {
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['index', 'view', 'add', 'edit', 'delete', 'getFilesList'];
        } else {
            $actionsAllowed = ['view', 'getFilesList'];
        }
        $action = $this->request->getParam('action');
        return in_array($action, $actionsAllowed);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [
                'CustomerFiles' => [
                    'sort' => ['CustomerFiles.dir_name']
                ]
            ]
        ];
        $firms = $this->paginate($this->Firms);
        $dir = $this->getFilesList();
        $this->set(compact('firms', 'dir'));
    }

    /**
     * View method
     *
     * @param string|null $id Firm id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $firm = $this->Firms->get($id, [
            'contain' => ['CustomerFiles', 'Users']
        ]);
        $list = $this->getFilesList();
        $dir = (isset($list[$id])) ? $list[$id] : []; 
        //dd($firm);       
        $this->set(compact('firm', 'dir'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $firm = $this->Firms->newEntity();
        if ($this->request->is('post')) {
            $firm = $this->Firms->patchEntity($firm, $this->request->getData());
            $firm->added_by = $this->Auth->user('id');
            if ($this->Firms->save($firm)) {
                $this->Flash->success(__('La société a bien été sauvegardée.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La société n\'a pas pu être sauvegardée. Veuillez ré-essayer.'));
        }
        $this->set(compact('firm'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Firm id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $firm = $this->Firms->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $firm = $this->Firms->patchEntity($firm, $this->request->getData());
            if ($this->Firms->save($firm)) {
                $this->Flash->success(__('La société a bien été sauvegardée.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('La société n\'a pas pu être sauvegardée. Veuillez ré-essayer.'));
        }
        $this->set(compact('firm'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Firm id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $firm = $this->Firms->get($id);
        if ($this->Firms->delete($firm)) {
            $this->Flash->success(__('La société a bien été supprimée.'));
        } else {
            $this->Flash->error(__('La société n\'a pas pu être supprimée. Veuillez ré-essayer.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * GetFilesList method
     */
    private function getFilesList()
    {
        $dir = new Folder(WWW_ROOT . 'uploads');
        $content = $dir->read()[0];
        $results = [];        
        foreach($content as $key => $value) {
            $path = UPLOADS . $value;
            $dir->cd($path);
            $contentDirs = $dir->read()[0];
            $contentFiles = $dir->read()[1];            
            if (count($contentDirs) > 0) {          
                foreach($contentDirs as $subDirKey => $subDir) {
                    $subFolder = new Folder($dir->pwd() . DS . $subDir);
                    $results[$value]['subDirs'][$subDirKey]['name'] = $subDir;
                    if (count($subFolder->read()[1]) > 0) {
                        $contentFolder = $subFolder->read()[1];
                        foreach($contentFolder as $contentFolderKey => $contentValue) {
                            $file = new File($subFolder->pwd() . DS . $contentValue);
                            $results[$value]['subDirs'][$subDirKey]['files'][$contentFolderKey] = $file;
                        }
                    }
                }
            }
            if (count($contentFiles) > 0) {                
                foreach($contentFiles as $fileKey => $fileValue) {
                    $file = new File($dir->pwd() . DS . $fileValue);
                    $results[$value]['files'][$fileKey] = $file;
                }
            }
        }
        return $results;
    }
}
