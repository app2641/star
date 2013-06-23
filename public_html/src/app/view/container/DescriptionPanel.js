/**
 * STAR.view.container.DescriptionPanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.container.DescriptionPanel', {

    extend: 'Ext.panel.Panel',

    alias: 'widget.container-DescriptionPanel',


    style: 'margin-bottom: 25px;',
    border: false,
    
    
    initComponent: function () {
        var me = this;

        me.head = (me.head === undefined) ? '': '<h2 class="container-description-header">'+me.head+'</h2>';
        me.description = (me.description === undefined) ? '': '<p>'+me.description+'</p>';

        Ext.apply(me, {
            html: me.head + me.description
        });

        me.callParent(arguments);
    }

});
