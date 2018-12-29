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
     * IsAuthorized method
     */
    public function isAuthorized($user)
    {
        if (in_array($user['user_type_id'], [1, 2])) {
            $actionsAllowed = ['index', 'view', 'add', 'edit', 'delete'];
        } else {
            $actionsAllowed = ['edit'];
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
            if ($this->Users->save($user)) {                
                $this->Flash->success(__('L\'utilisateur a bien été sauvegardé.'));    
                return $this->redirect(['action' => 'index']);
            }            
            $this->Flash->error(__('L\'utilisateur n\'a pas pu être sauvegardé. Veuillez ré-essayer'));
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
            if ($this->Users->save($user)) {          
                $this->Flash->success(__('L\'utilisateur a bien été sauvegaré.'));
                return $this->redirect(['action' => 'index']);
            }   
            $this->Flash->error(__('L\'utilisateur n\'a pas pu être sauvegardé. Veuillez ré-essayer'));         
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
            $this->Flash->success(__('L\'utilisateur a bien été supprimé.'));
        } else {
            $this->Flash->error(__('L\'utilisateur n\'a pas pu être supprimé. Veuillez ré-essayer.'));
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
                if (in_array($this->Auth->user('user_type_id'), [1, 2])) {
                    $url = ['controller' => 'Firms', 'action' => 'index'];
                } else {
                    $url = ['controller' => 'Firms', 'action' => 'view', $this->Auth->user('firm_id')];
                }
                $this->Flash->success(__('Vous êtes connecté.'));
                return $this->redirect($url);
            } else {
                $this->Flash->error(__('Login ou mot de passe incorrect.'));
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
}
