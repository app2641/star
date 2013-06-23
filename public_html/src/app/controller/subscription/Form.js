/**
 * STAR.controller.subscription.Form
 *
 * @author app2641
 **/
Ext.define('STAR.controller.subscription.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Form', selector: 'subscription-Form'
    }, {
        ref: 'Window', selector: 'subscription-Window'
    }],


    init: function () {
        var me = this;

        me.control({
            'subscription-Window button[action="subscription"]': {
                click: function (btn) {
                    me.submit(btn);
                }
            },


            'subscription-Form textfield[name="url"]': {
                render: function (field) {
                    field.focus(false, 400);
                }
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
            btn.disable();

            var mask = new Ext.LoadMask(Ext.getBody(), {
                msg: STAR.locale.common.mask.wait
            });
            mask.show();

            form.getForm().submit({
                success: function (form, response) {
                    mask.hide();

                    var win = me.getWindow();
                    if (win) {
                        win.fireEvent('successsubmit', response.node_id);
                    }
                },
                failure: function (form, response) {
                    btn.enable();
                    mask.hide();

                    STAR.Events.showCautionWindow(response.result.msg);
                }
            });
        }
    }

});
