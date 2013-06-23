<?php


namespace Star\Model\Column;

class FeedColumn implements ColumnInterface
{
    protected
        $columns = array(
            'id',
            'list_id',
            'url',
            'title',
            'date',
            'is_read',
            'is_star'
        );


    /**
     * テーブルのカラム情報を取得する
     *
     * @author app2641
     **/
    public function getColumns ()
    {
        return $this->columns;
    }
}
