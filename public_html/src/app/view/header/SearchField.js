/**
 * STAR.view.header.SearchField
 *
 * @author app2641
 **/
Ext.define('STAR.view.header.SearchField', {

    extend: 'Ext.container.Container',

    alias: 'widget.header-SearchField',


    layout: 'hbox',
    border: false,
    margin: '5 0 0 0',


    initComponent: function () {
        var me = this;

        me.items = [];
        me.buildSearchField();

        me.callParent(arguments);
    },


    
    /**
     * 検索テキストフィールドの構築
     *
     * @author app2641
     **/
    buildSearchField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'query',
            width: 420,
            height: 30
        }, {
            xtype: 'button',
            text: '検索',
            scale: 'medium',
            margin: '0 0 0 20',
            height: 30
        });
    }

});
