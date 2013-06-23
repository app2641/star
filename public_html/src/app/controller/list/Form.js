/**
 * STAR.controller.list.Form
 *
 * @author app2641
 **/
Ext.define('STAR.controller.list.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'form', selector: 'list-Form'
    }, {
        ref: 'tree', selector: 'list-Tree'
    }],


    init: function () {
        var me = this;

        me.control({
            'list-Form': {
                submit: me.submit
            },


            'list-CreateFolderWindow': {
                successsubmit: function (win) {
                    win.close();
                    me.getTree().fireEvent('reloadandreselect');
                }
            },


            'list-RenameWindow': {
                successsubmit: function (win) {
                    win.close();
                    me.getTree().fireEvent('reloadandreselect');
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

        btn.disable();

        if (form.getForm().isValid()) {
            var mask = new Ext.LoadMask(Ext.getBody(), {
                msg: STAR.locale.common.mask.wait
            });
            mask.show();

            form.getForm().submit({
                success: function (form, response) {
                    mask.hide();

                    var win = me.getForm().up('window');
                    if (win) {
                        win.fireEvent('successsubmit', win);
                    }
                },
                failure: function (form, response) {
                    mask.hide();
                    btn.enable();

                    STAR.Events.showCautionWindow(response.result.msg);
                }
            });
        } else {
            btn.enable();
        }
    }

});
