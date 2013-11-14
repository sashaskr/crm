<?php

namespace OroCRM\Bundle\MagentoBundle\Provider;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;

class CustomerConnector extends AbstractConnector implements CustomerConnectorInterface
{
    const DEFAULT_SYNC_RANGE = '1 week';

    /**
     * {@inheritdoc}
     */
    public function sync($oneWay = self::SYNC_DIRECTION_PULL, $params = [])
    {
        $channelSettings = $this->channel->getSettings();

        if (empty($channelSettings['last_sync_date'])) {
            throw new InvalidConfigurationException('Last (starting) sync date can\'t be empty');
        } else {
            $lastSyncDate = new \DateTime($channelSettings['last_sync_date']);
        }

        if (empty($channelSettings['sync_range'])) {
            throw new InvalidConfigurationException('Sync range can\'t be empty');
        } else {
            $syncRange = \DateInterval::createFromDateString($channelSettings['sync_range']);
        }

        $filters = function ($startDate, $endDate) {
            return [
                ['complex_filter' => [
                        [
                            'key'   => 'created_at',
                            'value' => ['key'   => 'gteq', 'value' => $startDate],
                        ],
                        [
                            'key'   => 'created_at',
                            'value' => ['key'   => 'lt', 'value' => $endDate],
                        ],
                    ]
                ]
            ];
        };

        $startDate = $lastSyncDate;
        $endDate = $lastSyncDate->add($syncRange);
        do {
            $hasData = true;

            // TODO: implement bi-directional sync

            $batchData = $this->getCustomersList($filters($startDate, $endDate));

            if (!empty($batchData)) {
                $this->processSyncBatch($batchData);
            } else {
                $hasData = false;
            }

            // move date range, from end to start, allow new customers to be imported first
            $endDate = $startDate;

        } while ($hasData);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomersList($filters = [])
    {
        return $this->call(CustomerConnectorInterface::ACTION_CUSTOMER_LIST, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerData($id, $isAddressesIncluded = false, $isGroupsIncluded = false, $onlyAttributes = [])
    {
        $result = $this->call(CustomerConnectorInterface::ACTION_CUSTOMER_INFO, [$id, $onlyAttributes]);

        if ($isAddressesIncluded) {
            $result->addresses = $this->getCustomerAddressData($id);
        }

        if ($isGroupsIncluded) {
            $result->groups = $this->getCustomerGroups($result->group_id);
            $result->group_name = $result->groups[$result->group_id];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerAddressData($customerId)
    {
        return $this->call(CustomerConnectorInterface::ACTION_ADDRESS_LIST, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroups($groupId = null)
    {
        $result = $this->call(CustomerConnectorInterface::ACTION_GROUP_LIST);

        $groups = [];
        foreach ($result as $item) {
            $groups[$item->customer_group_id] = $item->customer_group_code;
        }

        if (!is_null($groupId) && isset($groups[$groupId])) {
            $result = [$groupId => $groups[$groupId]];
        } else {
            $result = $groups;
        }

        return  $result;
    }

    /**
     * @return mixed
     */
    public function getStoresData()
    {
        return $this->call(CustomerConnectorInterface::ACTION_STORE_LIST);
    }

    /**
     * {@inheritdoc}
     */
    public function saveCustomerData()
    {
        // TODO: implement create/update customer data
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function saveCustomerAddress()
    {
        // TODO: implement create/update customer address
    }
}
