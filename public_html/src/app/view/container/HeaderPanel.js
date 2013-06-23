/**
 * STAR.view.container.HeaderPanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.container.HeaderPanel', {

    extend: 'Ext.container.Container',

    alias: 'widget.container-HeaderPanel',

    requires: [
        'STAR.view.header.TitlePanel',
        'STAR.view.header.SearchField',
        'STAR.view.header.SettingButton'
    ],

    
    layout: 'hbox',
    margin: '15 0 0 25',

    
    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'header-TitlePanel',
                width: 170
            }, {
                xtype: 'header-SearchField'
            }, {
                xtype: 'tbfill'
            }, {
                xtype: 'header-SettingButton'
            }]
        });

        me.callParent(arguments);
    }

});
