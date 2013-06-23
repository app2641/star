<?php


namespace Star\Model;

use Star\Container,
    Star\Factory\ModelFactory;

class FeedModel extends AbstractModel
{
    public $table;


    public function __construct ()
    {
        $container = new Container(new ModelFactory);
        $this->table = $container->get('FeedTable');
    }
}
