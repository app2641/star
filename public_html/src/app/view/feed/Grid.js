/**
 * STAR.view.feed.Grid
 *
 * @author app2641
 **/
Ext.define('STAR.view.feed.Grid', {

    extend: 'Ext.grid.Panel',

    alias: 'widget.feed-Grid',


    id: 'STAR-view-feed-Grid',
    layout: 'fit',
    hideHeaders: true,
    stripeRows: true,
    loadMask: true,

    
    initComponent: function () {
        var me = this;

        me.buildStore();
        me.buildColumn();

        me.callParent(arguments);
    },


    
    /**
     * ストアを生成する
     *
     * @author app2641
     **/
    buildStore: function () {
        this.store = Ext.create('STAR.store.feed.Grid');
    },



    /**
     * カラムを生成する
     *
     * @author app2641
     **/
    buildColumn: function () {
        this.columns = [{
            xtype: 'actioncolumn',
            width: 25,
            items: [{
                getClass: this.getStarIconCls
            }]
        }, {
            xtype: 'actioncolumn',
            width: 25,
            items: [{
                getClass: this.getPocketIconCls,
                handler: function (view, row_index) {
                    this.fireEvent('pocket', row_index);
                },
                scope: this
            }]
        }, {
            xtype: 'actioncolumn',
            width: 25,
            items: [{
                iconCls: 'feed-item-hatebu-icon',
                handler: function (view, row_index) {
                    this.fireEvent('hatebu', row_index);
                },
                scope: this
            }]
        }, {
            text: 'list_title',
            dataIndex: 'list_title',
            sortable: false,
            menuDisabled: true,
            flex: 2
        }, {
            text: 'feed_title',
            dataIndex: 'feed_title',
            renderer: this.renderTitle,
            sortable: false,
            menuDisabled: true,
            flex: 7
        }, {
            text: 'date',
            dataIndex: 'date',
            renderer: this.renderDate,
            sortable: false,
            menuDisabled: true,
            flex: 0.6
        }];
    },



    /**
     * スターアイコンのclsを取得する
     *
     * @author app2641
     **/
    getStarIconCls: function (val, meta, record) {
        //console.log(record);
    },



    /**
     * Pocketアイコンのclsを取得する
     *
     * @author app2641
     **/
    getPocketIconCls: function (val, meta, record) {
        return 'feed-item-pocket-unset-icon';
    },



    /**
     * フィード既読かどうかでボールド表記に
     *
     * @author app2641
     **/
    renderTitle: function (val, meta, record) {
        if (record.raw.is_read == '1') {
            val = '<span class="feed-item-read">'+val+'</span>';
        }
        return val;
    },



    /**
     * 日付の表示を整形する
     *
     * @author app2641
     **/
    renderDate: function (val) {
        var date  = new Date(),
            year  = date.getYear(),
            month = date.getMonth() + 1,
            day   = date.getDate();

        // 日付を整形
        if (year < 2000) { year += 1900; }
        if (month < 10) { month = '0' + month; }
        if (day < 10) { day = '0' + day; }

        // 日付を分割
        var today = year+'-'+month+'-'+day;
        if (val.substr(0, 10) == today) {
            // 更新日が今日なら時間のみ表示
            return val.substr(11, 5);
        } else {
            return month+'/'+day;
        }
    }

});
