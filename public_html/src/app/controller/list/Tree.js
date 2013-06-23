/**
 * STAR.controller.list.Tree
 *
 * @author app2641
 **/
Ext.define('STAR.controller.list.Tree', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'tree', selector: 'list-Tree'
    }, {
        ref: 'ImportWindow', selector: 'import-Window'
    }, {
        ref: 'SubscriptionWindow', selector: 'subscription-Window'
    }],


    init: function () {
        var me = this;

        me.control({
            'list-Tree': {
                load: function () {
                    var tree = me.getTree();

                    if (tree.getLastSelected() === null) {
                        var node = tree.getRootNode().firstChild;
                        tree.getSelectionModel().select(node);
                    }
                },


                // level1以上のノードには移動をさせない
                beforeitemmove: function (node, old_parent, new_parent, index, opt) {
                    if (new_parent.raw.level !== '0' && node.raw.is_folder === '1') {
                        return false;
                    }
                },


                // ノードの移動
                itemmove: function (node, old_parent, new_parent, index, opt) {
                    ListData.move({
                        list_id: node.raw.id,
                        old_parent: old_parent.raw.id,
                        new_parent: new_parent.raw.id,
                        index: index
                    }, function (response) {
                        if (response.success) {

                        } else {
                            STAR.Events.showInfoWindow(response.msg);
                        }
                    });
                },


                // コンテキストメニューの表示
                itemcontextmenu: function (tree, node, el, index, e, opt) {
                    if (node.raw.level == '0') {
                        return false;
                    }

                    var menu = me.getTree().buildContextMenu(node);
                    e.stopEvent();
                    menu.showAt(e.getXY());
                    me.getTree().getSelectionModel().select(node);
                },


                // リネーム
                itemrename: me.itemRename,

                // フォルダを作成して移動
                createfolder: me.createFolder,

                // 登録解除
                itemderegister: me.itemDeregister,

                // ストアのリロードとノードの再選択
                reloadandreselect: me.reloadAndReselect
            },


            'import-Window': {
                // インポート処理が正常に完了した時の処理
                successsubmit: function () {
                    me.getImportWindow().close();

                    // ツリーを再読み込み
                    me.getTree().setLastSelected(null);
                    me.getTree().fireEvent('reloadandreselect');
                }
            },


            'subscription-Window': {
                // Feed登録が正常に完了した時の処理
                successsubmit: function (node_id) {
                    me.getSubscriptionWindow().close();

                    // ツリーの再読み込み
                    me.getTree().setLastSelected(node_id);
                    me.getTree().fireEvent('reloadandreselect');
                }
            }
        });
    },



    /**
     * 名前変更処理
     *
     * @author app2641
     **/
    itemRename: function (node) {
        var win = Ext.create('STAR.view.list.RenameWindow', {
            node: node
        });
        win.show();
    },



    /**
     * 新規フォルダ作成後、ノードを移動させる
     *
     * @author app2641
     **/
    createFolder: function (node) {
        var win = Ext.create('STAR.view.list.CreateFolderWindow', {
            node: node
        });
        win.show();
    },



    /**
     * 登録解除
     *
     * @author app2641
     **/
    itemDeregister: function (node) {
        var me = this;

        Ext.Msg.confirm('Confirm', STAR.locale.list.msg.deregister, function (b) {
            if (b == 'yes') {
                var mask = new Ext.LoadMask(Ext.getBody(), {
                    msg: STAR.locale.common.mask.wait
                });
                mask.show();

                ListData.Deregister({
                    list_id: node.raw.id
                }, function (response) {
                    mask.hide();

                    if (response.success) {
                        me.getTree().setLastSelected(null);
                        me.getTree().fireEvent('reloadandreselect');
                    
                    } else {
                        STAR.Events.showCautionWindow(response.msg);
                    }
                });
            }
        });
    },



    /**
     * ストアのリロードとノードの再選択
     *
     * @author app2641
     **/
    reloadAndReselect: function () {
        var me = this,
            tree = me.getTree(),
            node = tree.getLastSelected();

        tree.getStore().load({
            callback: function () {
                if (node !== null) {
                    var new_node = tree.getStore().getNodeById(node.raw.id);

                    if (new_node.isLeaf()) {
                        new_node.parentNode.expand();
                    }

                    tree.getSelectionModel().select(new_node);
                }
            }
        });
    }
});
