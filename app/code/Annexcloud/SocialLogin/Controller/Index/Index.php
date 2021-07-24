<?php
namespace Annexcloud\SocialLogin\Controller\Index;

use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\App\Action\Action;

class Index extends Action
{
    /* This function returns response in html. */
    private $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    protected function xdmessage($msgCode)
    {
        if (!empty($msgCode)) {
            $enCode=urlencode($msgCode);
            $url = "http://api.socialannex.com/s13/v2/service/postmessage-script.php?msgcode=".$enCode;
            $resp = $this->fetch($url);
            return $resp;
        }
    }

    /* This function is use to return data. */
    private function fetch($url, $data = null)
    {
        $objCurl = new \Magento\Framework\HTTP\Adapter\Curl();
        $objCurl->setConfig(array('header' => 0));
        if ($data != null) {
            $objCurl->write('POST', $url, '1.1', array(), $data);
            $resp = $objCurl->read();
        } else {
            $objCurl->write('GET', $url, '1.1');
            $resp = $objCurl->read();
        }

        return $resp;
    }

    public function execute()
    {
        $obj = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $obj->get('Magento\Framework\App\RequestInterface');
        $msgCode = $request->getParam('msgcode');
        $nextUrl = $request->getParam('next');
        $oauthCode = $request->getParam('code');
        try {
        /* Some logic that could throw an Exception */
    
        if (!empty($msgCode)) {
            $funResp=$this->xdmessage($msgCode);
            $this->getResponse()->setBody($funResp);
        } else {
            $objValue = $obj->get('Magento\Framework\App\Config\ScopeConfigInterface');
            $siteId=$objValue->getValue('saadmin/sageneral/siteid');
            $secret=$objValue->getValue('saadmin/salogin/secretkey');
            $msgError='Configuration error for social login, Please consult Social Annex Documentation.';
           
            $handleArray=array('next'=>urlencode($nextUrl));
            $getPageUrl = $obj->get('Magento\Framework\UrlInterface');

            $redUri=$getPageUrl->getUrl('sociallogin/index/index', $handleArray);
            $params = array(
                'client_secret' => $secret,
                'oauth_code' => $oauthCode,
                'redirect_url' => $redUri,
                'siteid' => $siteId
            );

            $saToken = $objValue->getValue('saadmin/salogin/tokenurl');
            $tokenUrl = $saToken.'?'.http_build_query($params);
            $accessToken = $this->fetch($tokenUrl);
            $token = json_decode($accessToken);
            $accessTokenArray = array('access_token' => $token->access_token);
            $uProfile = $objValue->getValue('saadmin/salogin/userinfo');
            $resUrl= $uProfile.'?'.http_build_query($accessTokenArray);
            $profileJson = $this->fetch($resUrl);
            $profile = json_decode($profileJson);
            $firstname = $profile->firstname;
            $lastname = $profile->lastname;
            $email = $profile->email;
            
            $mgObj = $obj->create('Annexcloud\SocialLogin\Model\Config\Source\Login');
            $value=$mgObj->magentoLogin($firstname, $lastname, $email);
            $this->_redirect(urldecode(base64_decode($nextUrl)));
        }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
    