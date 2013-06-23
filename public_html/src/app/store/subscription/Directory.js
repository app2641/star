/**
 * STAR.store.subscription.Directory
 *
 * @author app2641
 **/
Ext.define('STAR.store.subscription.Directory', {

    extend: 'Ext.data.Store',

    fields: [
        {name: 'id'},
        {name: 'title'}
    ],


    proxy: {
        type: 'direct',
        directFn: ListData.fetchDirectories
    }

});
