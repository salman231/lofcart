<?php
namespace LucentInnovation\SocialLogin\Controller\Login;

use Magento\Framework\App\Action\Action;

class Request extends \Magento\Framework\App\Action\Action
{
  protected $_customer;
  protected $_customerSession;

  public function __construct(
    \Magento\Framework\App\Action\Context $context, 
    \Magento\Customer\Model\Customer $customer,
    \Magento\Customer\Model\Session $customerSession
  )
  {
    $this->_customer = $customer;
    $this->_customerSession = $customerSession;
    return parent::__construct($context);
  }


  public function execute()
  {
    error_reporting(0); 
    $data = array();
    $data['redirect'] = '';
    $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
    $appState = $objectManager->get('\Magento\Framework\App\State');
    $setting_redirect_url = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('sociallogin/general/general_redirect_url');

    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    $websiteId = $storeManager->getStore()->getWebsiteId();
    $post = $this->getRequest()->getPostValue();
    $name = $post['name'];
    $social_type = $post['social_type'];
    $names = explode(" ",$post['name']);
    $firstName = $names[0];
    $lastName = $names[1];
    $password =   uniqid();
    $app_id = $post['id'];


    $connection = $objectManager->create('\Magento\Framework\App\ResourceConnection');
    $conn = $connection->getConnection();

    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);    
    $obj = $bootstrap->getObjectManager();
    $deploymentConfig = $obj->get('Magento\Framework\App\DeploymentConfig');
    $db_prefix = $deploymentConfig->get('db/table_prefix');    
    $tableName = $db_prefix.'lucent_sociallogin';

    $data['email_not_exist'] = 'no';  
    $data['name'] = $name;  
    $data['app_id'] = $app_id;  

    if($setting_redirect_url!=''){
        $redirect_url = $setting_redirect_url;
    }else{
        $redirect_url = $storeManager->getStore()->getBaseUrl().'customer/account/';
    }

    $customer = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
    $customer->setWebsiteId($websiteId);

    if(isset($post['email']) && $post['email']!='undefined'){
        $email = $post['email'];
        // instantiate customer object
        $data['data'] = $this->checkCustomer($customer,$email,$redirect_url,$app_id,$firstName,$lastName,$password,$social_type,$conn,$tableName,$objectManager);
    }else{
        $select = $conn->select()
            ->from(
                ['o' => $tableName]
            )
            ->where('o.app_id=?', $app_id);
        $results = $conn->fetchAll($select);
        if(empty($results)){
            $data['email_not_exist'] = 'yes'; 
        }else{
            $email = $results[0]['email'];
            $data['email_not_exist'] = 'no'; 
            $data['data'] = $this->checkCustomer($customer,$email,$redirect_url,$app_id,$firstName,$lastName,$password,$social_type,$conn,$tableName,$objectManager);

        }
    }
    $json_data = json_encode($data);
    print_r($json_data);
  }

  function checkCustomer($customer,$email,$redirect_url,$app_id,$firstName,$lastName,$password,$social_type,$conn,$tableName,$objectManager){
        if ($customer->loadByEmail($email)->getId()) {
            
            $id = $customer->loadByEmail($email)->getId();
            $customer_login = $this->_customer->load($id); 
            $this->_customerSession->setCustomerAsLoggedIn($customer_login);
            

            $data['error'] = 1;  
            $data['message'] = 'already email exist'; 
            $data['redirect'] = $redirect_url;
        } else {
            try {        
                // prepare customer data
                $customer->setEmail($email); 
                $customer->setFirstname($firstName);
                $customer->setLastname($lastName);

                // set null to auto-generate password
                $customer->setPassword($password); 

                // set the customer as confirmed
                // this is optional
                // comment out this line if you want to send confirmation email
                // to customer before finalizing his/her account creation
                $customer->setForceConfirmed(true);

                // save data
                $customer->save();
                if($_SERVER['HTTP_HOST']='localhost' || $_SERVER['HTTP_HOST']='127.0.0.1'){
                }else{
                    $customer->sendNewAccountEmail();
                }
                $id = $customer->getId();
                $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($id); //2 is Customer ID
                $customerSession = $objectManager->create('Magento\Customer\Model\Session');
                $customerSession->setCustomerAsLoggedIn($customer);

          
                $data['error'] = 0;  
                $data['message'] = 'success'; 
                $data['redirect'] = $redirect_url;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $data['error'] = 2;  
                $data['message'] = 'error exception'; 
                $data['redirect'] = '';
            }
        }

        $select = $conn->select()
            ->from(
                ['o' => $tableName]
            )
            ->where('o.app_id=?', $app_id);
        $results = $conn->fetchAll($select);
        if(empty($results)){
            $insert_value = [
                    'customer_id' =>$id,
                    'app_id' => $app_id,
                    'email' => $email,
                    'social_type' => $social_type
                ];

            $conn->insert($tableName, $insert_value);
        }
        return $data;
  }
}
