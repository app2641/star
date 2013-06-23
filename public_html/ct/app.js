

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
        Ext.create('STAR.view.Viewport');
    }
});


