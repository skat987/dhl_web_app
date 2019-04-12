<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CustomerDirectories Controller
 *
 * @property \App\Model\Table\CustomerDirectoriesTable $CustomerDirectories
 *
 * @method \App\Model\Entity\CustomerDirectory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CustomerDirectoriesController extends AppController
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
        $actionsAllowed = in_array($user['user_type_id'], [1, 2]) ? ['add', 'edit', 'delete', 'storageView', 'getDirectoriesOptions'] : ['storageView', 'getDirectoriesOptions'];
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
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($firmId = null, $type = null)
    {
        $customerDirectory = $this->CustomerDirectories->newEntity();
        $firm = $this->CustomerDirectories->Firms->get($firmId);
        if ($this->request->is('post')) {
            $customerDirectory = $this->CustomerDirectories->patchEntity($customerDirectory, $this->request->getData());
            $customerDirectory->added_by = $this->Auth->user('id');
            if ($this->CustomerDirectories->save($customerDirectory)) {
                $this->Flash->success(__('Le dossier {0} a bien été sauvegardé.', $customerDirectory->name));
            } else {
                $this->Flash->error(__('Le dossier {0} n\'a pas pu être sauvegardé. Veuillez ré-essayer.', $customerDirectory->name));
            }

            return $this->redirect($this->referer());
        }
        $this->set(compact('customerDirectory', 'firm', 'type'));
    }

    /**
     * Edit method
     * 
     * @param string|null $id CustomerDirectory id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerDirectory = $this->CustomerDirectories->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerDirectory = $this->CustomerDirectories->patchEntity($customerDirectory, $this->request->getData());
            if ($this->CustomerDirectories->save($customerDirectory)) {
                $this->Flash->success(__('Le dossier {0} a bien été sauvegardé.', $customerDirectory->name));
            } else {
                $this->Flash->error(__('Le dossier {0} n\'a pas pu être sauvegardé. Veuillez ré-essayer.', $customerDirectory->name));
            }

            return $this->redirect($this->referer());
        }
        $this->set('customerDirectory', $customerDirectory);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Directory id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerDirectory = $this->CustomerDirectories->get($id);
        if ($this->CustomerDirectories->delete($customerDirectory)) {
            $this->Flash->success(__('Le dossier a bien été supprimé.'));
        } else {
            $this->Flash->error(__('Le dossier n\'a pas pu être supprimé. Veuillez ré-essayer.'));
        }

        return $this->redirect($this->referer());
    }    

    /**
     * StorageView method
     * 
     * Display the storage content of a firm.
     * 
     * @param string|null $firmId Firm id
     * @param string|null $customerDirectoryName name of a specify directory
     */
    public function storageView($firmId = null, $customerDirectoryName = null)
    {
        $this->paginate = [
            'contain' => [
                'CustomerFiles' => [
                    'sort' => ['CustomerFiles.name' => 'ASC']
                ]
            ],
            'order' => ['CustomerDirectories.created' => 'DESC'],
            'maxLimit' => 10
        ];
        if ($customerDirectoryName == 'all') {  
            $query = $this->CustomerDirectories->findByFirmId($firmId);
            $firm = $this->CustomerDirectories->Firms->get($firmId, [
                'contain' => [
                    'CustomerFiles' => [
                        'sort' => ['CustomerFiles.name' => 'ASC']
                    ]
                ]
            ]);
        } else if (($customerDirectoryName == 'dgf') || ($customerDirectoryName == 'express')) {
            $query = $this->CustomerDirectories->findByFirmId($firmId)
                ->where(['name LIKE' => mb_strtoupper($customerDirectoryName) . '%']);
            $firm = $this->CustomerDirectories->Firms->get($firmId, [
                'contain' => [
                    'CustomerFiles' => [
                        'sort' => ['CustomerFiles.name' => 'ASC']
                    ]
                ]
            ]);
        } else {
            $query = $this->CustomerDirectories->findByFirmId($firmId)
                ->where(['name' => $customerDirectoryName]);
            $firm = $this->CustomerDirectories->Firms->get($firmId, [
                'contain' => []
            ]);
        }  
        $customerDirectories = $this->paginate($query);
        $this->set(compact('customerDirectories', 'firm'));
    }

    /**
     * GetDirectoriesOptions method
     * 
     * Display the options of the search directories input
     * 
     * @param string|null $firmId Firm id
     * @param string|null $search search to look for 
     */
    public function getDirectoriesOptions($firmId = null, $search = null)
    {
        if ($this->request->is('get')) {
            $query = $this->CustomerDirectories->findByFirmId($firmId);
            $options = $query->select(['id', 'name'])
                ->where(['name LIKE' => '%' . $search . '%'])
                ->orderDesc('name')
                ->toArray();
            $this->set(compact('options'));
        }
    }
}
