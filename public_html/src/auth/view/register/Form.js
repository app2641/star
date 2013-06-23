/**
 * STAR.view.register.Form
 *
 * @author app2641
 **/
Ext.define('STAR.view.register.Form', {

    extend: 'Ext.form.Panel',

    alias: 'widget.register-Form',

    
    labelWidth: 100,
    border: false,
    buttonAlign: 'right',


    initComponent: function () {
        var me = this;

        me.buildVtypes();

        me.items = [];
        me.buildUsernameField();
        me.buildPasswordField();
        me.buildRetypePasswordField();

        Ext.apply(me, {
            buttons: [{
                text: STAR.locale.common.button.submit,
                handler: function (btn) {
                    me.fireEvent('submit', btn);
                }
            }]
        });

        me.callParent(arguments);
    },



    /**
     * バリデータの生成
     *
     * @author app2641
     **/
    buildVtypes: function () {
        var me = this;

        Ext.apply(Ext.form.field.VTypes, {
            register: function (value) {
                var result;
                if (/^[!-\.:-@\[-`{-~a-zA-Z0-9]+$/.test(value)) {
                    result = true;
                } else {
                    result = false;
                }
                return result;
            },
            registerText: STAR.locale.register.msg.errorusername,

            retype: function (value) {
                var password = me.down('textfield[name="password"]').getValue();

                if (password === value) {
                    return true;
                } else {
                    return false;
                }
            },
            retypeText: STAR.locale.register.msg.error_retype
        });
    },



    /**
     * ユーザ名入力フィールド構築
     *
     * @author app2641
     **/
    buildUsernameField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'username',
            fieldLabel: STAR.locale.login.field.username,
            allowBlank: false,
            minLength: 4,
            vtype: 'register',
            width: 400,
            listeners: {
                render: function (field) {
                    field.focus(false, 400);
                }
            }
        });
    },



    /**
     * パスワード入力フィールド構築
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
            minLength: 6,
            vtype: 'register',
            width: 400
        });
    },



    /**
     * パスワード再入力フィールドの構築
     *
     * @author app2641
     **/
    buildRetypePasswordField: function () {
        this.items.push({
            xtype: 'textfield',
            name: 'retype-password',
            fieldLabel: STAR.locale.login.field.retype_password,
            inputType: 'password',
            allowBlank: false,
            vtype: 'retype',
            width: 400
        });
    }
});
