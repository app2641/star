/**
 * STAR.view.login.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.login.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.login-Form',


    id: 'STAR.login.Form',
    labelWidth: 100,
    border: false,
    buttonAlign: 'right',


    initComponent: function () {
        var me = this;

        me.items = [];
        me.buildUsernameField();
        me.buildPasswordField();
        me.buildRememberField();
        me.buildAccountField();

        Ext.apply(me, {
            buttons: [{
                text: STAR.locale.common.button.login,
                action: 'login'
            }]
        });

        me.callParent(arguments);
    },



    /**
     * username入力フィールドの構築
     *
     * @author app2641
     **/
    buildUsernameField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'username',
            fieldLabel: STAR.locale.login.field.username,
            allowBlank: false,
            width: 400
        });
    },



    /**
     * パスワード入力フィールドの構築
     *
     * @author app2641
     **/
    buildPasswordField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'password',
            fieldLabel: STAR.locale.login.field.password,
            inputType: 'password',
            allowBlank: false,
            width: 400
        });
    },



    /**
     * ログイン記憶チェックボックスの構築
     *
     * @author app2641
     **/
    buildRememberField: function () {
        this.items.push({
            xtype: 'checkbox',
            name: 'remember',
            boxLabel: STAR.locale.login.field.remember,
            labelAligh: 'right',
            checked: true
        });
    },



    /**
     * アカウント新規作成リンク
     *
     * @author app2641
     **/
    buildAccountField: function () {
        this.items.push({
            html: '<a href="/auth/register">'+ STAR.locale.login.msg.about_account +'</a>',
            border: false,
            layout: 'fit'
        });
    }
});
