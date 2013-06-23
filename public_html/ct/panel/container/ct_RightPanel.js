

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
        'STAR.controller.feed.Grid'
    ],
    launch: function () {
        var panel = Ext.create('STAR.view.feed.Grid', {
            width: 600,
            height: 500,
            renderTo: 'render-component'
        });
    }
});

