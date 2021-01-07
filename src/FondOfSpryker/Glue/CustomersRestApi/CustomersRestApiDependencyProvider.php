<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge;
use Spryker\Glue\CustomersRestApi\CustomersRestApiDependencyProvider as SprykerCustomersRestApiDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiDependencyProvider extends SprykerCustomersRestApiDependencyProvider
{
    public const ADDITIONAL_CLIENT_CUSTOMER = 'ADDITIONAL_CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addAdditionalCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAdditionalCustomerClient(Container $container): Container
    {
        $container[static::ADDITIONAL_CLIENT_CUSTOMER] = static function (Container $container) {
            return new CustomersRestApiToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}
