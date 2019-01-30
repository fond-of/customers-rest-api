<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\CustomersRestApi\CustomersRestApiDependencyProvider as SprykerCustomersRestApiDependencyProvider;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiDependencyProvider extends SprykerCustomersRestApiDependencyProvider
{
    public const CLIENT_CUSTOMER_B2B = 'CLIENT_CUSTOMER_B2B';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerB2bClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerB2bClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER_B2B] = function (Container $container) {
            return new CustomersRestApiToCustomerClientBridge($container->getLocator()->customerB2b()->client());
        };

        return $container;
    }
}
