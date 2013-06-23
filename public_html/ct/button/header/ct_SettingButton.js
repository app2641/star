

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
    controllers: 'STAR.controller.header.SettingButton',
    launch: function () {
        var panel = Ext.create('STAR.view.header.SettingButton', {
            renderTo: 'render-component'
        });
    }
});

