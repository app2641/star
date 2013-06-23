

Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext': '/ext/src',
        'Ext.ux': '/src/ux',
        'STAR': '/src/app'
    }
});



Ext.direct.Manager.addProvider(STAR.REMOTING_API);
Ext.application({
    controllers: [
        'STAR.controller.account.Form',
        'STAR.controller.account.Window',
        'STAR.controller.header.SettingButton'
    ],
    launch: function () {
        var panel = Ext.create('STAR.view.container.HeaderPanel', {
            width: 800,
            height: 500,
            renderTo: 'render-component'
        });
    }
});

