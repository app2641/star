/**
 * STAR.view.container.LeftPanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.container.LeftPanel', {

    extend: 'Ext.container.Container',

    alias: 'widget.container-LeftPanel',

    requires: [
        'STAR.view.list.Tree'
    ],
    

    layout: 'border',


    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                region: 'north',
                bodyStyle: 'padding: 24px 33px;',
                layout: 'fit',
                items: [{
                    xtype: 'button',
                    action: 'subscription',
                    scale: 'large',
                    width: 60,
                    text: STAR.locale.container.button.entry
                }],
                height: 80
            }, {
                xtype: 'list-Tree',
                region: 'center'
            }]
        });

        me.callParent(arguments);
    }

});
