<?php


use Star\Container,
    Star\Factory\ModelFactory;

class Feed
{
    protected
        $container;

    public function __construct ()
    {
        $this->container = new Container(new ModelFactory);
    }


    
    /**
     * 指定リストのフィードを取得する
     *
     * @author app2641
     **/
    public function getList ($request)
    {
        $list_model = $this->container->get('ListModel');
        $list_model->fetchById($request->list_id);

        $results = $list_model->getFeeds();

        return array(
            'contents' => $results
        );
    }



    /**
     * フィードの既読フラグを立てる
     *
     * @author app2641
     **/
    public function readable ($request)
    {
        $feed_model = $this->container->get('FeedModel');
        $feed_model->fetchById($request->feed_id);

        if ($feed_model->hasRecord()) {
            $feed_model->set('is_read', true);
            $feed_model->update();
        
        } else {
            return array('success' => false, 'msg' => 'エラーが発生しました');
        }

        return array('success' => true);
    }
}
