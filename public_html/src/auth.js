

Ext.ns('STAR');

Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext': '/ext/src',
        'Ext.ux': '/src/ux',
        'STAR': '/src/auth'
    }
});


// Ext.direct.Providerの設定
Ext.direct.Manager.addProvider(STAR.REMOTING_API);

Ext.application({
    controllers: STAR.Controllers,
    launch: function () {
        // Elがあるかどうか
        if (Ext.get('auth-login-container')) {
            Ext.create('STAR.view.login.Panel', {
                renderTo: 'auth-login-container'
            });
        } else if (Ext.get('auth-register-container')) {
            Ext.create('STAR.view.register.Form', {
                renderTo: 'auth-register-container',
                api: {
                    submit: User.register
                }
            });
        }
    }
});

