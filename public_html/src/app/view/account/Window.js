/**
 * STAR.view.account.Window
 *
 * @author app2641
 **/
Ext.define('STAR.view.account.Window', {

    extend: 'Ext.window.Window',

    alias: 'widget.account-Window',

    requires: [
        'STAR.view.account.Form'
    ],


    title: STAR.locale.account.title.window,
    modal: true,
    width: 550,
    height: 280,


    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'account-Form',
                api: {
                    load: User.getAccountData,
                    submit: User.update
                }
            }],
            buttons: [{
                text: STAR.locale.common.button.cancel,
                action: 'close'
            }, {
                text: STAR.locale.common.button.update,
                action: 'update'
            }]
        });

        me.callParent(arguments);
    }

});
