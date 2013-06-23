<?php


namespace Star\Model\Column;

class UserColumn implements ColumnInterface
{
    protected
        $columns = array(
            'id',
            'name',
            'password',
            'salt',
            'auto_signin_key',
            'api_key',
            'created_date',
            'updated_date'
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
