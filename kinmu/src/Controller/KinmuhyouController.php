<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Kinmuhyou Controller
 *
 * @property \App\Model\Table\KinmuhyouTable $Kinmuhyou
 * @method \App\Model\Entity\Kinmuhyou[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class KinmuhyouController extends AppController
{

  public function isAuthorized($user = null)
  {
      // // アクションによって分岐可能
      // if ($this->request->getParam('action') == 'getUserName') {

      // }

      return (bool)($user['role'] === 'admin'); // adminは無制限に許可

      return parent::isAuthorized($user);
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $this->paginate = [
        'contain' => ['Users'],
    ];
    $kinmuhyou = $this->paginate($this->Kinmuhyou);

    $this->set(compact('kinmuhyou'));
  }

  /**
   * View method
   *
   * @param string|null $id Kinmuhyou id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $kinmuhyou = $this->Kinmuhyou->get($id, [
        'contain' => ['Users'],
    ]);

    $this->set(compact('kinmuhyou'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $kinmuhyou = $this->Kinmuhyou->newEmptyEntity();
    if ($this->request->is('post')) {
        $kinmuhyou = $this->Kinmuhyou->patchEntity($kinmuhyou, $this->request->getData());
        if ($this->Kinmuhyou->save($kinmuhyou)) {
            $this->Flash->success(__('The kinmuhyou has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The kinmuhyou could not be saved. Please, try again.'));
    }
    $users = $this->Kinmuhyou->Users->find('list', ['limit' => 200])->all();
    $syukinTimes = $this->Kinmuhyou->SyukinTimes->find('list', ['limit' => 200])->all();
    $taikinTimes = $this->Kinmuhyou->TaikinTimes->find('list', ['limit' => 200])->all();
    $this->set(compact('kinmuhyou', 'users'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Kinmuhyou id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $kinmuhyou = $this->Kinmuhyou->get($id, [
        'contain' => [],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
        $kinmuhyou = $this->Kinmuhyou->patchEntity($kinmuhyou, $this->request->getData());
        if ($this->Kinmuhyou->save($kinmuhyou)) {
            $this->Flash->success(__('The kinmuhyou has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The kinmuhyou could not be saved. Please, try again.'));
    }
    $users = $this->Kinmuhyou->Users->find('list', ['limit' => 200])->all();
    $syukinTimes = $this->Kinmuhyou->SyukinTimes->find('list', ['limit' => 200])->all();
    $taikinTimes = $this->Kinmuhyou->TaikinTimes->find('list', ['limit' => 200])->all();
    $this->set(compact('kinmuhyou', 'users'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Kinmuhyou id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $kinmuhyou = $this->Kinmuhyou->get($id);
    if ($this->Kinmuhyou->delete($kinmuhyou)) {
        $this->Flash->success(__('The kinmuhyou has been deleted.'));
    } else {
        $this->Flash->error(__('The kinmuhyou could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function kinmuhyouSei()
  {
    // セッションからユーザIDと日付を持ってくる

    // 始業時刻	終業時刻	標準労働時間	実労働時間	休憩時間	残業時間	欠勤時間			
  }
}
