<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // // getUserNameは無制限に許可
        // if (in_array($this->request->getParam('action'), ['getUserName'])) {
        //   $this->Authorization->skipAuthorization();
        // }
    }

    public function isAuthorized($user = null)
    {
        return (bool)($user['role'] === 'admin');

        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Faces'],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
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

    public function login()
    {
        if ($this->request->is('post')) {
            // identify()メソッドをもちいてリクエスト中の認証情報を使用してユーザーを識別する
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                //$this->request->session()->delete('Auth.redirect'); // 固定ページに移動させたい場合
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('ユーザー名またはパスワードが不正です。');
        }
    }
    
    // public function login() {
    //     $success = false;
    //     // ログイン処理
    //     if ($this->request->is('post')) {
    //       // ユーザ認証
    //       $user = $this->Auth->identify();
    //       if ($user) {
    //         // ログインを記録
    //         $this->Auth->setUser($user);
    //         $success = true;
    //       }
    //     }
    //     // レスポンス生成
    //     if (!$this->getRequest()->is('ajax')) {
    //       // HTML
    //       if (!$this->getRequest()->getSession()->check('Flash.flash')) {
    //         if ($success) {
    //           $this->Flash->success(__('ログインしました'));
    //         } else {
    //           $this->Flash->error(__('Username or password is incorrect !'));
    //         }
    //       }
    //       if ($success) {
    //           return $this->redirect($this->Auth->redirectUrl());
    //       }
    //     } else {
    //       // AJAX/JSON
    //       if ($success) {
    //         $this->set('login', 'ok');
    //       } else {
    //         $this->set('login', 'failed');
    //       }
    //       $this->set('_serialize', ['username', 'login']);
    //     }
    //   }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    // public function logout() {
    //     // ログアウト
    //     $url = $this->Auth->logout();
      
    //     // レスポンス生成
    //     if (!$this->getRequest()->is('ajax')) {
    //       // HTML
    //       $this->Flash->success('ログアウトしました');
    //       return $this->redirect($url);
    //     } else {
    //       // AJAX/JSON
    //       $this->set('logout', 'ok');
    //       $this->set('_serialize', ['logout']);
    //     }
    // }

    public function getUserName()
    {
        // 警告メッセージを出力しないようにする
        error_reporting(0);

        if ($this->request->is('post')) {
            $this->autoRender = false;

            $data = $this->request->getData();

            $user = $this->Users->find('all', array(
                'fields' => array('username'),
                'conditions'=>array('id'=> $data["id"])
            ))->first();
    
            if ($user != null) {
                echo $user->username;
            }
        }
    }    
}
