/**
 * STAR.view.list.RenameWindow
 *
 * @author app2641
 **/
Ext.define('STAR.view.list.RenameWindow', {

    extend: 'Ext.window.Window',

    alias: 'widget.list-RenameWindow',

    requires: [
        'STAR.view.list.Form'
    ],


    modal: true,
    closable: false,
    layout: 'fit',

    
    initComponent: function () {
        var me = this, title, cls;

        if (me.node.raw.is_folder == '1') {
            title = me.node.raw.text + ': ' + STAR.locale.list.title.folder_rename_window;
            cls = 'list-folder-rename-icon';
        } else {
            title = me.node.raw.text + ': ' + STAR.locale.list.title.item_rename_window;
            cls = 'list-item-rename-icon';
        }

        Ext.apply(me, {
            iconCls: cls,
            title: title,
            items: [{
                xtype: 'list-Form',
                node: me.node,
                api: {
                    submit: ListData.rename
                }
            }],
            buttons: [{
                text: STAR.locale.common.button.cancel,
                handler: function () {
                    me.close();
                }
            }, {
                text: STAR.locale.common.button.submit,
                handler: function (btn) {
                    me.down('list-Form').fireEvent('submit', btn);
                }
            }],
            width: 455,
            height: 125
        });

        me.callParent(arguments);
    }

});
