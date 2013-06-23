

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
    controllers: [
        'STAR.controller.account.Form',
        'STAR.controller.account.Window'
    ],
    launch: function () {
        var win = Ext.create('STAR.view.account.Window');
        win.show();
    }

});

