<?php

class Widgento_Core_Adminhtml_RewriteController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this
            ->loadLayout()
            ->renderLayout();
    }
}
