

Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext': '/ext/src',
        'Ext.ux': '/src/ux',
        'STAR': '/src/auth'
    }
});


Ext.require([
    'Ext.direct.*',
    'Ext.data.*'
]);


Ext.onReady(function () {

    Ext.direct.Manager.addProvider(STAR.REMOTING_API);

    var form = Ext.create('STAR.view.login.Form', {
        width: 600,
        height: 400,
        renderTo: 'render-component',
        api: {
            submit: User.login
        }
    });

});

