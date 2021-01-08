<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader;
use FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilder;
use Spryker\Glue\CustomersRestApi\CustomersRestApiFactory as SprykerCustomersRestApiFactory;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;

class CustomersRestApiFactory extends SprykerCustomersRestApiFactory
{
    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        $customerRestResponseBuilder = new CustomerRestResponseBuilder($this->getResourceBuilder());

        return new CustomerReader(
            parent::createCustomerReader(),
            $this->getAdditionalCustomerClient(),
            $customerRestResponseBuilder
        );
    }

    /**
     * @return \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected function getAdditionalCustomerClient(): CustomersRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::ADDITIONAL_CLIENT_CUSTOMER);
    }
}
