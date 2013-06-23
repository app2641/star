<?php


class AuthController extends \Zend_Controller_Action
{

    public function init ()
    {
        $this->view->layout()->disableLayout();

        if (APPLICATION_ENV == "development") {
            $debug = $this->getRequest()->getParam('debug');
            $this->view->debug = ($debug == "ct") ? true: false;

        } else {
            $this->view->debug = false;
        }
    }



    /**
     * ログイン画面
     *
     **/
    public function loginAction ()
    {
    }



    /**
     * アカウント作成画面
     *
     **/
    public function registerAction ()
    {
    }
}
