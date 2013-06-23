<?php


namespace Star\Model\Table;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Model\AbstractModel;

class ListTable implements TableInterface
{
    protected $db;

    public $column;


    public function __construct ()
    {
        $this->db = \Zend_Registry::get('db');

        $container = new Container(new ModelFactory);
        $this->column = $container->get('ListColumn');
    }



    public final function insert (\stdClass $params)
    {
        try {
            foreach ($params as $key => $val) {
                if (! in_array($key, $this->column->getColumns())) {
                    throw new \Exception('invalid field '.$key);
                }
            }

            $sql = 'INSERT INTO list
                (title, url, is_folder, lft, rgt, level) VALUES
                (:title, :url, :is_folder, :lft, :rgt, :level)';

            $this->db->state($sql, $params);

        } catch (\Exception $e) {
            throw $e;
        }

        return $this->fetchById($this->db->lastInsertId());
    }



    public final function update (AbstractModel $model)
    {
        try {
            $record = $model->getRecord();

            foreach ($record as $key => $val) {
                if (! in_array($key, $this->column->getColumns())) {
                    throw new \Exception('invalid field '.$key);
                }
            }

            $sql = 'UPDATE list
                SET title = :title, url = :url,
                is_folder = :is_folder,
                lft = :lft, rgt = :rgt, level = :level
                WHERE id = :id';

            $this->db->state($sql, $record);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    public final function delete (AbstractModel $model)
    {
        
    }



    public final function fetchById ($id)
    {
        try {
            $sql = 'SELECT id, title, url,
                is_folder, lft, rgt, level
                FROM list
                WHERE id = ?';

            $result = $this->db->state($sql, $id)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定名のフォルダを取得する
     *
     * @author app2641
     **/
    public function fetchFolderByTitle ($title)
    {
        try {
            $sql = 'SELECT id, title, url, is_folder,
                lft, rgt, level FROM list
                wHERE list.is_folder = ?
                AND list.title = ?';

            $result = $this->db
                ->state($sql, array(true, $title))->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定タイトルのレコードを取得する
     *
     * @author app2641
     **/
    public function fetchByTitle ($title)
    {
        try {
            $sql = 'SELECT id, title, url, is_folder,
                lft, rgt, level FROM list
                wHERE list.title = ?';

            $result = $this->db
                ->state($sql, $title)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * リストデータを階層のない配列で取得する
     *
     * @author app2641
     **/
    public function getIndexes ()
    {
        try {
            $sql = 'SELECT id, title, url, is_folder, lft, rgt, level
                FROM list
                ORDER BY list.lft ASC';

            $results = $this->db->state($sql)->fetchAll();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }



    /**
     * ディレクトリを全取得する
     *
     * @author app2641
     **/
    public function fetchAllDirectory ($query = '')
    {
        try {
            $sql = 'SELECT id, title, url, lft, rgt, level
                FROM list
                WHERE list.is_folder = :folder
                AND list.title != :item';
            $bind = array('folder' => true, 'item' => 'すべてのアイテム');


            // クエリの有無
            if ($query != '') {
               $sql .= ' AND list.title LIKE :query';
               $bind['query'] = "%$query%";
            }


            $results = $this->db
                ->state($sql, $bind)->fetchAll();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }



    /**
     * ディレクトリではないリストを全取得する
     *
     * @author app2641
     **/
    public function fetchAllList ()
    {
        try {
            $sql = 'SELECT
                list.id, list.title, list.url,
                list.is_folder, list.lft, list.rgt, list.level
                FROM list
                WHERE list.is_folder = ?';

            $results = $this->db
                ->state($sql, false)->fetchAll();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }



    /**
     * 指定URLのレコードを取得する
     *
     * @author app2641
     **/
    public function fetchByURL ($url)
    {
        try {
            $sql = 'SELECT
                id, title, url, is_folder,
                lft, rgt, level
                FROM list
                WHERE list.url = ?';

            $result = $this->db->state($sql, $url)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定リストのフィードを取得する
     *
     * @author app2641
     **/
    public function getFeeds ($lft, $rgt)
    {
        try {
            $sql = 'SELECT
                list.id AS list_id,
                list.title AS list_title,
                feed.id AS feed_id,
                feed.url AS feed_url,
                feed.title AS feed_title,
                feed.date,
                feed.is_read,
                feed.is_star
                FROM list
                INNER JOIN feed ON feed.list_id = list.id
                WHERE list.lft >= ?
                AND list.rgt <= ?
                ORDER BY feed.date DESC
                LIMIT 0, 300';

            $results = $this->db
                ->state($sql, array($lft, $rgt))->fetchAll();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }
}
