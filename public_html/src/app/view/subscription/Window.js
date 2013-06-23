/**
 * STAR.view.subscription.Window
 *
 * @author app2641
 **/
Ext.define('STAR.view.subscription.Window', {

    extend: 'Ext.window.Window',

    alias: 'widget.subscription-Window',

    requires: [
        'STAR.view.subscription.Form'
    ],


    iconCls: 'subscription-window-icon',
    title: STAR.locale.subscription.title.window,
    layout: 'fit',
    modal: true,
    width: 490,
    height: 280,
    

    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'subscription-Form',
                api: {
                    submit: ListData.subscription
                }
            }],
            buttons: [{
                text: STAR.locale.common.button.cancel,
                handler: function () {
                    me.close();
                }
            }, {
                text: STAR.locale.subscription.button.subscription,
                action: 'subscription'
            }]
        });

        me.callParent(arguments);
    }

});
