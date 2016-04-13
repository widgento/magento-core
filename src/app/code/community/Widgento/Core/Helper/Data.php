<?php

class Widgento_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_FEED_URL_PATH = 'widgentocore/newsletter/feed_url';

    public function getFeedUrl()
    {
        $url = Mage::getStoreConfig(self::XML_FEED_URL_PATH);

        return  $url.'?s='.urlencode(Mage::getStoreConfig('web/unsecure/base_url'));
    }
}
