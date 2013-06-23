<?php


namespace Star\Commands;

class init extends Base\AbstractCommand
{

    /**
     * コマンドの実行
     *
     **/
    public function execute (Array $params)
    {
        try {
            $config   = new \Zend_Config_Ini(APPLICATION_PATH.'/configs/database.ini', 'database');
            $host     = $config->db->host;
            $user     = $config->db->username;
            $password = $config->db->password;

            if ($host == '' || $user == '' || $password == '') {
                throw new \Exception('application/configs/database.iniにMySQLの設定を記載してください！');
            }


            try {
                $dsn = 'mysql:dbname=star;host='.$host;
                $db = new \PDO($dsn, $user, $password);
                throw new \Exception('既にstarデータベースは存在しています！');

            } catch (\PDOException $e) {
                try {
                    // データベースを生成する
                    $sql = "CREATE DATABASE star CHARACTER SET utf8;";
                    $command = sprintf(
                        'mysql -u %s --password="%s" --execute="%s"',
                        $config->db->username,
                        str_replace('$', '\$', $config->db->password),
                        $sql
                    );

                    exec($command);


                    // スキーマ処理
                    $command = sprintf(
                        'mysql -u %s --password="%s" star < %s',
                        $config->db->username,
                        str_replace('$', '\$', $config->db->password),
                        DATA.DS.'fixtures'.DS.'schema.sql'
                    );

                    exec($command);


                    // ルートノードの生成
                    $sql = 'INSERT INTO list
                        (title, url, is_folder, lft, rgt, level) VALUES
                        (?, ?, ?, ?, ?, ?)';
                    $bind = array(
                        'すべてのアイテム',
                        null, true, 1, 2, 0
                    );

                    $db = new \PDO($dsn, $user, $password);
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bind);

                } catch (\Exception $e) {
                    throw $e;
                }
            }

            $this->log('コマンドを実行しました！', 'success!');
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * コマンドリストに表示するヘルプメッセージを表示する
     *
     **/
    public static function help ()
    {
        /* write help message */
        $msg = 'MySQLでstarデータベースを生成します';

        return $msg;
    }
}
