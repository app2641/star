/**
 * STAR.view.Viewport
 *
 * @author app2641
 **/
Ext.define('STAR.view.Viewport', {

    extend: 'Ext.container.Viewport',

    alias: 'widget.view-Viewport',

    requires: [
        'STAR.view.container.HeaderPanel',
        'STAR.view.container.MainPanel'
    ],

    layout: 'border',

    
    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'container-HeaderPanel',
                region: 'north'
            }, {
                xtype: 'container-MainPanel',
                region: 'center'
            }]
        });
        
        me.callParent(arguments);
    }

});
