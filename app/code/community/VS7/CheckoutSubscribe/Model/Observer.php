<?php

class VS7_CheckoutSubscribe_Model_Observer
{
    public function insertOptions(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();
        if (
            in_array('checkout_onepage_review', $handles)
            && $block instanceof Mage_Checkout_Block_Agreements
            && $block->getNameInLayout() == 'checkout.onepage.agreements'
        ) {
            $transport = $observer->getEvent()->getTransport();
            $html = $transport->getHtml();
            $optionsHtml = Mage::app()->getLayout()->createBlock('core/template')->setTemplate('vs7_checkoutsubscribe/options.phtml')->toHtml();
            $html .= $optionsHtml;
            $transport->setHtml($html);
        }
    }

    public function setSubscribe(Varien_Event_Observer $observer)
    {
        $subscribe = $observer->getEvent()
            ->getControllerAction()
            ->getRequest()
            ->getParam('is_subscribed', 0);

        Mage::getSingleton('checkout/session')->setIsSubscribed($subscribe);

        return $this;
    }

    public function saveSubscribe(Varien_Event_Observer $observer)
    {
        $subscribe = Mage::getSingleton('checkout/session')->getIsSubscribed();
        $email = $observer->getOrder()->getCustomerEmail();
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);

        if ((bool)$subscribe) {
            $subscriber->subscribe($email);
        } else {
            if($subscriber->getId() != null) {
                $subscriber->unsubscribe();
            }
        }

        return $this;
    }
}