<?php


namespace ElQuchiri\SocialLogin\Model;


use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

class Social extends \Magento\Framework\Model\AbstractModel
{

    private $socialHelper;
    private $customerFactory;
    private $customerRepository;
    private $storeManager;
    private $cookieMetadataFactory;
    private $cookieMetadataManager;
    private $session;
    private $customerModelFactory;
    private $accountManagement;
    private $random;
    private $socialLoginCustomerRepository;
    private $socialNetworkCustomer;
    private $dateTime;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \ElQuchiri\SocialLogin\Helper\Social $socialHelper,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Customer\Model\CustomerFactory $customerModelFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieMetadataManager,
        \Magento\Customer\Model\Session $session,
        \Magento\Customer\Model\AccountManagement $accountManagement,
        \Magento\Framework\Math\Random $random,
        \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomerFactory $socialNetworkCustomer,
        \ElQuchiri\SocialLogin\Model\Repository\SocialLoginCustomerRepository $socialLoginCustomerRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->socialHelper = $socialHelper;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieMetadataManager = $cookieMetadataManager;
        $this->session = $session;
        $this->customerModelFactory = $customerModelFactory;
        $this->accountManagement = $accountManagement;
        $this->random = $random;
        $this->socialLoginCustomerRepository = $socialLoginCustomerRepository;
        $this->socialNetworkCustomer = $socialNetworkCustomer;
        $this->dateTime = $dateTime;
    }

    /**
     * @param $adapterId
     * @return \Hybridauth\User\Profile
     * @throws \Hybridauth\Exception\InvalidArgumentException
     * @throws \Hybridauth\Exception\UnexpectedValueException
     */
    public function getSocialUserProfile($adapterId) {
        $adapterName = $this->socialHelper->getSocialNetwork($adapterId);
        $adaptersConfig = $this->socialHelper->getHybridauthConfig($adapterId);

        $hybridauth = new \Hybridauth\Hybridauth($adaptersConfig);

        // Attempt to authenticate the user
        $adapter = $hybridauth->authenticate($adapterName);

        // Retrieve the user's profile
        $userProfile = $adapter->getUserProfile();

        // Disconnect the adapter (log out)
        $adapter->disconnect();

        return $this->prepareUserProfile($userProfile, $adapterId);
    }

    public function prepareUserProfile($userProfile, $type)
    {
        $name = explode(' ', $userProfile->displayName ?: __('New User'));

        return [
            'email' => $userProfile->email ?: $userProfile->identifier . '@' . strtolower($type) . '.com',
            'firstname' => $userProfile->firstName ?: (array_shift($name) ?: $userProfile->identifier),
            'lastname' => $userProfile->lastName ?: (array_shift($name) ?: $userProfile->identifier),
            'identifier' => $userProfile->identifier,
            'type' => $type,
            'password' => isset($userProfile->password) ? $userProfile->password : null
        ];
    }

    public function createCustomerAccount($userProfile, $type) {
        $store = $this->storeManager->getStore();

        $customer = $this->customerFactory->create();
        $customer
            ->setFirstname($userProfile['firstname'])
            ->setLastname($userProfile['lastname'])
            ->setPrefix('OO')
            ->setDob('00/00/1990')
            ->setEmail($userProfile['email'])
            ->setStoreId($store->getId())
            ->setWebsiteId($store->getWebsiteId())
            ->setCreatedIn($store->getName());

        $customer = $this->customerRepository->save($customer);
        $this->createSocialLoginCustomer($userProfile, $type, $customer->getId());

        // Update rp_token & rk_token_created_at columns
        $newPasswordToken  = $this->random->getUniqueHash();
        $this->accountManagement->changeResetPasswordLinkToken($customer, $newPasswordToken);

        return $this->customerModelFactory->create()->load($customer->getId());
    }

    public function createSocialLoginCustomer($userProfile, $type, $customerId) {
        $socialNetworkCustomer = $this->socialNetworkCustomer->create();
        $currentTimestamp = $this->dateTime->timestamp();

        $socialNetworkCustomer
            ->setSocialId($userProfile['identifier'])
            ->setCustomerId($customerId)
            ->setSocialType($type)
            ->setCreatedAt($currentTimestamp)
            ->setUpdatedAt($currentTimestamp);

        $this->socialLoginCustomerRepository->save($socialNetworkCustomer);
    }

    public function refresh($customer)
    {
        if ($customer && $customer->getId()) {
            $this->session->setCustomerAsLoggedIn($customer);
            $this->session->regenerateId();

            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        }
    }

    /**
     * Retrieve cookie manager
     *
     * @return     PhpCookieManager
     * @deprecated
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = ObjectManager::getInstance()->get(
                PhpCookieManager::class
            );
        }

        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @return     CookieMetadataFactory
     * @deprecated
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = ObjectManager::getInstance()->get(
                CookieMetadataFactory::class
            );
        }

        return $this->cookieMetadataFactory;
    }
}