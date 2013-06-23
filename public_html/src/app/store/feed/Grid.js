/**
 * STAR.store.feed.Grid
 *
 * @author app2641
 **/
Ext.define('STAR.store.feed.Grid', {

    extend: 'Ext.data.Store',

    autoLoad: false,

    fields: [{
        name: 'list_title'
    }, {
        name: 'feed_title'
    }, {
        name: 'date'
    }],

    
    proxy: {
        type: 'direct',
        directFn: Feed.getList,
        reader: {
            root: 'contents'
        }
    }

});
