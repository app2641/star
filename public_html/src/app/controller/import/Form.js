/**
 * STAR.controller.import.Form
 *
 * @author app2641
 **/
Ext.define('STAR.controller.import.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'form', selector: 'import-Form'
    }, {
        ref: 'tree', selector: 'list-Tree'
    }],

    
    init: function () {
        var me = this;

        me.control({
            'import-Form': {
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

        if (form.getForm().isValid()) {
            btn.disable();

            var mask = new Ext.LoadMask(Ext.getBody(), {
                msg: STAR.locale.g_import.mask.g_import
            });
            mask.show();

            form.getForm().submit({
                success: function () {
                    mask.hide();
                    STAR.Events.showInfoWindow(STAR.locale.g_import.msg.successimport);

                    var win = me.getForm().up('import-Window');
                    if (win) {
                        win.fireEvent('successsubmit');
                    }
                },
                failure: function (form, response) {
                    mask.hide();
                    btn.enable();

                    STAR.Events.showCautionWindow(response.result.msg);
                }
            });
        }
    }

});
