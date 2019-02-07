<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader;
use FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriter;
use Spryker\Glue\CustomersRestApi\CustomersRestApiFactory as SprykerCustomersRestApiFactory;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriterInterface;

class CustomersRestApiFactory extends SprykerCustomersRestApiFactory
{
    /**
     * @return \FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    public function createFondOfCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader(
            $this->getResourceBuilder(),
            $this->getFondOfCustomerClient(),
            $this->createCustomerResourceMapper(),
            $this->createRestApiError(),
            $this->createRestApiValidator()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriterInterface
     */
    public function createCustomerWriter(): CustomerWriterInterface
    {
        return new CustomerWriter(
            $this->getCustomerClient(),
            $this->createFondOfCustomerReader(),
            $this->getResourceBuilder(),
            $this->createCustomerResourceMapper(),
            $this->createRestApiError(),
            $this->createRestApiValidator(),
            $this->getCustomerPostRegisterPlugins()
        );
    }

    /**
     * @throws
     * @return \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    public function getFondOfCustomerClient(): CustomersRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER_B2B);
    }
}
