<?php


namespace ElQuchiri\SocialLogin\Block\System;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;


class RedirectUrl extends FormField
{
    private $socialHelper;

    /**
     * RedirectUrl constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        \ElQuchiri\SocialLogin\Helper\Social $socialHelper,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->socialHelper = $socialHelper;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @throws LocalizedException
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $elementId   = explode('_', $element->getHtmlId());
        $redirectUrl = $this->socialHelper->getSocialRedirectUrl($elementId[4]);
        $html        = '<input style="opacity:1;" readonly id="' . $element->getHtmlId() . '" class="input-text admin__control-text" value="' . $redirectUrl . '" onclick="this.select()" type="text">';

        return $html;
    }
}
