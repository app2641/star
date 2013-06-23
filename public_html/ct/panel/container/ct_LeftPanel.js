

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
        'STAR.controller.list.Tree',
        'STAR.controller.list.Form',
        'STAR.controller.container.LeftPanel',
        'STAR.controller.subscription.Form'
    ],
    launch: function () {
        var panel = Ext.create('STAR.view.container.LeftPanel', {
            width: 250,
            height: 400,
            renderTo: 'render-component'
        });
    }
});

