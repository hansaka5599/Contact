<?php
/**
 * Netstarter Pty Ltd.
 *
 * @category    CameraHouse
 * @package     CameraHouse_Contact
 * @author      Netstarter Team <contact@netstarter.com>
 * @copyright   Copyright (c) 2016 Netstarter Pty Ltd. (http://www.netstarter.com.au)
 */

namespace CameraHouse\Contact\Plugin\Controller\Index;

/**
 * Class Post
 * @package CameraHouse\Contact\Plugin\Controller\Index
 */
class Post extends \Magento\Contact\Controller\Index\Post
{

    /**
     * Contact us form name
     */
    const CONTACT_FORM_NAME = 'Contact Us Form';

    const XML_PATH_EMAIL_TEMPLATE_CONTACTUS_CONFIRMATION = 'contact_contact_confirmation_email_template';

    /**
     * @var \Netstarter\StoreLocator\Model\StoreFactory
     */
    protected $store;

    /**
     * Post constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Netstarter\StoreLocator\Model\StoreFactory $storeFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Netstarter\StoreLocator\Model\Store $store
    ) {
        $this->store = $store;
        parent::__construct($context, $transportBuilder, $inlineTranslation, $scopeConfig, $storeManager);
    }

    /**
     * Around plugin for Post user question
     *
     * @param \Magento\Contact\Controller\Index\Post $subject
     * @param \Closure $proceed
     * @return void
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings("unused")
     */
    public function aroundExecute(
        \Magento\Contact\Controller\Index\Post $subject,
        \Closure $proceed
    ) {
        $post = $subject->getRequest()->getPostValue();
        if (!$post) {
            $subject->_redirect('*/*/');
            return;
        }

        $subject->inlineTranslation->suspend();
        try {

            $post['full_name'] = $post['first_name']. ' ' .$post['last_name'];
            $error = false;

            if (!\Zend_Validate::is(trim($post['first_name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['last_name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['store_locator_id']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }

            $storeLocator = $this->store->load($post['store_locator_id']);

            $storeEmail = $storeLocator->getEmail();
            $post['store'] = $storeLocator->getName();

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $subject->_transportBuilder
                ->setTemplateIdentifier($subject->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                ->setTemplateOptions(
                    [
                        'area'  => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($subject->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($storeEmail, $storeScope)
                ->addBcc($subject->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->setReplyTo($post['email'])
                ->getTransport();

            $transport->sendMessage();

            /*Confirmation Email*/

            $transport2 = $subject->_transportBuilder
                ->setTemplateIdentifier(self::XML_PATH_EMAIL_TEMPLATE_CONTACTUS_CONFIRMATION)
                ->setTemplateOptions(
                    [
                        'area'  => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' =>  $this->storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($subject->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($post['email'], $post['full_name'])
                ->setReplyTo($subject->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->getTransport();

            $transport2->sendMessage();

            /*end confirmation email*/


            $subject->inlineTranslation->resume();
            $subject->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );

            $subject->_redirect('contact', array('success' => true));
            return;
        } catch (\Exception $e) {

            $subject->inlineTranslation->resume();
            $subject->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $subject->_redirect('contact');
            return;
        }
    }
}