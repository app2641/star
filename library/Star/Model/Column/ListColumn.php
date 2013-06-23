<?php


namespace Star\Model\Column;

class ListColumn implements ColumnInterface
{
    protected
        $columns = array(
            'id',
            'title',
            'url',
            'is_folder',
            'lft',
            'rgt',
            'level'
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
