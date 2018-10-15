<?php
namespace App\Controller;

use App\Controller\AppController;

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
            if ($this->CustomerFiles->save($customerFile)) {
                $this->Flash->success(__('The customer file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer file could not be saved. Please, try again.'));
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
                $this->Flash->success(__('The customer file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer file could not be saved. Please, try again.'));
        }
        $firms = $this->CustomerFiles->Firms->find('list', ['limit' => 200]);
        $this->set(compact('customerFile', 'firms'));
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
            $this->Flash->success(__('The customer file has been deleted.'));
        } else {
            $this->Flash->error(__('The customer file could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
