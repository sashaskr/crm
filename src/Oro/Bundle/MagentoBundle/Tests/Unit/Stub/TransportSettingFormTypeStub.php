<?php

namespace Oro\Bundle\MagentoBundle\Tests\Unit\Stub;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TransportSettingFormTypeStub extends AbstractType
{
    const NAME = 'oro_magento_soap_transport_setting_form_type';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apiKey', 'password')
            ->add(
                'websiteId',
                'oro_magento_website_select',
                [
                    // TODO: Remove 'choices_as_values' option in scope of BAP-15236
                    'choices_as_values' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
