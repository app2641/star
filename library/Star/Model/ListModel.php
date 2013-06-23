<?php


namespace Star\Model;

use Star\Container,
    Star\Factory\ModelFactory;

class ListModel extends AbstractModel
{
    public $table;


    public function __construct ()
    {
        $container = new Container(new ModelFactory);
        $this->table = $container->get('ListTable');
    }



    /**
     * 子供ノードを取得する
     *
     * @author app2641
     **/
    public function getChildNodes ($depth = null, $include = true)
    {
        $container = new Container(new ModelFactory);
        $nested = $container->get('NestedSetTable');
        return $nested->getChildNodes($this->getRecord(), $depth, $include);
    }



    /**
     * 指定リストのフィードを取得する
     *
     * @author app2641
     **/
    public function getFeeds ()
    {
        return $this->table->getFeeds($this->get('lft'), $this->get('rgt'));
    }
}
