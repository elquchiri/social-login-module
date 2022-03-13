<?php

namespace ElQuchiri\SocialLogin\Controller\Social;

use Magento\Framework\App\Action\Context;

class Redirect extends \Magento\Framework\App\Action\Action
{

    private $resultRawFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    )
    {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
    }

    public function execute()
    {

    }

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