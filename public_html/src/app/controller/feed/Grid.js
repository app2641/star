/**
 * STAR.controller.feed.Grid
 *
 * @author app2641
 **/
Ext.define('STAR.controller.feed.Grid', {

    extend: 'Ext.app.Controller',

    refs: [{
        ref: 'Grid', selector: 'feed-Grid'
    }, {
        ref: 'Tree', selector: 'list-Tree'
    }],


    init: function () {
        var me = this;

        me.control({

            // フィードグリッド
            'feed-Grid': {
                // feed選択処理
                select: function (grid, record) {
                    // 既読フラグがない場合は既読処理を行う
                    if (record.raw.is_read == '0') {
                        Feed.readable({
                            feed_id: record.raw.feed_id
                        }, function (response) {
                            if (! response.success) {
                                STAR.Events.showCautionWindow(response.msg);
                            }
                        });
                    }
                    window.open(record.raw.feed_url, '_blank');
                },

                // ポケットアイコン押下処理
                pocket: function (row_index) {
                    var feed = me.getGrid().getStore().getAt(row_index);
                    console.log(feed);
                },
                
                // はてブアイコン押下処理
                hatebu: function (row_index) {
                    var feed = me.getGrid().getStore().getAt(row_index);
                    window.open('http://b.hatena.ne.jp/entry/'+feed.raw.feed_url);
                }
            },


            // ノードの選択処理
            'list-Tree': {
                select: function (model, record, index, opt) {
                    me.getTree().setLastSelected(record);
                    
                    var grid = me.getGrid();
                    grid.getStore().load({
                        params: {
                            list_id: record.raw.id
                        }
                    });
                }
            }
        });
    }
});
