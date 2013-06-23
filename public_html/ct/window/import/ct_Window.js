

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
    controllers: ['STAR.controller.import.Form'],
    launch: function () {
        var win = Ext.create('STAR.view.import.Window');
        win.show();
    }

});

