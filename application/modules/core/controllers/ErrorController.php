<?php

class ErrorController extends Zend_Controller_Action
{

    public function init ()
    {
        $this->view->layout()->setLayout('error_layout', true);
    }



    /**
     * PDOの初期化が正しく行われなかった場合の処理
     *
     * @return void
     **/
    public function pdoAction ()
    {
        $this->_helper->layout->assign('description', 'PDOクラスをインスタンス化することが出来ませんでした。');
        $this->_helper->layout->assign('title', 'PDOException -- '.SITE);
    }



    /**
     * 何ならかのエラーが発生した場合
     * debugパラメータが付与されている場合にスタックトレースを行う
     *
     **/
    public function errorAction()
    {
        $errors = $this->getRequest()->getParam('error_handler');
        $this->view->errors = $errors;
        $this->view->exception = $errors->exception;


        // debug値の有無
        $debug_param = $this->getRequest()->getParam('debug');
        $debug = false;

        if (! is_null($debug_param) && $debug_param == "app") {
            $debug = true;
        }

        $this->view->debug = $debug;
    }
}

