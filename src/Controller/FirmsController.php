<?php
namespace App\Controller;

use App\Controller\AppController;

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
     * 
     * Define the allowed methods for the authenticated user.
     * 
     * @param string|array $user user's authenticated informations
     * @return bool if the authenticated user is authorized or not.
     */
    public function isAuthorized($user)
    {
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['index', 'view', 'add', 'edit', 'delete'];
        } else {
            $actionsAllowed = ['view'];
        }
        $action = $this->request->getParam('action');

        return in_array($action, $actionsAllowed);
    }

    /**
     * Index method
     *
     * Display the firms list on the admin home page.
     * 
     * @return \Cake\Http\Response|void
     */
    public function index()
    {   
        $this->paginate = [
            'order' => ['Firms.name' => 'asc'],
            'maxLimit' => 10
        ];
        $firms = $this->paginate($this->Firms);

        $this->set(compact('firms'));
    }

    /**
     * View method
     *
     * Display the profile of the firm.
     * 
     * @param string|null $id Firm id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $firm = $this->Firms->get($id, [
            'contain' => []
        ]);

        $this->set('firm', $firm);
    }

    /**
     * Add method
     * 
     * Create a new firm entity.
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $firm = $this->Firms->newEntity();
        if ($this->request->is('post')) {
            $firm = $this->Firms->patchEntity($firm, $this->request->getData());
            $firm->added_by = 1;//$this->Auth->user('id');
            if ($this->Firms->save($firm)) {
                $this->Flash->success(__('La société a bien été sauvegardée.'));
            } else {
                $this->Flash->error(__('La société n\'a pas pu être sauvegardée. Veuillez ré-essayer.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('firm'));
    }

    /**
     * Edit method
     * 
     * Update the informations of the selected firm.
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
            } else {
                $this->Flash->error(__('La société n\'a pas pu être sauvegardée. Veuillez ré-essayer.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $this->set(compact('firm'));
    }

    /**
     * Delete method
     * 
     * Delete the selected firm entity.
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
}
