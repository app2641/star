/**
 * STAR.controller.account.Form
 *
 * @author suguru
 **/
Ext.define('STAR.controller.account.Form', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Form', selector: 'account-Form'
    }],


    init: function () {
        var me = this;

        me.control({
            'account-Form': {
                afterrender: function () {
                    var form = me.getForm();
                    form.getForm().load({
                        success: function (response) {
                            var name_field = me.getForm().down('textfield[name="username"]');
                            name_field.setValue(response.name);

                            var hidden_field = me.getForm().down('hidden[name="id"]');
                            hidden_field.setValue(response.id);
                        }
                    });
                }
            },

            'account-Form textfield[name="username"]': {
                afterrender: function (field) {
                    field.focus(true, 400);
                }
            }
        });
    }

});
