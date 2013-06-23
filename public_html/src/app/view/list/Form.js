/**
 * STAR.view.list.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.list.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.list-Form',

    bodyStyle: 'padding: 20px;',
    border: false,

    initComponent: function () {
        var me = this;

        me.items = [];
        me.buildNodeIdField();
        me.buildNameField();

        me.callParent(arguments);
    },



    /**
     * node_id用hiddenフィールドの構築
     *
     * @author app2641
     **/
    buildNodeIdField: function () {
        this.items.push({
            xtype: 'hiddenfield',
            name: 'list_id',
            allowBlank: false,
            value: this.node.raw.id
        });
    },



    /**
     * 名前入力フィールドの構築
     *
     * @author app2641
     **/
    buildNameField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'name',
            labelWidth: 80,
            fieldLabel: STAR.locale.list.field.name,
            allowBlank: false,
            width: 400,
            listeners: {
                render: function (field) {
                    field.focus(true, 400);
                }
            }
        });
    }
});
