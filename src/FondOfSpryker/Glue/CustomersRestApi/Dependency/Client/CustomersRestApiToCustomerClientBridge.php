<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge as SprykerCustomersRestApiToCustomerClientBridge;

class CustomersRestApiToCustomerClientBridge extends SprykerCustomersRestApiToCustomerClientBridge implements CustomersRestApiToCustomerClientInterface
{
    /**
     * @var \FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface
     */
    protected $fondOfCustomerClient;

    /**
     * @param \FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface $customerClient
     */
    public function __construct(CustomerB2bClientInterface $customerClient)
    {
        parent::__construct($customerClient);
        $this->fondOfCustomerClient = $customerClient;
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerById(CustomerTransfer $customerTransfer): ?CustomerTransfer
    {
        return $this->fondOfCustomerClient->findCustomerById($customerTransfer);
    }
}
