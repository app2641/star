/**
 * STAR.controller.login.Form
 *
 * @author app2641
 **/
Ext.define('STAR.controller.login.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Form', selector: 'login-Form'
    }, {
        ref: 'Button', selector: 'login-Form button[action="login"]'
    }],


    init: function () {
        var me = this;

        me.control({
            'login-Form textfield[name="username"]': {
                render: function (field) {
                    field.focus(false, 400);
                },

                specialkey: function (field, e) {
                    if (e.getKey() == e.ENTER) {
                        me.getForm().fireEvent('submit');
                    }
                }
            },

            'login-Form textfield[name="password"]': {
                specialkey: function (field, e) {
                    if (e.getKey() == e.ENTER) {
                        me.getForm().fireEvent('submit');
                    }
                }
            },

            'login-Form button[action="login"]': {
                click: function () {
                    me.getForm().fireEvent('submit');
                }
            },


            'login-Form': {
                submit: me.submit
            }
        });
    },



    /**
     * フォーム送信処理
     *
     * @author app2641
     **/
    submit: function () {
        var me = this,
            btn = me.getButton(),
            form = me.getForm();
        
        btn.disable();

        if (form.getForm().isValid()) {
            form.getForm().submit({
                success: function (form, response) {
                    window.location = '/';
                },
                failure: function (form, response) {
                    btn.enable();
                    me.showFailureMessage();
                }
            });
        } else {
            btn.enable();
        }
    },



    /**
     * ログイン失敗メッセージを表示する
     *
     * @author app2641
     **/
    showFailureMessage: function () {
        var me = this;

        if (me.msgCt === undefined) {
            me.msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
        }

        var dom = '<div class="msg"><h3>Failure!</h3>' +
                '<p>'+ STAR.locale.login.msg.failure_login +'</p></div>',
            msg = Ext.DomHelper.append(me.msgCt, dom, true);

        msg.hide();
        msg.slideIn('t').ghost("t", {delay: 2000, remove: true});
    }

});
