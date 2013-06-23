<?php


namespace Star\Import;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Feed;

class GoogleData implements ImportInterface
{
    public
        $file,

        $container,
        $list_model,
        $feed_model,
        $nested,
        
        $parent_id = null,

        $feed_data = array();


    public function __construct ()
    {
        $this->container = new Container(new ModelFactory);
        $this->list_model = $this->container->get('ListModel');
        $this->feed_model = $this->container->get('FeedModel');
        $this->nested = $this->container->get('NestedSetTable');
    }



    /**
     * インポート処理
     *
     * @author app2641
     **/
    public function import (array $file)
    {
        try {
            $this->file = $file;
            $this->_validateFile();

            try {
                $this->dom = new \DOMDocument();
                $this->dom->loadXML(file_get_contents($file['tmp_name']));

            } catch (\Exception $e) {
                throw new \Exception('XMLファイルが正しくありません！');
            }

            $this->_parse();

        } catch (\Exception $e) {
            return array('success' => false, 'msg' => $e->getMessage());
        }

        return array('success' => true);
    }



    /**
     * file情報のバリデート
     *
     * @author app2641
     **/
    private function _validateFile ()
    {
        // XMLファイルかどうか
        if ($this->file['type'] != 'text/xml') {
            throw new \Exception('GoogleReaderからエクスポートしたsubscriptions.xmlを指定してください！');
        }

        // subscriptions.xmlかどうか
        if ($this->file['name'] != 'subscriptions.xml') {
            throw new \Exception('GoogleReaderからエクスポートしたsubscriptions.xmlを指定してください！');
        }
    }



    /**
     * XML解析
     *
     * @author app2641
     **/
    private function _parse ()
    {
        try {
            $xpath = new \DOMXpath($this->dom);
            $categories = $xpath->query('//body/outline');

            foreach ($categories as $category) {
                if ($category->nodeName != '#text') {
                    $this->parent_id = null;

                    $this->_parseElement($category);
                    $this->_parseChildren($category);
                }
            }


            if (count($this->feed_data) > 0) {
                $this->feed_model->table->bulkInsert($this->feed_data);
            }
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 子供要素を解析する
     *
     * @author app2641
     **/
    private function _parseChildren ($parent)
    {
        if ($parent->hasChildNodes()) {
            foreach ($parent->childNodes as $child) {
                if ($child->nodeName != '#text') {
                    $this->_parseElement($child);
                }
            }
        }
    }



    /**
     * DOM要素を解析する
     *
     * @author app2641
     **/
    private function _parseElement ($element)
    {
        $title = $element->getAttribute('title');

        // タイトルのないものは除外
        if ($title == '') {
            return false;
        }


        $url = $element->getAttribute('xmlUrl');
        $root = $this->nested->fetchRootNode();

        if ($url == '') {
            // フォルダ処理
            $folder = $this->list_model->table->fetchFolderByTitle($title);

            if (! $folder) {
                $params = new \stdClass;
                $params->title = $title;
                $params->url   = null;
                $params->is_folder = true;
                $params->lft = null;
                $params->rgt = null;
                $params->level = null;

                $node = $this->list_model->insert($params);

                $parent_node = $this->nested->insertAsLastChildOf($node, $root);
                $this->parent_id = $parent_node->id;

            } else {
                $this->parent_id = $folder->id;
            }


        } else {
            // ノード処理
            $params = new \stdClass;
            $params->title = $title;
            $params->url   = $url;
            $params->is_folder = false;
            $params->lft = null;
            $params->rgt = null;
            $params->level = null;

            $node = $this->list_model->insert($params);

            if (is_null($this->parent_id)) {
                $this->nested->insertAsLastChildOf($node, $root);
            } else {
                $parent = $this->list_model->table->fetchById($this->parent_id);
                $this->nested->insertAsLastChildOf($node, $parent);
            }


            // Feedの登録
            try {
                $feed = new Feed();
                $feed->init($url);
                $feed->load();

                $items = $feed->fetchItems();
                foreach ($items as $item) {
                    $params = new \stdClass;
                    $params->list_id = $node->id;
                    $params->url = $item->get_permalink();
                    $params->title = $item->get_title();
                    $params->date = $item->get_date('Y-m-d H:i:s');

                    $this->feed_data[] = $params;
                }


                if (count($this->feed_data) > 300) {
                    $this->feed_model->table->bulkInsert($this->feed_data);
                    $this->feed_data = array();
                }

            } catch (\Exception $e) {
            }

            unset($feed);
        }
    }
}
