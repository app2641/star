

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
    controllers: STAR.Controllers,
    launch: function () {
        var panel = Ext.create('STAR.view.container.MainPanel', {
            width: 1200,
            height: 600,
            renderTo: 'render-component'
        });
    }
});

