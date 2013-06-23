<?php


namespace Star;

use Star\Container,
    Star\Factory\ModelFactory;

class Feed
{
    private
        $feed,
        $url,
        
        $title, $link;


    public function __construct ()
    {
        require_once ROOT_PATH.DS.'library'.DS.'SimplePie'.DS.'SimplePie.compiled.php';
        require_once ROOT_PATH.DS.'library'.DS.'SimplePie'.DS.'idn'.DS.'idna_convert.class.php';

        $this->feed = new \SimplePie();
    }



    /**
     * URLを取得する
     *
     * @author app2641
     **/
    public function getURL ()
    {
        return $this->url;
    }



    /**
     * タイトルを取得する
     *
     * @author app2641
     **/
    public function getTitle ()
    {
        return $this->title;
    }



    /**
     * サイトのURLを取得する
     *
     * @author app2641
     **/
    public function getLink ()
    {
        return $this->link;
    }



    /**
     * 初期化
     *
     * @author app2641
     **/
    public function init ($url)
    {
        $this->url = $url;
        $this->_validateURL();
    }



    /**
     * RSSを読み込む
     *
     * @author app2641
     **/
    public function load ()
    {
        $this->feed->handle_content_type();

        $this->title = $this->feed->get_title();
        $this->link  = $this->feed->get_link();
        $this->url   = $this->feed->feed_url;
    }



    /**
     * URLの妥当性を調べる
     *
     * @author app2641
     **/
    private function _validateURL ()
    {
        try {
            // 実在するURLかどうか
            $fp = fopen($this->url, 'r');
            $data = fgets($fp);
            fclose($fp);
        
        } catch (\Exception $e) {
            throw new \Exception('URLが正しくありません');
        }


        try {
            $this->feed->set_feed_url($this->url);
            $this->feed->set_cache_location(ROOT_PATH.DS.'library'.DS.'SimplePie'.DS.'cache');
            $this->feed->set_cache_duration(1800);
            $success = $this->feed->init();

            if ($success === false) {
                throw new \Exception('URLが正しくありません');
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * FeedItemを取得する
     *
     * @author app2641
     **/
    public function fetchItems ()
    {
        return $this->feed->get_items();
    }
}
