<?php
namespace Annexcloud\SocialLogin\Block\Widget;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
class LoginWidget extends Template implements BlockInterface
{
    protected $scopeConfig;
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }
    public function toHtml()
    {
        $siteId=$this->_scopeConfig->getValue('saadmin/sageneral/siteid');
        $saShow = false;
        $curUrl=null;
        $saSocialWidget =null;
        $getInstance = \Magento\Framework\App\ObjectManager::getInstance();
        $getPageUrl = $getInstance->get('Magento\Framework\UrlInterface');
        $customerSession = $getInstance->get('Magento\Customer\Model\Session');
        if (!$customerSession->isLoggedIn()) {
            $curUrl = $getPageUrl->getCurrentUrl();
            $customerSession->setBeforeAuthUrl($curUrl);
            $saShow = true;
        }

        $saSocialWidget = "";
        $paramArray=array('next'=>base64_encode(urlencode($curUrl)));
        $handleUrl=$getPageUrl->getUrl('sociallogin/index/index', $paramArray);
        if ($saShow == true) {
            $saSocialWidget = "<script type=\"text/javascript\">
             window.S13AsyncInit = function(){
             SAS13Obj.init({
             siteid:".$siteId.",
             buttonType:[\"regular\",\"small\"]},
             \"".$handleUrl."\");
             };
             (function(d){
             var js, id = 'socialannex-s13', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {
             return;
             }
             js = d.createElement('script'); js.id = id; js.async = true;
             //js.src = \"//cdn.socialannex.com/partner/".$siteId."/s13.js\";
             js.src = \"//api.socialannex.com/s13/v2/s13-main.js\";
             ref.parentNode.insertBefore(js, ref);
             }(document));
         </script><div id=\"show_provider_small\"></div>";
        }
        return $saSocialWidget;
    }
}
    