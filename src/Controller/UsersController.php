<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Initialization method
     * 
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['UserTypes', 'Firms']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['UserTypes', 'Firms']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->full_name = $user->first_name . ' ' . $user->last_name;
            $this->updateWorkersCountFirm($user->firm_id);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200]);
        $firms = $this->Users->Firms->find('list', ['limit' => 200]);
        $this->set(compact('user', 'userTypes', 'firms'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->full_name = $user->first_name . ' ' . $user->last_name;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200]);
        $firms = $this->Users->Firms->find('list', ['limit' => 200]);
        $this->set(compact('user', 'userTypes', 'firms'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     * 
     * @return \Cake\Http\Response|null Redirects to Auth HomePage.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                if (($this->Auth->user('user_type_id') == 1) || ($this->Auth->user('user_type_id') == 2)) {
                    return $this->redirect(['controller' => 'Firms', 'action' => 'index']);
                } else {
                    return $this->redirect(['controller' => 'Firms', 'action' => 'view', $this->Auth->user('firm_id')]);
                }
            } else {
                $this->Flash->error(__("Nom d'utilisateur ou mot de passe incorrect"));
            }
        }
    }
    
    /**
     * Logout method
     * 
     * @return \Cake\Http\Response|null Redirects to Login Page.
     */
    public function logout()
    {
        $this->Flash->success('Vous avez été déconnecté.');
        
        return $this->redirect($this->Auth->logout()); 
    }

    /**
     * UpdateWorkersCountFirm method
     * 
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function updateWorkersCountFirm($id)
    {
        $firm = $this->Users->Firms->get($id);
        $firm->workers_count++;
        $query = $this->Users->Firms->query();
        $query->update()
            ->set(['workers_count' => $firms->workers_count])
            ->where(['id' => $id])
            ->execute();
    }
}
