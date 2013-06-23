/**
 * STAR.view.list.CreateFolderWindow
 *
 * @author app2641
 **/
Ext.define('STAR.view.list.CreateFolderWindow', {

    extend: 'Ext.window.Window',

    alias: 'widget.list-CreateFolderWindow',

    requires: [
        'STAR.view.list.Form'
    ],


    title: STAR.locale.list.title.create_folder_window,
    iconCls: 'list-create-folder-icon',
    modal: true,
    closable: false,
    layout: 'fit',
    width: 455,
    height: 125,

    
    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'list-Form',
                node: me.node,
                api: {
                    submit: ListData.createFolder
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
            }]
        });

        me.callParent(arguments);
    }

});
