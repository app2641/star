

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
    launch: function () {
        var panel = Ext.create('STAR.view.header.SearchField', {
            width: 600,
            height: 500,
            renderTo: 'render-component'
        });
    }
});

