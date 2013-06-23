<?php


use Star\Container,
    Star\Factory\ModelFactory;

class User
{

    protected
        $container;

    public function __construct ()
    {
        $this->container = new Container(new ModelFactory);
    }


    /**
     * ログイン処理
     *
     * @formHandler
     */
    public function login ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            $auth = \Zend_Registry::get('auth');
            $user = $auth->validate($request['username'], $request['password']);

            if ($user) {
                $user_model = $this->container->get('UserModel');
                $login_user = clone $user;
                $user_model->setRecord($user);

                // ログイン記憶をチェックしているかどうか
                if (isset($request['remember'])) {
                
                }

                $auth->setStorage($login_user);

            } else {
                throw new \Exception('username or password is fail!');
            }
            
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * ログアウト処理
     *
     **/
    public function logout ($request)
    {
        $auth = \Zend_Registry::get('auth');
        $auth->clearStorage();

        if (isset($_COKKIE[REMEMBER_KEY])) {
            setcookie(REMEMBER_KEY, '', time() - 3600);
        }

        return array('success' => true);
    }



    /**
     * アカウント作成処理
     *
     * @formHandler
     * @author app2641
     **/
    public function register ($request)
    {
        try {
            $username = $request['username'];
            $password = $request['password'];

            $container  = new Container(new ModelFactory);
            $user_model = $container->get('UserModel');

            $user = $user_model->table->fetchByName($username);
            if ($user) {
                throw new \Exception('既に登録されているユーザ名です！');
            }


            // ユーザの登録
            $salt = md5(uniqid(rand()));
            $sha_pass = sha1($salt.$password);
            $api_key = substr(md5(uniqid(rand())), 0, 8);

            $params = new \stdClass;
            $params->name     = $username;
            $params->password = $sha_pass;
            $params->salt     = $salt;
            $params->api_key  = $api_key;
            $params->created_date = date('Y-m-d H:i:s');

            $user_model->insert($params);

        } catch (\Exception $e) {
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * アカウント設定フォームのロード処理
     *
     * @author app2641
     **/
    public function getAccountData ($request)
    {
        $auth = \Zend_Registry::get('auth');
        $data = $auth->getStorage();

        return array(
            'success' => true,
            'id' => $data->id,
            'name' => $data->name
        );
    }



    /**
     * ユーザ情報更新処理
     *
     * @formHandler
     * @author app2641
     **/
    public function update ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();


            $user_model = $this->container->get('UserModel');
            $user_model->fetchById($request['id']);

            $user_model->set('name', $request['username']);
            $user_model->set('updated_date', date('Y-m-d H:i:s'));
            $user_model->update();

        
            $password = $request['password'];
            if ($password != '') {
                $salt = md5(uniqid(rand()));
                $sha_pass = sha1($salt.$password);

                $user_model->set('salt', $salt);
                $user_model->set('password', $sha_pass);
                $user_model->updatePassword();

                $auth = \Zend_Registry::get('auth');
                $user = $auth->validate($user_model->get('name'), $password);
                $auth->setStorage($user);
            }
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }
}
