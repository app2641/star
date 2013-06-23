/**
 * STAR.controller.account.Window
 *
 * @author app2641
 **/
Ext.define('STAR.controller.account.Window', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Window', selector: 'account-Window'
    }, {
        ref: 'Form', selector: 'account-Form'
    }],


    init: function () {
        var me = this;

        me.control({
            'account-Window button[action="close"]': {
                click: function () {
                    me.getWindow().close();
                }
            },


            'account-Window button[action="update"]': {
                click: me.submit
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

        if (form.getForm().isValid()) {
            Ext.Msg.confirm('Confirm', STAR.locale.account.msg.confirm_update, function (b) {
                if (b == 'yes') {
                    btn.disable();

                    var mask = new Ext.LoadMask(Ext.getBody(), {
                        msg: STAR.locale.common.mask.wait
                    });
                    mask.show();

                    form.getForm().submit({
                        success: function (f, response) {
                            mask.hide();

                            var win = me.getWindow();
                            win.close();

                            STAR.Events.showInfoWindow(STAR.locale.account.msg.success_update);
                        },
                        failure: function (f, response) {
                            btn.enable();
                            mask.hide();

                            STAR.Events.showCautionWindow(response.result.msg);
                        }
                    });
                }
            });
        }
    }

});
