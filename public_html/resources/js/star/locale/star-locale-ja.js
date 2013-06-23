/**
 * Ja用ロケール
 *
 * @author app2641
 **/
Ext.ns('STAR', 'STAR.locale');

STAR.locale = {

    common: {
        button: {
            submit: '送信',
            cancel: 'キャンセル',
            login: 'ログイン',
            apply: '設定',
            update: '更新'
        },
        mask: {
            wait: 'Waiting....'
        }
    },


    login: {
        field: {
            username: 'ユーザID',
            password: 'パスワード',
            remember: 'ログイン情報を記憶する',
            retype_password: 'パスワードの確認'
        },
        msg: {
            about_account: 'アカウントの作成について',
            failure_login: 'ユーザ名かパスワードが間違っています'
        }
    },



    register: {
        msg: {
            errorusername: '半角英数字を入力してください',
            error_retype: '確認のため、入力したパスワードと同じものを入力してください',
            registed: 'アカウントを新規作成しました！<br />ログイン画面へ移動します',
            registeraccount: 'アカウントの作成'
        },
        confirm: {
            register: 'この内容でアカウントを作成してよろしいですか？'
        }
    },



    g_import: {
        title: {
            window: 'GoogleReaderのインポート'
        },
        field: {
            google: 'subscriptions.xml'
        },
        button: {
            g_import: 'インポート'
        },
        msg: {
            successimport: 'インポートに成功しました！',
            head: 'GoogleReaderのインポート',
            description: 'GoogleReaderからエクスポートしたsubscriptions.xmlをアップロードしてGoogleReaderのフィードを引き継ぐことが出来ます'
        },
        mask: {
            g_import: 'GoogleReaderデータのインポートには時間がかかります<br />暫くお待ち下さい'
        }
    },



    subscription: {
        title: {
            window: 'RSSの購読'
        },
        field: {
            url: 'URL',
            directory: 'フォルダ'
        },
        button: {
            subscription: '購読'
        },
        msg: {
            head: 'RSSの購読',
            description: '購読したいRSSを配信しているページのURL、またはRSSのURLを指定してSTARに登録をすることができます<br />' +
                'フォルダにはフィードを紐付けたいフォルダを選択してください<br />' +
                '無指定の場合はすべてのアイテムの直下に置かれます'
        }
    },



    account: {
        title: {
            window: 'アカウント設定'
        },
        field: {
            username: 'ユーザID',
            password: 'パスワード',
            confirm_password: '確認用パスワード'
        },
        msg: {
            invalid_password: '確認用パスワードが正しくありません',
            invalid_username: '半角英数字を入力してください',
            head: 'アカウント設定',
            description: 'アカウントの設定を行うことができます<br />' +
                'パスワードを変更したくない場合は、パスワードフィールドを空のまま更新ボタンを押してください',
            confirm_update: 'アカウント情報を上書きしますがよろしいですか？',
            success_update: 'アカウント情報を更新しました'
        }
    },



    container: {
        button: {
            entry: '登録'
        }
    },



    list: {
        title: {
            folder_rename_window: 'フォルダ名を変更',
            item_rename_window: 'アイテム名を変更',
            create_folder_window: '新しいフォルダの作成'
        },
        menu: {
            folder_rename: 'フォルダ名変更',
            item_rename: 'アイテム名変更',
            create_folder: '新しいフォルダを作成して移動',
            deregistration: '登録の解除'
        },
        field: {
            name: '名前'
        },
        msg: {
            deregister: '登録を解除しますか？'
        }
    },



    header: {
        button: {
            setting: '設定'
        },
        menu: {
            g_import: 'GoogleReaderのインポート',
            account: 'アカウントの設定',
            logout: 'ログアウト'
        },
        confirm: {
            logout: 'STARアプリケーションからログアウトしますか？'
        }
    }
};
