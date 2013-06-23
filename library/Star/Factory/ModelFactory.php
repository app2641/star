<?php


namespace Star\Factory;

use Star\Model\FeedModel,
    Star\Model\ListModel,
    Star\Model\UserModel;

use Star\Model\Table\FeedTable,
    Star\Model\Table\ListTable,
    Star\Model\Table\NestedSetTable,
    Star\Model\Table\UserTable;

use Star\Model\Column\FeedColumn,
    Star\Model\Column\ListColumn,
    Star\Model\Column\UserColumn;

class ModelFactory extends AbstractFactory
{
    
    /////////////////////
    // Model
    /////////////////////
    
    public function buildFeedModel ()
    {
        return new FeedModel;
    }


    public function buildListModel ()
    {
        return new ListModel;
    }


    public function buildUserModel ()
    {
        return new UserModel;
    }



    /////////////////////
    // Table
    /////////////////////

    public function buildFeedTable ()
    {
        return new FeedTable;
    }


    public function buildListTable ()
    {
        return new ListTable;
    }


    public function buildNestedSetTable ()
    {
        return new NestedSetTable;
    }


    public function buildUserTable ()
    {
        return new UserTable;
    }


    /////////////////////
    // Column
    /////////////////////

    public function buildFeedColumn ()
    {
        return new FeedColumn;
    }

    
    public function buildListColumn ()
    {
        return new ListColumn;
    }


    public function buildUserColumn ()
    {
        return new UserColumn;
    }
}
