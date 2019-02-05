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
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['add', 'delete', 'storageView', 'getDirectoriesOptions'];
        } else {
            $actionsAllowed = ['storageView', 'getDirectoriesOptions'];
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
        $customerDirectories = $this->paginate($this->CustomerDirectories);

        $this->set(compact('customerDirectories'));
    }

    /**
     * View method
     *
     * @param string|null $id Customer Directory id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerDirectory = $this->CustomerDirectories->get($id, [
            'contain' => ['Firms', 'CustomerFiles']
        ]);

        $this->set('customerDirectory', $customerDirectory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($firmId)
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
        $this->set(compact('customerDirectory', 'firm'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Directory id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerDirectory = $this->CustomerDirectories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerDirectory = $this->CustomerDirectories->patchEntity($customerDirectory, $this->request->getData());
            if ($this->CustomerDirectories->save($customerDirectory)) {
                $this->Flash->success(__('The customer directory has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer directory could not be saved. Please, try again.'));
        }
        $firms = $this->CustomerDirectories->Firms->find('list', ['limit' => 200]);
        $this->set(compact('customerDirectory', 'firms'));
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
     */
    public function storageView($firmId = null, $customerDirectoryName = null)
    {
        $this->paginate = [
            'contain' => ['CustomerFiles'],
            'maxLimit' => 10
        ];
        if ($customerDirectoryName == 'all') {  
            $query = $this->CustomerDirectories->findByFirmId($firmId);
            $firm = $this->CustomerDirectories->Firms->get($firmId, [
                'contain' => ['CustomerFiles']
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

    public function getDirectoriesOptions($firmId = null, $search = null)
    {
        if ($this->request->is('get')) {
            $query = $this->CustomerDirectories->findByFirmId($firmId);
            $options = $query->select(['id', 'name'])
                ->where(['name LIKE' => '%' . $search . '%'])->toArray();
            $this->set(compact('options'));
        }
    }
}
