<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Security;

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
     * 
     * Define the allowed methods for the authenticated user.
     * 
     * @param string|array $user user's authenticated informations
     * @return bool if the authenticated user is authorized or not.
     */
    public function isAuthorized($user)
    {
        if ($user['user_type_id'] == 1) {
            $actionsAllowed = ['index', 'view', 'add', 'edit', 'delete', 'editMyAccess', 'checkPass', 'resetPassword'];
        } else if ($user['user_type_id'] == 2) {
            $actionsAllowed = ['index', 'view', 'add', 'edit', 'delete', 'editMyAccess', 'checkPass'];
        } else {
            $actionsAllowed = ['editMyAccess', 'checkPass'];
        }
        $action = $this->request->getParam('action');

        return in_array($action, $actionsAllowed);
    }

    /**
     * Index method
     * 
     * Display all users.
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['UserTypes', 'Firms'],
            'order' => [
                'Users.last_name' => 'asc',
                'Users.first_name' => 'asc'
            ],
            'maxLimit' => 10
        ];
        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * Display the profile of the selected user.
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
     * Create a new user entity.
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
            } else {
                $this->Flash->error(__('L\'utilisateur n\'a pas pu être sauvegardé. Veuillez ré-essayer.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200]);
        $firms = $this->Users->Firms->find('list', ['limit' => 200]);
        $this->set(compact('user', 'userTypes', 'firms'));
    }

    /**
     * Edit method
     * 
     * Update the informations of the selected user.
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                if ($id == $this->Auth->user('id')) {
                    $this->Auth->setUser($user);
                }
                $this->Flash->success(__('L\'utilisateur a bien été sauvegardé.'));
            } else {
                $this->Flash->error(__('L\'utilisateur n\'a pas pu être sauvegardé. Veuillez ré-essayer.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200]);
        $firms = $this->Users->Firms->find('list', ['limit' => 200]);
        $this->set(compact('user', 'userTypes', 'firms'));
    }

    /**
     * Delete method
     * 
     * Delete the selected user.
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
     * Connect a user.
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
                $this->Flash->success(__('Bienvenue {0} !', $this->Auth->user('full_name')));

                return $this->redirect($url);
            } else {
                $this->Flash->error(__('Login ou mot de passe incorrect.'));
            }
        }
    }
    
    /**
     * Logout method
     * 
     * Disconnect a user.
     * 
     * @return \Cake\Http\Response|null Redirects to Login Page.
     */
    public function logout()
    {
        $this->Flash->success(__('Vous avez été déconnecté.'));  

        return $this->redirect($this->Auth->logout()); 
    }

    /**
     * EditMyAccess method
     * 
     * Update an authenticated user's access.
     * 
     * @return \Cake\Http\Response|null Redirects to the same page.
     */
    public function editMyAccess()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['post', 'patch', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user);
                $this->Flash->success(__('Vos accès ont été modifiés.'));
            } else {
                $this->Flash->error(__('Une erreur est survenue. Vos accès n\'ont pas pu être modifiés.'));
            }
            
            return $this->redirect($this->referer());
        }
        $this->set('user', $user);
    }

    /**
     * CheckPass method
     * 
     * Check the password entered by the user in the "EditMyAccess" form.
     */
    public function checkPass()
    {
        if ($this->request->is(['post', 'ajax'])) {
            $user = $this->Users->get($this->Auth->user('id'));
            $resp = (security::hash($this->request->getData('pass'), 'sha3-512') === $user->password);
            $this->set('resp', $resp);
        }
    }

    /**
     * ResetPassword method
     * 
     * Allows the user "ios" to update a user's password.
     * 
     * @return \Cake\Http\Response|null Redirects to Index Page
     */
    public function resetPassword($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['post', 'patch', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('L\'utilisateur {0} a bien été modifié.', $user->full_name));
            } else {
                $this->Flash->error(__('Une erreur est survenue. L\'utilisateur n\'a pas pu être modifié.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $this->set('user', $user);
    }
}
