<?php


use Star\Test\DatabaseTestCase;

class UserTest extends DatabaseTestCase
{
    protected $user;


    public function setUp ()
    {
        parent::setUp();
        $this->user = new User();
    }



    /**
     * ログイン処理
     *
     * @group user
     * @group user-login
     * @group direct
     */
    public function testLogin ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // ユーザが間違っている場合
            $request = array(
                'username' => 'foo',
                'password' => 'foo'
            );

            $result = $this->user->login($request);
            $this->assertFalse($result['success']);
            $this->assertEquals('username or password is fail!', $result['msg']);


            //// パスワードが間違っている場合
            $request['username'] = 'test';

            $result = $this->user->login($request);
            $this->assertFalse($result['success']);
            $this->assertEquals('username or password is fail!', $result['msg']);


            // 正常なログイン処理
            $request['password'] = 'testpass';
            $request['remember'] = false;

            $result = $this->user->login($request);
            $this->assertTrue($result['success']);


            // ログイン記憶の正常ログイン
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * アカウント作成テスト
     *
     * @group user
     * @group user-register
     * @group direct
     */
    public function testRegister ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 既に登録されているユーザ名の場合
            $request = array(
                'username' => 'test',
                'password' => 'te'
            );

            $result = $this->user->register($request);
            $this->assertFalse($result['success']);
            $this->assertEquals($result['msg'], '既に登録されているユーザ名です！');


            // 正常な処理
            $request = array(
                'username' => 'register_user',
                'password' => 'register_user'
            );

            $result = $this->user->register($request);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * アカウント情報の更新
     *
     * @group user
     * @group user-update
     * @group direct
     */
    public function testUpdate ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // ユーザ名のみを更新する場合
            $request = array(
                'id' => 1,
                'username' => 'hoge',
                'password' => ''
            );

            $result = $this->user->update($request);
            $this->assertArrayHasKey('success', $result);
            $this->assertTrue($result['success']);


            // パスワードを更新する場合
            $request['password'] = 'new_pass';

            $result = $this->user->update($request);
            $this->assertArrayHasKey('success', $result);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
