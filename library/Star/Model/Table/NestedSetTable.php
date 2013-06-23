<?php


namespace Star\Model\Table;

use Star\Container,
    Star\Factory\ModelFactory;

class NestedSetTable
{
    protected $db, $container;


    public function __construct ()
    {
        $this->db = \Zend_Registry::get('db');
        $this->container = new Container(new ModelFactory);
    }



    /**
     * ルートノードを取得する
     *
     * @author app2641
     **/
    public function fetchRootNode ()
    {
        try {
            $sql = 'SELECT id, title, url, is_folder,
                lft, rgt, level FROM list
                WHERE lft = :lft AND level = :level AND is_folder = :folder';
            $bind = array(
                'lft' => 1,
                'level' => 0,
                'folder' => true
            );

            $result = $this->db->state($sql, $bind)->fetch();

            // ルートノードが取得出来ない場合は生成する
            if (! $result) {
                $model = $this->container->get('ListModel');

                $params = new \stdClass;
                $params->title = 'すべてのアイテム';
                $params->url = null;
                $params->is_folder = true;
                $params->lft = 1;
                $params->rgt = 2;
                $params->level = 0;

                $result = $model->insert($params);
            }
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }



    /**
     * 指定ノードを指定親ノードの最初の子供にする
     *
     * @author app2641
     **/
    public function moveAsFirstChildOf ($node, $parent)
    {
        try {
            $old_level   = $node->level;
            $node->level = $parent->level + 1;
            $this->_updateNode($node, $parent->lft + 1, ($node->level - $old_level));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 指定ノードを指定兄弟ノードの前に移動させる
     *
     * @author app2641
     **/
    public function moveAsPrevSiblingOf ($node, $target)
    {
        try {
            $old_level = $node->level;
            $node->level = $target->level;
            $this->_updateNode($node, $target->lft, ($node->level - $old_level));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 指定ノードを指定兄弟ノードの後ろに移動させる
     *
     * @author app2641
     **/
    public function moveAsNextSiblingOf ($node, $target)
    {
        try {
            $old_level = $node->level;
            $node->level = $target->level;
            $this->_updateNode($node, $target->rgt + 1, ($node->level - $old_level));

        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * ノード情報を更新する
     *
     * @author app2641
     **/
    private function _updateNode ($node, $left, $diff)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $tree_size = $node->rgt - $node->lft + 1;
            $this->_shiftNodeValues($left, $tree_size);


            if ($node->lft >= $left) {
                $node->lft += $tree_size;
                $node->rgt += $tree_size;
            }


            // 指定lft, rgt範囲内のlevel値を更新する
            $sql = 'UPDATE list
                SET level = level + :level
                WHERE list.lft > :lft
                AND list.rgt < :rgt';

            $bind = array(
                'level' => ($node->level + 1),
                'lft' => $node->lft,
                'rgt' => $node->rgt
            );

            $db->state($sql, $bind);


            $this->_shiftNodeRanges(
                $node->lft, $node->rgt,
                ($left - $node->lft)
            );

            $this->_shiftNodeValues($node->rgt + 1, - $tree_size);

            $sql = "UPDATE list
                SET list.level = ?
                WHERE list.id = ?";
            $db->state($sql, array($node->level, $node->id));

            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }



    /**
     * 指定ノードを指定親ノードの最後の子供として追加する
     *
     * @author app2641
     **/
    public function insertAsLastChildOf ($node, $parent)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            // lft, rgt値をセットする
            $node->lft   = $parent->rgt;
            $node->rgt   = $parent->rgt + 1;
            $node->level = $parent->level + 1;

            $this->_shiftNodeValues($node->lft, 2);


            $list_model = $this->container->get('ListModel');
            $list_model->setRecord($node);
            $list_model->update();

            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }

        return $list_model->getRecord();
    }



    /**
     * lft, rgt値を更新する
     *
     * @author app2641
     **/
    private function _shiftNodeValues ($value, $index)
    {
        try {
            // lft値を更新する
            $sql = 'UPDATE list
                SET lft = (lft + ?)
                WHERE lft >= ?';

            $this->db->state($sql, array($index, $value));


            // rgt値を更新する
            $sql = 'UPDATE list
                SET rgt = (rgt + ?)
                WHERE rgt >= ?';

            $this->db->state($sql, array($index, $value));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    public function _shiftNodeRanges ($index, $last, $add)
    {
        try {
            $sql = 'UPDATE list
                SET list.lft = lft + ?
                WHERE list.lft >= ?
                AND list.lft <= ?';

            $this->db->state($sql, array($add, $index, $last));


            $sql = 'UPDATE list
                SET list.rgt = rgt + ?
                WHERE list.rgt >= ?
                AND list.rgt <= ?';

            $this->db->state($sql, array($add, $index, $last));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 渡されたインデックスを階層のある目次で構築する
     *
     * @author app2641
     **/
    public function buildTree ($indexes)
    {
        // 階層付きインデックスを格納する
        $list = array();

        // インデックスの一時格納先
        $stack = array();

        foreach ($indexes as $index) {
            $index->text = $index->title;

            // 子供を持つノードか末端ノードかどうか
            if ($index->rgt - $index->lft != 1 || $index->is_folder == true) {
                $index->expanded = false;
                $index->children = array();

            } else {
                $index->iconCls = 'list-feed-item-icon';
                $index->leaf = true;
            }

            // 不要なプロパティを削除する
            unset($index->title, $index->lft,
                $index->rgt, $index->url);


            $stack_level = count($stack);

            while ($stack_level > 0 && $stack[$stack_level - 1]->level >= $index->level) {
                array_pop($stack);
                $stack_level--;
            }


            if ($stack_level == 0) {
                $i = count($list);

                $list[$i] = $index;
                $stack[] =& $list[$i];

            } else {
                if (!isset($stack[$stack_level - 1]->children)) {
                    throw new \Exception('invalid index!');
                }

                $i = count($stack[$stack_level - 1]->children);

                $stack[$stack_level - 1]->children[$i] = $index;
                $stack[] =& $stack[$stack_level - 1]->children[$i];
            }
        }

        return $list;
    }



    /**
     * 指定ノードの子供ノードを取得する
     *
     * @author app2641
     **/
    public function getChildNodes ($node, $depth = null, $include = true)
    {
        try {
            $sql = 'SELECT
                id, url, title, is_folder,
                lft, rgt, level
                FROM list ';

            // $includeの有無
            if ($include) {
                $sql .= 'WHERE list.lft >= :lft
                    AND list.rgt <= :rgt
                    %s ORDER BY list.lft ASC';
            } else {
                $sql .= 'WHERE list.lft > :lft
                    AND list.rgt < :rgt
                    %s ORDER BY list.lft ASC';
            }

            // $depthの有無
            if (is_null($depth)) {
                $sql = sprintf($sql, '');
            } else {
                $sql = sprintf(
                    $sql, sprintf(
                        'AND list.level <= %s',
                        $node->level + $depth
                    )
                );
            }


            $bind = array(
                'lft' => $node->lft,
                'rgt' => $node->rgt
            );

            $results = $this->db->state($sql, $bind)->fetchAll();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $results;
    }



    /**
     * 指定ノードを削除する
     *
     * @author app2641
     **/
    public function deleteTreeNode ($node)
    {
        try {
            $db = \Zend_Registry::get('db');
            $db->beginTransaction();
        
            $sql = 'DELETE FROM list
                WHERE lft >= :lft
                AND rgt <= :rgt';

            $bind = array(
                'lft' => $node->lft,
                'rgt' => $node->rgt
            );

            $db->state($sql, $bind);

            // 目次のlft, rgt値を最適化する
            $this->_shiftNodeValues($node->rgt + 1, $node->lft - $node->rgt - 1);
        
            $db->commit();
        
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
