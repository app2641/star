/**
 * STAR.controller.header.SettingButton
 *
 * @author app2641
 **/
Ext.define('STAR.controller.header.SettingButton', {

    extend: 'Ext.app.Controller',

    
    init: function () {
        var me = this;

        me.control({
            'header-SettingButton menuitem[action="g_import"]': {
                click: function () {
                    var win = Ext.create('STAR.view.import.Window');
                    win.show();
                }
            },


            'header-SettingButton menuitem[action="account"]': {
                click: function () {
                    var win = Ext.create('STAR.view.account.Window');
                    win.show();
                }
            },


            'header-SettingButton menuitem[action="logout"]': {
                click: function (btn) {
                    me.logout(btn);
                }
            }
        });
    },



    /**
     * STARをログアウトする
     *
     * @author suguru
     **/
    logout: function (btn) {
        var me = this;

        Ext.Msg.confirm('Confirm', STAR.locale.header.confirm.logout, function (b) {
            if (b == 'yes') {
                btn.disable();
                var mask = new Ext.LoadMask(Ext.getBody(), {
                    msg: STAR.locale.common.mask.wait
                });
                mask.show();

                User.logout(null, function (response) {
                    if (response.success) {
                        Ext.getBody().fadeOut({
                            callback: function () {
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        });
    }

});
