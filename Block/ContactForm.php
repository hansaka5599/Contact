<?php
/**
 * Netstarter Pty Ltd.
 *
 * @category    CameraHouse
 * @package     CameraHouse_Contact
 * @author      Netstarter Team <contact@netstarter.com>
 * @copyright   Copyright (c) 2016 Netstarter Pty Ltd. (http://www.netstarter.com.au)
 */

namespace CameraHouse\Contact\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class ContactForm
 * @package CameraHouse\Contact\Block
 */
class ContactForm extends \Magento\Contact\Block\ContactForm
{
    /**
     * topics config path
     */
    const XML_PATH_TOPICS = 'contact/contact/topics';

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \CameraHouse\StoreLocator\Block\Store\FavouriteStore
     */
    protected $favouriteStore;

    /**
     * ContactForm constructor.
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param Template\Context $context
     * @param \CameraHouse\StoreLocator\Block\Store\FavouriteStore $favouriteStore
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        Template\Context $context,
        \CameraHouse\StoreLocator\Block\Store\FavouriteStore $favouriteStore,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->favouriteStore = $favouriteStore;
    }

    /**
     * returns the scope config topics converted to an array
     *
     * @return array
     */
    public function getTopics()
    {
        $topics = [];
        $topicsConfig = $this->scopeConfig->getValue(
            self::XML_PATH_TOPICS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!empty($topicsConfig)) {
            foreach (explode(PHP_EOL, $topicsConfig) as $topic) {
                if ($topic = trim($topic)) {
                    $topics[$topic] = $topic;
                }
            }
        }

        return $topics;
    }

    /**
     *
     */
    public function getUserStore()
    {
        return $this->favouriteStore->getUserFavoriteStore();
    }
}