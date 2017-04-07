<?php
/**
 * Netstarter Pty Ltd.
 *
 * @category    CameraHouse
 * @package     CameraHouse_Contact
 * @author      Netstarter Team <contact@netstarter.com>
 * @copyright   Copyright (c) 2016 Netstarter Pty Ltd. (http://www.netstarter.com.au)
 */

namespace CameraHouse\Contact\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package Rag\Contact\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * InstallData constructor.
     *
     * @param BlockFactory $modelBlockFactory
     */
    public function __construct(
        BlockFactory $modelBlockFactory
    ) {
        $this->blockFactory = $modelBlockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // @codingStandardsIgnoreStart
        $cmsBlocks = [
                        [
                            'title'      => 'Contact Us Left Content',
                            'identifier' => 'contact_us_left_content',
                            'content'    => '<ul class="links">
                                                <li class="nav item"><a href="/stores">Stores</a></li>
                                                <li class="nav item"><a href="/faq">FAQs</a></li>
                                                <li class="nav item"><a href="/faq">Contact us</a></li>
                                                <li class="nav item"><a href="/shipping">Shipping</a></li>
                                                <li class="nav item"><a href="/returns">Returns</a></li>
                                            </ul>',
                            'is_active'  => 1,
                            'stores'     => 0,
                        ],
                        [
                            'title'      => 'Contact Us Top Content',
                            'identifier' => 'contact_us_top_content',
                            'content'    => '<div class="contact-content-top">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</div>
                                                <div class="contact-form-title">
                                                <h3>By Email</h3>
                                            </div>',
                            'is_active'  => 1,
                            'stores'     => 0,
                        ]
        ];
        // @codingStandardsIgnoreEnd
        /** @var \Magento\Cms\Model\Block $block */
        $block = $this->blockFactory->create();
        foreach ($cmsBlocks as $data) {
            $block->setData($data)->save();
        }
    }
}