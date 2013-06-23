

Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext': '/ext/src',
        'Ext.ux': '/src/ux',
        'STAR': '/src/app'
    }
});


Ext.require([
    'Ext.direct.*',
    'Ext.data.*'
]);


Ext.direct.Manager.addProvider(STAR.REMOTING_API);
Ext.application({
    controllers: 'STAR.controller.account.Form',
    launch: function () {
        Ext.create('STAR.view.account.Form', {
            width: 600,
            height: 400,
            renderTo: 'render-component',
            api: {
                submit: User.update,
                load: User.getAccountData
            }
        });
    }

});

