<?php
namespace Annexcloud\SocialLogin\Model\Config\Source;
use Magento\Framework\Model\AbstractModel;
class Login extends AbstractModel
{
    public function magentoLogin($firstname, $lastname, $email)
    {
        $obj = \Magento\Framework\App\ObjectManager::getInstance();
        $storeObj = $obj->get('Magento\Store\Model\StoreManagerInterface');
        $store = $storeObj->getStore();
        $customer = $obj->get('Magento\Customer\Model\Customer');
        $customer->setStore($store);
        $customer->loadByEmail($email);
        $session = $obj->get('Magento\Customer\Model\Session');
        if ($customer->getId()) {
            $session->loginById($customer->getId());
        } else {
            //create a new user
            $customer->setEmail($email);
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $customer->setConfirmation(null);
            $customer->save();
            $session->loginById($customer->getId());
        }
    }
}
        