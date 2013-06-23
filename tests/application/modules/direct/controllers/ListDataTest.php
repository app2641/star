<?php


use Star\Test\DatabaseTestCase;

use Star\Container,
    Star\Factory\ModelFactory;

class ListDataTest extends DatabaseTestCase
{
    
    protected $list, $container;

    public function setUp ()
    {
        parent::setUp();

        $this->list = new ListData();
        $this->container = new Container(new ModelFactory);
    }



    /**
     * RSSのノードを取得する
     *
     * @group list
     * @group list-getlist
     * @group direct
     */
    public function testGetList ()
    {
        $request = new \stdClass;
        $result = $this->list->getList($request);

        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
    }



    /**
     * ディレクトリのみを取得する
     *
     * @group list
     * @group list-directory
     * @group direct
     **/
    public function testFetchDirectories ()
    {
        $request = new \stdClass;

        // クエリのない場合
        $request->query = '';
        $result = $this->list->fetchDirectories($request);
        $this->assertTrue(is_array($result));
        $this->assertNotEquals(0, count($result));


        // クエリのある場合
        $request->query = 'and';
        $result = $this->list->fetchDirectories($request);
        $this->assertTrue(is_array($result));
        $this->assertNotEquals(0, count($result));
    }



    /**
     * Feed購読処理
     *
     * @group list
     * @group list-subscription
     * @group direct
     **/
    public function testSubscription ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 不正なURLの場合
            $request = array('url' => 'https://www.google.co.jps');

            $result = $this->list->subscription($request);
            $this->assertFalse($result['success']);
            $this->assertEquals('URLが正しくありません', $result['msg']);


            // RSSのURLでない場合
            $request = array('url' => 'https://www.google.co.jp');

            $result = $this->list->subscription($request);
            $this->assertFalse($result['success']);
            $this->assertEquals('URLが正しくありません', $result['msg']);


            // RSSを所持しているアドレスの場合
            $request['url'] = 'http://app2641.tumblr.com';

            $result = $this->list->subscription($request);
            $this->assertTrue($result['success']);
            $this->assertArrayHasKey('node_id', $result);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * Listを移動させる
     *
     * Listのツリー構成
     *         1{1}16
     *    _______|______
     *   |       |      |
     * 2{2}5   6{4}7  8{5}15
     *   |       _______|_______
     *   |      |       |       |
     * 3{3}4  9{6}10 11{7}12 13{8}14
     *
     * @group list
     * @group list-move1 
     * @group direct
     **/
    public function testMove1 ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 同じ親ノード内でインデックス0から2へ移動する
            $request = new \stdClass;
            $request->list_id = 6;
            $request->new_parent = 5;
            $request->old_parent = 5;
            $request->index = 2;

            $result = $this->list->move($request);
            $this->assertTrue($result['success']);

            $list_model = $this->container->get('ListModel');
            $list = $list_model->table->fetchById($request->list_id);
            $this->assertEquals(13, $list->lft);
            $this->assertEquals(14, $list->rgt);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 同じ親ノード内でインデックス2から0への移動
     *
     * @group list
     * @group list-move2
     * @group direct
     */
    public function testMove2 ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $request = new \stdClass;
            $request->list_id = 8;
            $request->new_parent = 5;
            $request->old_parent = 5;
            $request->index = 0;

            $result = $this->list->move($request);
            $this->assertTrue($result['success']);

            $list_model = $this->container->get('ListModel');
            $list = $list_model->table->fetchById($request->list_id);
            $this->assertEquals(9, $list->lft);
            $this->assertEquals(10, $list->rgt);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 違う親ノードの末端へ移動する場合
     *
     * @group list
     * @group list-move3
     * @group direct
     */
    public function testMove3 ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            $request = new \stdClass;
            $request->list_id = 3;
            $request->new_parent = 5;
            $request->old_parent = 2;
            $request->index = 3;

            $result = $this->list->move($request);
            $this->assertTrue($result['success']);

            $list_model = $this->container->get('ListModel');
            $list = $list_model->table->fetchById($request->list_id);
            $this->assertEquals(13, $list->lft);
            $this->assertEquals(14, $list->rgt);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 違う親ノードの二番目にノードを移動する
     *
     * @group list
     * @group list-move4
     * @group direct
     */
    public function testMove4 ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $request = new \stdClass;
            $request->list_id = 4;
            $request->new_parent = 5;
            $request->old_parent = 1;
            $request->index = 1;

            $result = $this->list->move($request);
            $this->assertTrue($result['success']);

            $list_model = $this->container->get('ListModel');
            $list = $list_model->table->fetchById($request->list_id);
            $this->assertEquals(9, $list->lft);
            $this->assertEquals(10, $list->rgt);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * アイテムのリネーム処理
     *
     * @group list
     * @group list-rename
     * @group direct
     */
    public function testRename ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 既にある名前のフォルダの場合
            $request = array(
                'list_id' => 2,
                'name' => 'ExtJS'
            );

            $result = $this->list->rename($request);
            $this->assertFalse($result['success']);
            $this->assertEquals('その名前のフォルダは既に使用されています', $result['msg']);


            // 既に名前のあるアイテムの場合
            $request['list_id'] = 3;

            $result = $this->list->rename($request);
            $this->assertTrue($result['success']);


            // 正常な処理
            $request['name'] = 'foo';

            $result = $this->list->rename($request);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 新しいフォルダを作成してノードを移動させる
     *
     * @group list
     * @group list-createfolder
     * @group direct
     */
    public function testCreateFolder ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 既に同じ名前のフォルダが存在する場合
            $request = array(
                'list_id' => 3,
                'name' => 'ExtJS'
            );

            $result = $this->list->createFolder($request);
            $this->assertEquals('その名前は既に使用されています', $result['msg']);


            // 正常な処理
            $request['name'] = 'New folder';
            $result = $this->list->createFolder($request);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 指定フィードを登録解除する
     *
     * @group list
     * @group list-deregister
     * @group direct
     */
    public function testDeregister ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 不正フィードを指定している場合
            $request = new \stdClass;
            $request->list_id = 200;

            $result = $this->list->deregister($request);
            $this->assertEquals('指定フィードが見つかりません', $result['msg']);


            // 正常な処理
            $request->list_id = 2;
            $result = $this->list->deregister($request);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
