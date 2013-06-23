

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
    controlers: 'STAR.controller.setting.CronForm',
    launch: function () {
        Ext.create('STAR.view.setting.CronForm', {
            width: 600,
            height: 200,
            renderTo: 'render-component'
        });
    }
});

