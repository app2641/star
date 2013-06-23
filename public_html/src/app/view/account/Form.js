/**
 * STAR.view.account.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.account.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.account-Form',

    requires: [
        'STAR.view.container.DescriptionPanel'
    ],


    border: false,
    bodyStyle: 'padding: 20px;',

    
    initComponent: function () {
        var me = this;

        me.buildVtype();

        me.items = [];
        me.buildDescription();
        me.buildUserId();
        me.buildUserName();
        me.buildPassword();
        me.buildConfirmPassword();

        me.callParent(arguments);
    },



    /**
     * カスタムVTypeを設定する
     *
     * @author app2641
     **/
    buildVtype: function () {
        var me = this;

        Ext.apply(Ext.form.field.VTypes, {
            username: function (value) {
                return (/^[!-\.:-@\[-`{-~a-zA-Z0-9]+$/.test(value)) ? true: false;
            },
            usernameText: STAR.locale.account.msg.invalid_username,

            retype: function (value) {
                var password = me.down('textfield[name="password"]').getValue();
                return (password === value) ? true: false;
            },
            retypeText: STAR.locale.account.msg.invalid_password
        });
    },



    /**
     * DescriptionPanelを構築する
     *
     * @author app2641
     **/
    buildDescription: function () {
        this.items.push({
            xtype: 'container-DescriptionPanel',
            head: STAR.locale.account.msg.head,
            description: STAR.locale.account.msg.description
        });
    },



    /**
     * ユーザIDフィールドの構築
     *
     * @author app2641
     **/
    buildUserId: function () {
        this.items.push({
            xtype: 'hidden',
            name: 'id',
            allowBlank: false
        });
    },


    
    /**
     * ユーザ名フィールドの構築
     *
     * @author app2641
     **/
    buildUserName: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'username',
            fieldLabel: STAR.locale.account.field.username,
            allowBlank: false,
            vtype: 'username',
            width: 350
        });
    },



    /**
     * パスワードフィールドの構築
     *
     * @author app2641
     **/
    buildPassword: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'password',
            inputType: 'password',
            fieldLabel: STAR.locale.account.field.password,
            vtype: 'username',
            width: 350
        });
    },



    /**
     * 確認用パスワードフィールドの構築
     *
     * @author app2641
     **/
    buildConfirmPassword: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'confirm',
            inputType: 'password',
            fieldLabel: STAR.locale.account.field.confirm_password,
            vtype: 'retype',
            width: 350
        });
    }

});
