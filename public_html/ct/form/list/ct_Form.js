

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


Ext.onReady(function () {

    Ext.direct.Manager.addProvider(STAR.REMOTING_API);

    var form = Ext.create('STAR.view.list.Form', {
        node: {
            raw: {
                id: 2
            }
        },
        width: 600,
        height: 400,
        renderTo: 'render-component'
    });

});

