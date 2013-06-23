

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
        'STAR.controller.list.Tree',
        'STAR.controller.list.Form'
    ],
    launch: function () {

        var tree = Ext.create('STAR.view.list.Tree', {
            width: 600,
            height: 400,
            renderTo: 'render-component'
        });
    }

});

