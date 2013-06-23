<?php


use Star\Container,
    Star\Factory\ModelFactory;

use Star\Import\GoogleData;

use Star\Feed;

class ListData
{

    public function __construct ()
    {
        $this->container = new Container(new ModelFactory);
    }


    /**
     * GoogleReaderデータのインポート処理
     *
     * @formHandler
     * @author app2641
     **/
    public function dataImport ($request)
    {
        try {
            set_time_limit(0);

            $g_import = new GoogleData();
            return $g_import->import($_FILES['file']);
        
        } catch (\Exception $e) {
            return array('success' => false, 'msg' => $e->getMessage());
        }
    }



    /**
     * リストを取得する
     *
     * @author app2641
     **/
    public function getList ($request)
    {
        $list_table = $this->container->get('ListTable');
        $nested = $this->container->get('NestedSetTable');

        $indexes = $list_table->getIndexes();
        $list = $nested->buildTree($indexes);

        // ルートディレクトリのみexpand
        $list[0]->expanded = true;

        return $list;
    }



    /**
     * ディレクトリを取得する
     *
     * @author app2641
     **/
    public function fetchDirectories ($request)
    {
        $query = trim(mb_convert_kana($request->query, 'a'));

        $list_table = $this->container->get('ListTable');
        $directories = $list_table->fetchAllDirectory($query);

        return $directories;
    }



    /**
     * RSSの登録処理
     *
     * @formHandler
     * @author app2641
     **/
    public function subscription ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            $url = $request['url'];
            $directory = (isset($request['directory'])) ? $request['directory']: null;


            // 指定URLが正しいRSSのURLかを判断
            $feed  = new Feed();
            $feed->init($url);
            $feed->load();

            // リストの登録
            $list_model = $this->container->get('ListModel');
            $params = new \stdClass;
            $params->title = $feed->getTitle();
            $params->url = $feed->getURL();
            $params->is_folder = false;
            $params->lft = null;
            $params->rgt = null;
            $params->level = null;

            $list_model->insert($params);


            // ツリーに追加
            $nested = $this->container->get('NestedSetTable');

            // ディレクトリの指定があったかどうか
            if (is_null($directory)) {
                $root = $nested->fetchRootNode();
            } else {
                $root = $list_model->table->fetchById($directory);
            }
            $nested->insertAsLastChildOf($list_model->getRecord(), $root);


            // Feedのアイテムを登録する
            $items = $feed->fetchItems();
            $feed_model = $this->container->get('FeedModel');
            foreach ($items as $item) {
                $params = new \stdClass;
                $params->list_id = $list_model->get('id');
                $params->url = $item->get_permalink();
                $params->title = $item->get_title();
                $params->is_star = false;
                $params->date = $item->get_date('Y-m-d H:i:s');

                $feed_model->insert($params);
            }

            unset($feed);
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true, 'node_id' => $list_model->get('id'));
    }



    /**
     * Listの移動処理
     *
     * @author app2641
     **/
    public function move ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $list_id = $request->list_id;
            $new_parent = $request->new_parent;
            $old_parent = $request->old_parent;
            $index = $request->index;

            $nested = $this->container->get('NestedSetTable');
            $list_model = $this->container->get('ListModel');
            $parent_model = clone $list_model;
            $list_model->fetchById($list_id);

            // 移動先と移動元の親が同じかどうか
            if ($new_parent == $old_parent) {
                $parent_model->fetchById($new_parent);
                $indexes = $parent_model->getChildNodes(1, false);

                // index比較用に$indexesを格納する
                $node_indexes = array();
                $index_ = $index;
                foreach ($indexes as $key => $val) {
                    $node_indexes[$val->id] = $key;
                }

                // 移動先indexと移動元indexを比較する
                if ($node_indexes[$list_model->get('id')] > $index) {
                    // 移動させるノードのほうがindexが大きければ$indexをひとつ減らす
                    $index_ -= 1;
                }


                if ($index == 0) {
                    // 1番目の兄弟ノードの前にノードを移動させる
                    $nested->moveAsPrevSiblingOf($list_model->getRecord(), $indexes[$index]);
                } else {
                    // $index番目の兄弟ノードの後ろにノードを移動させる
                    $nested->moveAsNextSiblingOf($list_model->getRecord(), $indexes[$index_]);
                }
            
            } else {
                $parent_model->fetchById($new_parent);
                $indexes = $parent_model->getChildNodes(1, false);

                if ($index == 0) {
                    if (count($indexes) == 0) {
                        $nested->moveAsFirstChildOf($list_model->getRecord(), $parent_model->getRecord());
                    } else {
                        $nested->moveAsPrevSiblingOf($list_model->getRecord(), $indexes[0]);
                    }
                } else {
                    $nested->moveAsNextSiblingOf($list_model->getRecord(), $indexes[$index - 1]);
                }
            }
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * アイテムのリネーム処理
     *
     * @formHandler
     * @author app2641
     **/
    public function rename ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $list_model = $this->container->get('ListModel');
            $list_model->fetchById($request['list_id']);

            // 既に名前が存在しているかを確認する
            $list = $list_model->table->fetchFolderByTitle($request['name']);
            if ($list && $list_model->get('is_folder') == '1') {
                throw new \Exception('その名前のフォルダは既に使用されています');
            }

            $list_model->fetchById($request['list_id']);
            $list_model->set('title', $request['name']);
            $list_model->update();
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * フォルダを新規作成してノードを移動させる
     *
     * @formHandler
     * @author app2641
     **/
    public function createFolder ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $list_model = $this->container->get('ListModel');
            $list = $list_model->table->fetchByTitle($request['name']);

            if ($list) {
                throw new \Exception('その名前は既に使用されています');
            }


            $params = new \stdClass;
            $params->title = $request['name'];
            $params->url = null;
            $params->is_folder = true;
            $params->lft = null;
            $params->rgt = null;
            $params->level = null;
            $list_model->insert($params);

            $nested = $this->container->get('NestedSetTable');
            $node   = $list_model->table->fetchById($request['list_id']);

            // 新しいフォルダをツリーに追加する
            $root = $nested->fetchRootNode();
            $nested->insertAsLastChildOf($list_model->getRecord(), $root);

            // 作成したフォルダに指定ノードを移動させる
            $nested->insertAsLastChildOf($node, $list_model->getRecord());
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * 指定フィードを登録解除する
     *
     * @author app2641
     **/
    public function Deregister ($request)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $list_table = $this->container->get('ListTable');
            $list = $list_table->fetchById($request->list_id);

            if (! $list) {
                throw new \Exception('指定フィードが見つかりません');
            }

            $nested = $this->container->get('NestedSetTable');
            $nested->deleteTreeNode($list);
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }
}
