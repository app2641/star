<?php


namespace Star\Model;

use Star\Container,
    Star\Factory\ModelFactory;

class UserModel extends AbstractModel
{
    public $table;


    public function __construct ()
    {
        $container = new Container(new ModelFactory);
        $this->table = $container->get('UserTable');
    }



    /**
     * パスワードを更新する
     *
     * @author app2641
     **/
    public function updatePassword ()
    {
        $this->table->updatePassword($this);
    }
}
