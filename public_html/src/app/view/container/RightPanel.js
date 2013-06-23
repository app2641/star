/**
 * STAR.view.container.RightPanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.container.RightPanel', {

    extend: 'Ext.container.Container',

    alias: 'widget.container-RightPanel',

    requires: [
        'STAR.view.feed.Grid'
    ],

    
    layout: 'fit',


    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'feed-Grid'
            }]
        });

        me.callParent(arguments);
    }

});
