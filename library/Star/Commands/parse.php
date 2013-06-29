<?php


namespace Star\Commands;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Feed;

class parse extends Base\AbstractCommand
{
    protected
        $feed_data = array();


    /**
     * コマンドの実行
     *
     **/
    public function execute (Array $params)
    {
        try {
            set_time_limit(0);
            $this->initDatabaseConnection();

            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            $this->log(date('Y-m-d H:i:s'), 'start');
            
            $container = new Container(new ModelFactory);
            $list_model = $container->get('ListModel');
            $feed_table = $container->get('FeedTable');

            // 対象リストを取得
            $list_data = $list_model->table->fetchAllList();

            foreach ($list_data as $list) {
                // RSSをFeedクラスに読み込ませる
                try {
                    $feed = new Feed();
                    $feed->init($list->url);
                    $feed->load();
                } catch (\Exception $e) {
                    continue;
                }

                $items = $feed->fetchItems();
                foreach ($items as $item) {
                    // url取得
                    $url = $item->get_permalink();

                    // 取得したurlがDBに登録済みかを確認
                    $entry = $feed_table->fetchByFeed($list->id, $url);

                    if ($entry) {
                        // 既に登録済みのエントリになったら解析中断
                        unset($entry);
                        continue;

                    } else {
                        $params = new \stdClass;
                        $params->list_id = $list->id;
                        $params->url = $url;
                        $params->title = $item->get_title();
                        $params->date = $item->get_date('Y-m-d H:i:s');

                        $this->feed_data[] = $params;
                    }


                    if (count($this->feed_data) > 30) {
                        $feed_table->bulkInsert($this->feed_data);
                        $this->feed_data = array();
                    }
                }

                unset($feed, $items);
            }


            if (count($this->feed_data) > 100) {
                $feed_table->bulkInsert($this->feed_data);
                $this->feed_data = array();
            }


            // 500エントリあるものは削除
            foreach ($list_data as $list) {
                $feed = $feed_table->fetchLimitFeed(500, $list->id);

                if ($feed) {
                    $feed_table->deleteOverFeeds($list->id, $feed->id);
                }
            }
            
            $db->commit();

            $this->log(date('Y-m-d H:i:s'), 'end');
            $this->log('parse success!');
            
        } catch (\Exception $e) {
            $db->rollBack();
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * コマンドリストに表示するヘルプメッセージを表示する
     *
     **/
    public static function help ()
    {
        $msg = 'cronでRSSフィードを取得する';

        return $msg;
    }
}
