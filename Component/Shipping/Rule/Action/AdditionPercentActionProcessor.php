<?php

namespace CoreShop\Component\Shipping\Rule\Action;

use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Core\Model\CarrierInterface;

class AdditionPercentActionProcessor implements CarrierPriceActionProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPrice(CarrierInterface $carrier, AddressInterface $address, array $configuration, $withTax = true)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getModification(CarrierInterface $carrier, AddressInterface $address, $price, array $configuration)
    {
        return ($price * ($configuration['percent'] / 100));
    }
}