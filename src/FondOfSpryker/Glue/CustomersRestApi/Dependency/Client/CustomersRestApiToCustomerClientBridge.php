<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use FondOfSpryker\Client\CustomerB2b\CustomerClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge as SprykerCustomersRestApiToCustomerClientBridge;

class CustomersRestApiToCustomerClientBridge extends SprykerCustomersRestApiToCustomerClientBridge implements CustomersRestApiToCustomerClientInterface
{
    /**
     * @var \FondOfSpryker\Client\CustomerB2b\CustomerClientInterface
     */
    protected $fondOfCustomerClient;

    /**
     * @param \FondOfSpryker\Client\CustomerB2b\CustomerClientInterface $customerClient
     */
    public function __construct(CustomerClientInterface $customerClient)
    {
        parent::__construct($customerClient);
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByExternalReference(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->fondOfCustomerClient->findCustomerByExternalReference($customerTransfer);
    }
}
