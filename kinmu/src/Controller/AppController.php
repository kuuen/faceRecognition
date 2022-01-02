<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    // src/Controller/AppController.php で
    // public function beforeFilter(\Cake\Event\EventInterface $event)
    // {
    //     parent::beforeFilter($event);
    //     // このアプリケーションのすべてのコントローラのために、
    //     // インデックスとビューのアクションを公開し、認証チェックをスキップします
    //     $this->Authentication->addUnauthenticatedActions(['index', 'view']);
    // }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // $this->loadComponent('RequestHandler');

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authorize'=> 'Controller',//この行を追加
            //送信されたフォームデータのキーとログイン処理の「username」「password」を紐つける設定
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            //ログイン処理を実行する場所設定
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            //ログイン後のリダイレクト先設定
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'index'
            ],
            //ログアウト後のリダイレクト先設定
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
            // 未認証時、元のページを返します。
            'unauthorizedRedirect' => $this->referer()
        ]]);

        // // リクエストの条件により動作を選択する
        // if ($this->getRequest()->is('ajax') || $this->getRequest()->is('json')) {
        //     // AJAX の ケース
        //     $unauthorizedRedirect = false;
        //     $validatePost = false;
        // } else {
        //     // HTML の ケース
        //     $unauthorizedRedirect = true;
        //     $validatePost = true;
        // }

        // // コンポーネントを読み込む
        // $this->loadComponent('Security', [
        //     'validatePost' => $validatePost,
        // ]);

        // $this->Auth->allow(['login','add']);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');

        $this->loadComponent('Authorization.Authorization');
        
    }

    public function isAuthorized($user = null)
    {
        // // 登録済みユーザーなら誰でも公開機能にアクセス可能です。
        // if (!$this->request->getParam('prefix')) {
        //     return true;
        // }

        // admin ユーザーだけが管理機能にアクセス可能です。
        if ($this->request->getParam('prefix') === 'Admin') {
            return (bool)($user['role'] === 'admin');
        }

        // デフォルトは拒否
        return false;
    }
}
