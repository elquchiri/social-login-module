<?php


namespace ElQuchiri\SocialLogin\Controller\Social;

use Magento\Framework\App\Action\Context;

class Login extends \Magento\Framework\App\Action\Action
{

    private $resultRawFactory;
    private $socialModel;
    private $customerRepository;
    private $customerModelFactory;
    private $socialNetworkCustomerRepository;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \ElQuchiri\SocialLogin\Model\Social $socialModel,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\CustomerFactory $customerModelFactory,
        \ElQuchiri\SocialLogin\Api\SocialNetworkCustomerRepositoryInterface $socialNetworkCustomerRepository
    )
    {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->socialModel = $socialModel;
        $this->customerRepository = $customerRepository;
        $this->customerModelFactory = $customerModelFactory;
        $this->socialNetworkCustomerRepository = $socialNetworkCustomerRepository;
    }

    public function execute()
    {
        $adapterId = $this->getRequest()->getParam('provider');

        try {
            $userProfile = $this->socialModel->getSocialUserProfile($adapterId);
            $customer = $this->customerRepository->get($userProfile['email']);
            if(isset($customer) && $customer->getId()) {
                // Get Customer Entity Model
                $customerModel = $this->customerModelFactory->create()->load($customer->getId());

                // Create new social network customer entity
                if(!$this->socialNetworkCustomerRepository->socialNetworkCustomerExists($userProfile, $adapterId)) {
                    $this->socialModel->createSocialLoginCustomer($userProfile, $adapterId, $customer->getId());
                }
            }
        }
        catch(\Magento\Framework\Exception\NoSuchEntityException $e) {
            // Create new Customer account
            $customerModel = $this->socialModel->createCustomerAccount($userProfile, $adapterId);
        }
        catch(\Exception $e){
            exit("Error: " . $e->getMessage());
        }

        $this->socialModel->refresh($customerModel);

        return $this->_appendJs();
    }

    /**
     * @param null $content
     * @return mixed
     */
    public function _appendJs($content = null)
    {
        /** @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        $raw = $resultRaw->setContents($content ?:
            "<script>
                    window.opener.location.reload(true);
                    window.close();
                </script>");

        return $raw;
    }
}