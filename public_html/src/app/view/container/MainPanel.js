/**
 * STAR.view.container.MainPanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.container.MainPanel', {

    extend: 'Ext.container.Container',

    alias: 'widget.container-MainPanel',

    requires: [
        'STAR.view.container.LeftPanel',
        'STAR.view.container.RightPanel'
    ],

    
    layout: 'border',


    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'container-LeftPanel',
                region: 'west',
                split: true,
                width: 250
            }, {
                xtype: 'container-RightPanel',
                region: 'center',
                split: true
            }]
        });

        me.callParent(arguments);
    }

});
