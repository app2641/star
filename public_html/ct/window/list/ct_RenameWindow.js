

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

    var win = Ext.create('STAR.view.list.RenameWindow', {
        node: {
            raw: {id: 2, is_folder: '1'}
        }
    });
    win.show();

});

