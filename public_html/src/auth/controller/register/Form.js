/**
 * STAR.controller.register.Form
 *
 * @author app2641
 **/
Ext.define('STAR.controller.register.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Form', selector: 'register-Form'
    }],


    init: function () {
        var me = this;

        me.control({
            'register-Form': {
                submit: me.submit
            }
        });
    },


    
    /**
     * フォーム送信処理
     *
     * @author app2641
     **/
    submit: function (btn) {
        var me = this,
            form = me.getForm();
        btn.disable();

        if (form.getForm().isValid()) {
            Ext.Msg.confirm('Confirm', STAR.locale.register.confirm.register, function (b) {
                if (b == 'yes') {
                    var mask = new Ext.LoadMask(Ext.getBody(), {
                        msg: STAR.locale.common.mask.wait
                    });
                    mask.show();

                    form.getForm().submit({
                        success: function (form, response) {
                            mask.hide();

                            Ext.Msg.show({
                                title: 'Success!',
                                msg: STAR.locale.register.msg.registed,
                                icon: Ext.Msg.INFO,
                                closable: false,
                                buttons: Ext.Msg.OK,
                                fn: function () {
                                    Ext.getBody().fadeOut({
                                        callback: function () {
                                            window.location = '/';
                                        }
                                    });
                                }
                            });
                        },
                        failure: function (form, response) {
                            mask.hide();
                            btn.enable();

                            Ext.Msg.show({
                                title: 'Caution!',
                                msg: response.result.msg,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                        }
                    });
                    
                } else {
                    btn.enable();
                }
            });
        } else {
            btn.enable();
        }
    }

});
