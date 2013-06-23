/**
 * STAR.view.import.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.import.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.import-Form',

    requires: [
        'STAR.view.container.DescriptionPanel'
    ],


    border: false,
    bodyStyle: 'padding: 20px;',
    

    initComponent: function () {
        var me = this;

        Ext.apply(me, {
            items: [{
                xtype: 'container-DescriptionPanel',
                head: STAR.locale.g_import.msg.head,
                description: STAR.locale.g_import.msg.description
            }, {
                xtype: 'filefield',
                fieldLabel: STAR.locale.g_import.field.google,
                labelWidth: 140,
                name: 'file',
                allowBlank: false,
                width: 500
            }]
        });

        me.callParent(arguments);
    }
});
