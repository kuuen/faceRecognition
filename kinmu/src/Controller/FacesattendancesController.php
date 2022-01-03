<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\FacesattendancesTable;
use Cake\ORM\SaveOptionsBuilder;
use Cake\ORM\TableRegistry;

use DateTimeImmutable;

/**
 * Facesattendances Controller
 *
 * @property \App\Model\Table\FacesattendancesTable $Facesattendances
 * @method \App\Model\Entity\Facesattendance[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FacesattendancesController extends AppController
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
        error_reporting(0);

        if ($this->request->is('post')) {
            $this->autoRender = false;

            $data = $this->request->getData();

            // $time_value = '201703220134';
            $startDt = date('Y-m-d 0:0:0', strtotime($data['syusya_dt']));
            $endDt = date('Y-m-d 23:59:59', strtotime($data['syusya_dt']));

            $syukindata = $this->Facesattendances->find('all', array(
                'fields' => array('user_id'),
                'conditions' => array(
                    'user_id'=> $data["id"],
                    'inout_time >=' => $startDt,
                    'inout_time <=' =>  $endDt
                ),
            ))->first();
    


            // 既に登録済みの場合は何もしない
            if ($syukindata != Null) {
                return;
            }

            // 登録を行う
            $facesattendancesTable = TableRegistry::getTableLocator()->get('Facesattendances');

            $facesattendance = $facesattendancesTable->newEmptyEntity();
            # ユーザID
            $facesattendance->user_id = $data["id"];
            # 日時
            $facesattendance->inout_time = $data["syusya_dt"];
            # 出社
            $facesattendance->inout_type = 0;

            # 登録
            $facesattendancesTable->save($facesattendance);

        }
    }

    
    public function timeCord() {
        
        error_reporting(0);

        // ユーザの取得
        $users = TableRegistry::getTableLocator()->get('Users');

        $syains = $users->find('all', array(
            'fields' => array('id', 'username')
        ))->all();

        // ユーザをviewに渡す値に整形
        $user_options = array();
        foreach ($syains as $syain) {
            $user_options[$syain["id"]] = $syain['username'];
        }

        // 年ドロップダウンリスト
        $year_options = array();
        for ($i = 2; $i >= 0; $i--) {
            $year_options[date('Y') - $i] = (string) (date('Y') - $i) . '年';
        }
        
        // 月ドロップダウンリスト
        $month_options = array();
        for ($i = 1; $i <= 12; $i++) {
            $month_options[$i] = ((string) $i) . '月';
        }
        
        $this->set(compact("user_options"));
        $this->set(compact("year_options"));
        $this->set(compact("month_options"));
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // 月初め
            $startDt = $data['year'] . '-' . $data['month'] . '-1 0:0:0';
            // 月末日を取得
            $endDt = (new DateTimeImmutable)->modify('last day of ' . $data['year'] . '-' . $data['month'])->format('Y-m-d 23:59:59')  ;

            $syukindata = $this->Facesattendances->find('all', array(
                'fields' => array('user_id', 'inout_time', 'inout_type'),
                'conditions'=>array('user_id'=> $data["user_id"],
                'inout_time >=' => $startDt,
                'inout_time <=' =>  $endDt
            )))->all();

            // foreach ($syukindata as $l) {
            //     echo $l;                
            // }

            $this->CreateClender($data['year'], $data['month'], $syukindata);
        } else {
            // 初期値
            $year_selected = (int)date('Y');
            $month_selected = (int)date("m");
            $this->set(compact("year_selected"));
            $this->set(compact("month_selected"));
        }
    }

    /**
     * カレンダー作成
     * $y:対象年
     * $m:対象月
     */
    private function CreateClender($y=null, $m=null, $syukindatas) {
        $y= $y ? :date("Y");
        $m= $m ? :date("m");

        $array=array(
            date("Y/m/d",mktime(0,0,0,(int) $m + 0, 1, (int)$y)),
            date("Y/m/d",mktime(0,0,0,(int) $m + 1, 0, (int)$y))
        );

        $tsta=$array[0];
        $tend=$array[1];

        $i=0;
        while(strcmp(date('N',strtotime($tsta)),"7")) {
            $tsta=date("Y/m/d",strtotime($tsta." -1 day"));
        }
        while(strcmp(date('N',strtotime($tend)),"6")) {
            $tend=date("Y/m/d",strtotime($tend." +1 day"));
        }

        $calendar = array();
        $week = array();
        $header = array();
        $temp = $tsta;

        while(1 == 1) {
            if(count($week) == 7) {
                array_push($calendar, $week);
                if(count($header)!=7) {
                    foreach($week as $key=>$value) {
                        array_push($header,date("D",strtotime($value)));
                    }
                }
                $week=array();
            }
            array_push($week,$temp);
            if(strtotime($tend)==strtotime($temp)) {
                array_push($calendar,$week);
                break;
            }
            $temp = date("Y/m/d",strtotime($temp." +1 day"));
        };
        foreach($calendar as $key => $value) {
            foreach($value as $key2 => $value2) {
                if (intval(date("m",strtotime($value2))) != intval($m)) {
                    $calendar[$key][$key2] = "&nbsp;";
                } else {

                    $kintai = $this->FindSyukindata($value2, $syukindatas);

                    if ($kintai[0] != '' or $kintai[1]) {
                        $calendar[$key][$key2] = date("d",strtotime($value2)) . '<br/>入：' . $kintai[0] . '<br/>出：' . $kintai[1];
                    } else {
                        $calendar[$key][$key2] = date("d",strtotime($value2));
                    }
                }
            }
        }

        $this->set("year", $y);
        $this->set("month", $m);
        $this->set("calendar", $calendar);
        $this->set("header", $header);
    }

    private function FindSyukindata($dt, $syukindatas) {
        
        $ary = array();
        $ary[0] = '';
        $ary[1] = '';
        foreach ($syukindatas as $syukindata) {
            if ($syukindata['inout_time']->i18nFormat('YYYY/MM/dd') == $dt) {

                if ($syukindata['inout_type'] == 0) {
                    $ary[0] = $syukindata['inout_time']->i18nFormat('HH:mm');
                } else {
                    $ary[1] = $syukindata['inout_time']->i18nFormat('HH:mm');
                }

                if ($ary[0] != '' and $ary[1] != '') {
                    break;
                }
            }
        }
        return $ary;
    }


}
