/**
 * STAR.controller.container.LeftPanel
 *
 * @author app2641
 **/
Ext.define('STAR.controller.container.LeftPanel', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'panel', selector: 'container-LeftPanel'
    }],


    init: function () {
        var me = this;

        me.control({
            'container-LeftPanel button[action=subscription]': {
                click: function () {
                    var win = Ext.create('STAR.view.subscription.Window');
                    win.show();
                }
            }
        });
    }

});
