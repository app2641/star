/**
 * STAR.view.list.Tree
 *
 * @author app2641
 **/
Ext.define('STAR.view.list.Tree', {

    extend: 'Ext.tree.Panel',

    alias: 'widget.list-Tree',

    config: {
        // 最後に選択したノードを格納する
        LastSelected: null
    },


    layout: 'fit',
    lines: false,
    rootVisible: false,
    useArrows: true,
    autoScroll: true,
    viewConfig: {
        loadMask: true,
        plugins: {
            ptype: 'treeviewdragdrop'
        }
    },


    initComponent: function () {
        var me = this;

        me.buildStore();
        me.callParent(arguments);
    },



    /**
     * ストアを構築する
     *
     * @author app2641
     **/
    buildStore: function () {
        this.store = Ext.create('STAR.store.list.Tree');
    },



    /**
     * コンテクストメニューを生成する
     *
     * @author app2641
     **/
    buildContextMenu: function (node) {
        var me = this,
            items = [];

        // フォルダノードかどうか
        if (node.raw.is_folder == '1') {
            items.push({
                // フォルダリネーム
                text: STAR.locale.list.menu.folder_rename,
                handler: function () {
                    me.fireEvent('itemrename', node);
                }
            }, {
                // 登録解除
                text: STAR.locale.list.menu.deregistration,
                handler: function () {
                    me.fireEvent('itemderegister', node);
                }
            });
        
        } else {
            items.push({
                // アイテムリネーム
                text: STAR.locale.list.menu.item_rename,
                handler: function () {
                    me.fireEvent('itemrename', node);
                }
            }, {
                // 新しくフォルダを作成して移動
                text: STAR.locale.list.menu.create_folder,
                handler: function () {
                    me.fireEvent('createfolder', node);
                }
            }, {
                // 登録解除
                text: STAR.locale.list.menu.deregistration,
                handler: function () {
                    me.fireEvent('itemderegister', node);
                }
            });
        }

        menu = Ext.create('Ext.menu.Menu', {
            bodyStyle: 'padding: 5px;',
            items: items
        });

        return menu;
    }
});
