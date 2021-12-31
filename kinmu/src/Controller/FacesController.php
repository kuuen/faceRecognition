<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Faces Controller
 *
 * @property \App\Model\Table\FacesTable $Faces
 * @method \App\Model\Entity\Face[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FacesController extends AppController
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
        $faces = $this->paginate($this->Faces);

        $this->set(compact('faces'));
    }

    /**
     * View method
     *
     * @param string|null $id Face id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $face = $this->Faces->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('face'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $face = $this->Faces->newEmptyEntity();
        if ($this->request->is('post')) {
            $face = $this->Faces->patchEntity($face, $this->request->getData());
            if ($this->Faces->save($face)) {
                $this->Flash->success(__('The face has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The face could not be saved. Please, try again.'));
        }
        $users = $this->Faces->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('face', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Face id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $face = $this->Faces->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $face = $this->Faces->patchEntity($face, $this->request->getData());
            if ($this->Faces->save($face)) {
                $this->Flash->success(__('The face has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The face could not be saved. Please, try again.'));
        }
        $users = $this->Faces->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('face', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Face id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $face = $this->Faces->get($id);
        if ($this->Faces->delete($face)) {
            $this->Flash->success(__('The face has been deleted.'));
        } else {
            $this->Flash->error(__('The face could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getcsrf()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
        } else {
            echo 'test';
        }
    }

    public function getFacePaths()
    {
        $this->autoRender = false;

        $result = $this->Faces->find()->contain(['Users']);
        
        $status = !empty($result);

        // if(!$status) {
        //     $error = array(
        //       'message' => 'データがありません',
        //       'code' => 404
        //     );
        //   }

        // JSON で出力
        // return json_encode(compact('result'));

        return $this->response->withStringBody(json_encode(compact('result')));
        // echo json_encode(compact('result'));
    }
}
