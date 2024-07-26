<?php
namespace Melazzo\AddressLabels\Plugin\Block;

use Closure;
use Magento\Checkout\Block\Checkout\LayoutProcessor as MagentoLayoutProcessor;

class LayoutProcessor
{
    /**
     * @param MagentoLayoutProcessor $subject
     * @param Closure $proceed
     * @param array $jsLayout
     * @return mixed
     */
    public function aroundProcess(
        MagentoLayoutProcessor $subject,
        Closure                $proceed,
        array                  $jsLayout
    )
    {
        $jsLayoutResult = $proceed($jsLayout);

        $shippingAddress = &$jsLayoutResult['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street'];

        unset($shippingAddress['label']);
        $shippingAddress['required'] = false;

        // Shipping fields street labels
        $shippingAddress['children']['0']['label'] = __('Street');
        $shippingAddress['children']['1']['label'] = __('Number');
        $shippingAddress['children']['2']['label'] = __('Additional Address Data');
        $shippingAddress['children']['3']['label'] = __('Neighbourhood');

        // Shipping fields street validation
        $shippingAddress['children']['0']['validation'] = $this->getFieldValidation();
        $shippingAddress['children']['1']['validation'] = $this->getFieldValidation();
        $shippingAddress['children']['2']['validation'] = $this->getFieldValidation(false);
        $shippingAddress['children']['3']['validation'] = $this->getFieldValidation();

        return $jsLayoutResult;
    }

    /**
     * @param bool $required
     * @return array
     */
    private function getFieldValidation(bool $required = true): array
    {
        return [
            'required-entry' => $required,
            'min_text_length' => 1,
            'max_text_length' => 255
        ];
    }
}
