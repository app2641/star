/**
 * STAR.view.import.Window
 *
 * @author app2641
 **/
Ext.define('STAR.view.import.Window', {

    extend: 'Ext.window.Window',

    alias: 'widget.import-Window',

    requires: [
        'STAR.view.import.Form'
    ],


    title: STAR.locale.g_import.title.window,
    iconCls: 'import-window-icon',
    layout: 'fit',
    modal: true,
    closable: false,
    width: 555,
    height: 220,


    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'import-Form',
                api: {
                    submit: ListData.dataImport
                },
                listeners: {
                    windowClose: function () {
                        me.close();
                    }
                }
            }],
            buttons: [{
                text: STAR.locale.common.button.cancel,
                handler: function () {
                    me.close();
                }
            }, {
                text: STAR.locale.g_import.button.g_import,
                handler: function (btn) {
                    me.down('import-Form').fireEvent('submit', btn);
                }
            }]
        });

        me.callParent(arguments);
    }

});
