/**
 * STAR.store.list.Tree
 *
 * @author app2641
 **/
Ext.define('STAR.store.list.Tree', {

    extend: 'Ext.data.TreeStore',

    constructor: function () {
        var me = this;

        Ext.apply(me, {
            autoLoad: true,
            proxy: {
                type: 'direct',
                directFn: ListData.getList
            }
        });

        me.callParent(arguments);
    }

});
