<?php

namespace OroCRM\Bundle\AnalyticsBundle\Builder;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\PropertyAccess\PropertyAccess;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use OroCRM\Bundle\AnalyticsBundle\Entity\RFMMetricCategory;
use OroCRM\Bundle\AnalyticsBundle\Model\AnalyticsAwareInterface;
use OroCRM\Bundle\AnalyticsBundle\Model\RFMAwareInterface;

class RFMBuilder implements AnalyticsBuilderInterface
{
    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * @var RFMProviderInterface[]
     */
    protected $providers = [];

    /**
     * @var array categories by channel
     */
    protected $categories = [];

    /**
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * @param RFMProviderInterface $provider
     */
    public function addProvider(RFMProviderInterface $provider)
    {
        $type = $provider->getType();

        if (!in_array($type, RFMMetricCategory::$types)) {
            throw new \InvalidArgumentException(
                sprintf('Expected one of "%s" type, "%s" given', implode(',', RFMMetricCategory::$types), $type)
            );
        }

        $this->providers[] = $provider;
    }

    /**
     * @return RFMProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($entity)
    {
        return $entity instanceof RFMAwareInterface;
    }

    /**
     * @param RFMAwareInterface $entity
     *
     * {@inheritdoc}
     */
    public function build(AnalyticsAwareInterface $entity)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->providers as $provider) {
            if ($provider->supports($entity)) {
                $value = $provider->getValue($entity);

                $type = $provider->getType();
                $entityIndex = $propertyAccessor->getValue($entity, $type);
                $index = $this->getIndex($entity, $type, $value);

                if ($index === $entityIndex) {
                    continue;
                }

                $propertyAccessor->setValue($entity, $type, $index);

                return true;
            }
        }

        return false;
    }

    /**
     * @param AnalyticsAwareInterface $entity
     * @param string $type
     * @param int $value
     *
     * @return int|null
     */
    protected function getIndex(AnalyticsAwareInterface $entity, $type, $value)
    {
        $channel = $entity->getDataChannel();
        if (!$channel) {
            return null;
        }

        $channelId = $this->doctrineHelper->getSingleEntityIdentifier($channel);
        if (!$channelId) {
            return null;
        }

        $categories = $this->getCategories($channelId, $type);
        if (!$categories) {
            return null;
        }

        foreach ($categories as $category) {
            $maxValue = $category->getMaxValue();
            if ($maxValue && $value > $maxValue) {
                continue;
            }

            if ($value <= $category->getMinValue()) {
                continue;
            }

            return $category->getIndex();
        }

        return null;
    }

    /**
     * @param int $channelId
     * @param string $type
     *
     * @return RFMMetricCategory[]
     */
    protected function getCategories($channelId, $type)
    {
        if (
            array_key_exists($channelId, $this->categories)
            && array_key_exists($type, $this->categories[$channelId])
        ) {
            return $this->categories[$channelId][$type];
        }

        $categories = $this->doctrineHelper
            ->getEntityRepository('OroCRMAnalyticsBundle:RFMMetricCategory')
            ->findBy(['channel' => $channelId, 'type' => $type], ['index' => Criteria::ASC]);

        $this->categories[$channelId][$type] = $categories;

        return $categories;
    }
}
