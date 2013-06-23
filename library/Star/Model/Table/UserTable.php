<?php


namespace Star\Model\Table;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Model\AbstractModel,
    Star\Model\UserModel;

class UserTable implements TableInterface
{
    protected $db;

    public $column;


    public function __construct ()
    {
        $this->db = \Zend_Registry::get('db');

        $container = new Container(new ModelFactory);
        $this->column = $container->get('UserColumn');
    }



    /**
     * レコードを新規作成する
     *
     * @author app2641
     **/
    public final function insert (\stdClass $params)
    {
        try {
            foreach ($params as $key => $val) {
                if (! in_array($key, $this->column->getColumns())) {
                    throw new \Exception('invalid column '.$key);
                }
            }

            $sql = 'INSERT INTO user
                (name, salt, password, api_key, created_date) VALUES
                (:name, :salt, :password, :api_key, :created_date)';

            $this->db->state($sql, $params);

        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * レコードを更新する
     *
     * @author app2641
     **/
    public final function update (AbstractModel $model)
    {
        try {
            $record = $model->getRecord();

            foreach ($record as $key => $val) {
                if (! in_array($key, $this->column->getColumns())) {
                    throw new \Exception('invalid column!');
                }
            }

            $sql = 'UPDATE user
                SET name = :name,
                    auto_signin_key = :auto_signin_key,
                    updated_date = :updated_date
                WHERE id = :id';

            $this->db->state($sql, $record);

        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * レコードを削除する
     *
     * @author app2641
     **/
    public final function delete (AbstractModel $model)
    {
    }



    public final function fetchById ($id)
    {
        try {
            $sql = 'select 
                id, name, auto_signin_key
                from user
                where user.id = ?';

            $result = $this->db->state($sql, $id)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定usernameからレコードを取得する
     *
     * @author app2641
     **/
    public function fetchByName ($name)
    {
        try {
            $sql = 'select
                id, name, salt, password
                from user where name = ?';

            $result = $this->db->state($sql, $name)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * パスワードを更新する
     *
     * @author app2641
     **/
    public function updatePassword (UserModel $model)
    {
        try {
            $id = $model->get('id');
            $salt = $model->get('salt');
            $pass = $model->get('password');

            $sql = 'UPDATE user
                SET salt = :salt,
                    password = :password
                WHERE id = :id';
            $bind = array(
                'id' => $id,
                'salt' => $salt,
                'password' => $pass
            );

            $this->db->state($sql, $bind);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
