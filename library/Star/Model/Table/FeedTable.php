<?php


namespace Star\Model\Table;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Model\AbstractModel;

class FeedTable implements TableInterface
{
    protected $db;

    public $column;


    public function __construct ()
    {
        $this->db = \Zend_Registry::get('db');

        $container = new Container(new ModelFactory);
        $this->column = $container->get('FeedColumn');
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

            $sql = 'INSERT INTO feed
                (list_id, url, title, date, is_star) VALUES
                (:list_id, :url, :title, :date, :is_star)';

            $this->db->state($sql, $params);

        } catch (\Exception $e) {
            throw $e;
        }

        return $this->fetchById($this->db->lastInsertId());
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

            $sql = 'UPDATE feed
                SET list_id = :list_id,
                    url = :url,
                    title = :title,
                    date = :date,
                    is_read = :is_read,
                    is_star = :is_star
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
                id, list_id, url, title, date, is_read, is_star
                from feed
                where feed.id = ?';

            $result = $this->db->state($sql, $id)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定list_idと指定urlから該当エントリを取得する
     *
     * @author app2641
     **/
    public function fetchByFeed ($list_id, $url)
    {
        try {
            $sql = 'SELECT
                feed.id, feed.list_id, feed.url, feed.title,
                feed.date, feed.is_read, feed.is_star
                FROM feed
                WHERE feed.list_id = ?
                AND feed.url = ?';

            $result = $this->db
                ->state($sql, array($list_id, $url))->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定リミットからのFeedを取得する
     *
     * @author app2641
     **/
    public function fetchLimitFeed ($limit, $list_id)
    {
        try {
            $sql = 'SELECT feed.id
                FROM feed
                WHERE list_id = ?
                LIMIT '.$limit.', 1';

            $result = $this->db
                ->state($sql, $list_id)->fetch();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定idより小さなFeedをすべて削除する
     *
     * @author app2641
     **/
    public function deleteOverFeeds ($list_id, $feed_id)
    {
        try {
            $sql = 'DELETE FROM feed
                WHERE feed.id < ?
                AND feed.list_id = ?';

            $this->db
                ->state($sql, array($feed_id, $list_id));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * フィードをバルクインサート
     *
     * @author app2641
     **/
    public function bulkInsert ($data)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            $sql = 'INSERT INTO feed
                (list_id, title, url, date)
                VALUES ';
            $bind = array();
        
            foreach ($data as $key => $params) {
                $sql .= "(:list_id$key, :title$key,
                    :url$key, :date$key), ";

                $bind = array_merge($bind, array(
                    "list_id$key" => $params->list_id,
                    "title$key" => $params->title,
                    "url$key" => $params->url,
                    "date$key" => $params->date
                ));
            }

            // sql末尾のコンマとスペースを削除
            $sql = substr($sql, 0, strlen($sql) - 2);
            $db->state($sql, $bind);
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
