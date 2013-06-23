

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
    controllers: 'STAR.controller.setting.CronForm',
    launch: function () {
        var win = Ext.create('STAR.view.setting.CronSettingWindow');
        win.show();
    }
});

