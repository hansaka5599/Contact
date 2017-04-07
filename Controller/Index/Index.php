<?php
/**
 * Netstarter Pty Ltd.
 *
 * @category    CameraHouse
 * @package     CameraHouse_Contact
 * @author      Netstarter Team <contact@netstarter.com>
 * @copyright   Copyright (c) 2016 Netstarter Pty Ltd. (http://www.netstarter.com.au)
 */

namespace CameraHouse\Contact\Controller\Index;

/**
 * Class Index
 * @package CameraHouse\Contact\Controller\Index
 */
class Index extends \Magento\Contact\Controller\Index\Index
{
    /**
     * Show Contact Us page
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
