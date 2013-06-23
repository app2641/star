<?php


use Star\Test\DatabaseTestCase;

use Star\Container,
    Star\Factory\ModelFactory;

class FeedTest extends DatabaseTestCase
{
    protected
        $feed, $container;


    public function setUp ()
    {
        parent::setUp();

        $this->feed = new Feed();
        $this->container = new Container(new ModelFactory);
    }



    /**
     * フィードを取得する
     *
     * @group feed
     * @group feed-list
     * @group direct
     **/
    public function testGetList ()
    {
        // 単体フィードの読み込み
        $request = new \stdClass;
        $request->list_id = 3;

        $result = $this->feed->getList($request);
        $this->assertArrayHasKey('contents', $result);


        // すべてのアイテム読み込み
        $request->list_id = 1;

        $result = $this->feed->getList($request);
        $this->assertArrayHasKey('contents', $result);
    }



    /**
     * 既読フラグを立てる
     *
     * @group feed
     * @group feed-readable
     * @group direct
     **/
    public function testReadable ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // 存在しないレコードの場合
            $request = new \stdClass;
            $request->feed_id = 'あいうえお';

            $result = $this->feed->readable($request);
            $this->assertEquals('エラーが発生しました', $result['msg']);


            // 正常な処理 
            $request->feed_id = 3;

            $result = $this->feed->readable($request);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
