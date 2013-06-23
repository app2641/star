/**
 * STAR.view.login.Panel
 *
 * @author app2641
 **/
Ext.define('STAR.view.login.Panel', {

    extend: 'Ext.panel.Panel',

    requires: [
        'STAR.view.login.Form'
    ],


    initComponent: function () {
        var me = this,
            html = me.buildHtml();

        Ext.apply(me, {
            border: false,
            autoScroll: true,
            items: [{
                border: false,
                html: html
            }, {
                xtype: 'login-Form',
                api: {
                    submit: User.login
                }
            }]
        });

        me.callParent(arguments);
    },



    /**
     * タイトルの表示
     *
     * @author app2641
     **/
    buildHtml: function () {
        var html = '<div class="auth-login-title">' +
            '<img src="/resources/image/star.png" />' +
            '<h1 class="wire-one auth-login-star">STAR</h1>' +
            '</div>';

        return html;
    }

});
