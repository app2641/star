<?php


use Star\Test\DatabaseTestCase;

use Star\Container,
    Star\Factory\ModelFactory;

use Star\Import\GoogleData;

class GoogleDataTest extends DatabaseTestCase
{
    public $g_import;

    private $path = '/tmp/star_subscription.xml';
    private $header = '<?xml version="1.0" encoding="UTF-8" ?>';


    public function setUp ()
    {
        parent::setUp();

        $this->g_import = new GoogleData();
    }



    /**
     * GoogleReaderデータインポート処理のテスト
     *
     * @group g_import
     * $group lib
     **/
    public function testImport ()
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();

            // GoogleReader実データをインポートするフラグ
            $google_import_flag = false;

        
            // XMLファイルでない場合
            $file = array('type' => 'text/plain');
            $result = $this->g_import->import($file);

            $this->assertFalse($result['success']);
            $this->assertEquals($result['msg'], 'GoogleReaderからエクスポートしたsubscriptions.xmlを指定してください！');


            // ファイル名がsubscriptions.xmlでない場合
            $file['name'] = 'foo.xml';
            $result = $this->g_import->import($file);

            $this->assertFalse($result['success']);
            $this->assertEquals($result['msg'], 'GoogleReaderからエクスポートしたsubscriptions.xmlを指定してください！');


            // XMLが壊れている場合
            $this->_buildXML($this->header.'<a>test</b>');
            $file = array(
                'name' => 'subscriptions.xml',
                'type' => 'text/xml',
                'tmp_name' => $this->path
            );

            $result = $this->g_import->import($file);
            $this->assertFalse($result['success']);
            $this->assertEquals($result['msg'], 'XMLファイルが正しくありません！');


            // 正常な処理
            $xml_path = ROOT_PATH.DS.'data'.DS.'reader'.DS.'app2641@gmail.com-takeout'.DS.'リーダー'.DS.'subscriptions.xml';
            if (file_exists($xml_path) && $google_import_flag == true) {
                $this->_buildXML(file_get_contents($xml_path));
            } else {
                $this->_buildXML($this->header.'<body>'.
                    '<outline title="foo"><outline title="bar" xmlUrl="http://app2641.tumblr.com/rss"/></outline>'.
                '</body>');
            }

            $result = $this->g_import->import($file);
            $this->assertTrue($result['success']);
        
            $db->rollBack();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * /tmpディレクトリにxmlを生成する
     *
     * @author app2641
     **/
    private function _buildXML ($xml)
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }


        touch($this->path);
        chmod($this->path, 0777);

        file_put_contents($this->path, $xml);
    }
}
