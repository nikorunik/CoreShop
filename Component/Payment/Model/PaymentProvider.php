<?php

namespace CoreShop\Component\Payment\Model;

use CoreShop\Component\Resource\Model\AbstractResource;
use CoreShop\Component\Resource\Model\ToggleableTrait;
use CoreShop\Component\Resource\Model\TranslatableTrait;

class PaymentProvider extends AbstractResource implements PaymentProviderInterface
{
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as initializeTranslationsCollection;
    }

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var int
     */
    protected $position = 1;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getName($language = null)
    {
        return $this->getTranslation($language)->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name, $language = null)
    {
        $this->getTranslation($language)->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription($language = null)
    {
        return $this->getTranslation($language)->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description, $language = null)
    {
        $this->getTranslation($language)->setDescription($description);
    }

    /**
     * {@inheritdoc}
     */
    public function getInstructions($language = null)
    {
        return $this->getTranslation($language)->getInstructions();
    }

    /**
     * {@inheritdoc}
     */
    public function setInstructions($instructions, $language = null)
    {
        $this->getTranslation($language)->setInstructions($instructions);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new PaymentProviderTranslation();
    }
}