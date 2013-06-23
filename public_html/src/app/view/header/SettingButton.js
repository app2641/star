/**
 * STAR.view.header.SettingButton
 *
 * @author app2641
 **/
Ext.define('STAR.view.header.SettingButton', {

    extend: 'Ext.button.Button',

    alias: 'widget.header-SettingButton',

    
    text: STAR.locale.header.button.setting,
    scale: 'medium',
    menu: [{
        text: STAR.locale.header.menu.g_import,
        action: 'g_import'
    }, {
        text: STAR.locale.header.menu.account,
        action: 'account'
    }, {
        text: STAR.locale.header.menu.logout,
        action: 'logout'
    }],

    margin: '5 25 0 0'

});
