<?php

/**

 * Copyright © Webiators. All rights reserved.

 * See LICENSE.txt for license details.

 */



namespace Webiators\GeoIpRedirection\Helper;



use Magento\Framework\App\Helper\AbstractHelper;

use Magento\Store\Model\ScopeInterface;

use Magento\Framework\App\Helper\Context;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;



class Data extends AbstractHelper

{

    const XML_PATH_ENABLED = 'webiators_geoip/webiators_geo_lock/enable';

    const XML_PATH_ENABLED_REDIRECTION = 'webiators_geoip/webiators_geo_redirection/enable_geo_redirection';

    const XML_PATH_COUNTRIES = 'webiators_geoip/webiators_geo_lock/countries';

    const XML_PATH_IP_BLACK_LIST = 'webiators_geoip/webiators_geo_lock/ip_black_list';

    const XML_PATH_API_KEY = 'webiators_geoip/webiators_geo_lock/api_key_ipstack';

    const IP_LIST_REGEXP_DELIMITER = '/[\r?\n]+|[,?]+/';



    private $remoteAddress;



    /**

     * @param Context $context

     */

    public function __construct(

        Context $context,

        RemoteAddress $remoteAddress

    ) {

        parent::__construct($context);

        $this->remoteAddress = $remoteAddress;

    }



    /**

     * @param null $storeId

     * @return bool

     */

    public function isEnabled($storeId = null)

    {

        return $this->scopeConfig->isSetFlag(

            self::XML_PATH_ENABLED,

            ScopeInterface::SCOPE_STORE,

            $storeId

        );

    }



    /**

     * @param null $storeId

     * @return bool

     */

    public function isRedirectionEnabled($storeId = null)

    {

        return $this->scopeConfig->isSetFlag(

            self::XML_PATH_ENABLED_REDIRECTION,

            ScopeInterface::SCOPE_STORE,

            $storeId

        );

    }



    /**

     * @param null $storeId

     * @return array|null

     */

    public function getCountries($storeId = null)

    {

        $countriesRawValue = $this->scopeConfig->getValue(

            self::XML_PATH_COUNTRIES,

            ScopeInterface::SCOPE_STORE,

            $storeId

        );

        $countriesRawValue = $this->prepareCode($countriesRawValue);

        if ($countriesRawValue) {

            $countriesCode = explode(',', $countriesRawValue);



            return $countriesCode;

        }



        return $countriesRawValue ? $countriesRawValue : null;

    }





    /**

     * @param null $storeId

     * @return array

     */

    public function getIpBlackList($storeId = null)

    {

        $rawIpList = $this->scopeConfig->getValue(

            self::XML_PATH_IP_BLACK_LIST,

            ScopeInterface::SCOPE_STORE,

            $storeId

        );



        $ipList = array_filter((array) preg_split(self::IP_LIST_REGEXP_DELIMITER, $rawIpList));



        return $ipList;

    }





    /**

     * Changes country code to upper case

     *

     * @param string $countryCode

     * @return string

     */

    public function prepareCode($countryCode)

    {

        return strtoupper(trim($countryCode));

    }



    /**

     * Get API Key From Config

     *

     * @return string 

    */



    public function getAccessApiKey($storeId = null)

    {

        $api_key = $this->scopeConfig->getValue(

            self::XML_PATH_API_KEY,

            ScopeInterface::SCOPE_STORE,

            $storeId

        );

        return $api_key;

    }





    function getUserIpAddr(){

        

        $ip = $this->remoteAddress->getRemoteAddress();



        return trim($ip);

    }

    public function setStore($curruntCountryCode){
        $cookie_name = "storeView";
        $setCookies = !empty($_COOKIE[$cookie_name])?$_COOKIE[$cookie_name]:'';
        if(empty($setCookies))
        {
            setcookie($cookie_name, $curruntCountryCode, time() + (86400 * 365), "/"); // 86400 = 1 day
            // return true;
            return 'A';
        } else {
            
            // return false;
            return 'B';
        }
    }
    

}

