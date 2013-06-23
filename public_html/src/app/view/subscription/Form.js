/**
 * STAR.view.subscription.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.subscription.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.subscription-Form',

    requires: [
        'STAR.view.container.DescriptionPanel'
    ],


    border: false,
    bodyStyle: 'padding: 20px;',
    defaults: {
        width: 430,
        labelWidth: 80
    },
    

    initComponent: function () {
        var me = this;

        me.items = [];
        me.buildDescriptionPanel();
        me.buildURLField();
        me.buildDirectoryField();

        me.callParent(arguments);
    },



    /**
     * DescriptionPanelの構築
     *
     * @author app2641
     **/
    buildDescriptionPanel: function () {
        this.items.push({
            xtype: 'container-DescriptionPanel',
            head: STAR.locale.subscription.msg.head,
            description: STAR.locale.subscription.msg.description
        });
    },



    /**
     * URL入力フィールドの構築
     *
     * @author app2641
     **/
    buildURLField: function () {
        this.items.push({
            xtype: 'textfield',
            fieldLabel: STAR.locale.subscription.field.url,
            name: 'url',
            vtype: 'url',
            allowBlank: false
        });
    },



    /**
     * ディレクトリ選択コンボボックス
     *
     * @author app2641
     **/
    buildDirectoryField: function () {
        var me = this,
            store = Ext.create('STAR.store.subscription.Directory');

        me.items.push({
            xtype: 'combobox',
            store: store,
            name: 'directory',
            fieldLabel: STAR.locale.subscription.field.directory,
            displayField: 'title',
            valueField: 'id',
            typeAhead: true,
            forceSelection: true,
            selectOnFocus: true,
            queryMode: 'remote',
            minChars: 2
        });
    }
});
