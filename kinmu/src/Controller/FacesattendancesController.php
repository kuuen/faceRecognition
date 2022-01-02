<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Facesattendances Controller
 *
 * @property \App\Model\Table\FacesattendancesTable $Facesattendances
 * @method \App\Model\Entity\Facesattendance[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FacesattendancesController extends AppController
{
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
        $facesattendances = $this->paginate($this->Facesattendances);

        $this->set(compact('facesattendances'));
    }

    /**
     * View method
     *
     * @param string|null $id Facesattendance id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $facesattendance = $this->Facesattendances->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('facesattendance'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $facesattendance = $this->Facesattendances->newEmptyEntity();
        if ($this->request->is('post')) {
            $facesattendance = $this->Facesattendances->patchEntity($facesattendance, $this->request->getData());
            if ($this->Facesattendances->save($facesattendance)) {
                $this->Flash->success(__('The facesattendance has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The facesattendance could not be saved. Please, try again.'));
        }
        $users = $this->Facesattendances->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('facesattendance', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Facesattendance id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $facesattendance = $this->Facesattendances->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $facesattendance = $this->Facesattendances->patchEntity($facesattendance, $this->request->getData());
            if ($this->Facesattendances->save($facesattendance)) {
                $this->Flash->success(__('The facesattendance has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The facesattendance could not be saved. Please, try again.'));
        }
        $users = $this->Facesattendances->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('facesattendance', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Facesattendance id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $facesattendance = $this->Facesattendances->get($id);
        if ($this->Facesattendances->delete($facesattendance)) {
            $this->Flash->success(__('The facesattendance has been deleted.'));
        } else {
            $this->Flash->error(__('The facesattendance could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function syukkin()
    {
        // error_reporting(0);

        if ($this->request->is('post')) {
            $this->autoRender = false;

            $data = $this->request->getData();

            // $time_value = '201703220134';
            $startDt = date('YYYY-mm-dd 0:0:0', strtotime($$data['syusya_dt']));
            $endDt = date('YYYY-mm-dd 23:59:59', strtotime($$data['syusya_dt']));

            $user = $this->Facesattendances->find('all', array(
                'fields' => array('name'),
                'conditions'=>array('id'=> $data["id"], 'inout_time' => $data['syusya_dt'])
            ))->first();
    

        }
    }
}
