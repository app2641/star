/**
 * STAR.view.header.TitlePanel
 *
 * @author app2641
 **/
Ext.define('STAR.view.header.TitlePanel', {

    extend: 'Ext.container.Container',

    alias: 'widget.header-TitlePanel',


    layout: 'fit',
    border: false,


    initComponent: function () {
        var me = this;

        me.items = [];
        me.buildHTML();

        me.callParent(arguments);
    },


    
    /**
     * タイトルのHTMLを構築する
     *
     * @author app2641
     **/
    buildHTML: function () {
        var html = '<table class="header-title-container">' +
            '<tbody><tr>' +
                '<td id="header-title-star-icon">' +
                    '<img src="/resources/image/star.png" />' +
                '</td>' +
                '<td id="header-title-star-text">' +
                    '<h1 class="wire-one">STAR</h1>' +
                '</td>' +
            '</tr></tbody>' +
            '</table>';

        this.items.push({
            xtype: 'container',
            border: false,
            html: html
        });
    }

});
