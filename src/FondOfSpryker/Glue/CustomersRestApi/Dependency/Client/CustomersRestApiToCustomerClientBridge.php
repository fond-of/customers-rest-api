<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge as SprykerCustomersRestApiToCustomerClientBridge;

class CustomersRestApiToCustomerClientBridge implements CustomersRestApiToCustomerClientInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct(CustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param int $idCustomer
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById(int $idCustomer): CustomerTransfer
    {
        return $this->customerClient->getCustomerById($idCustomer);
    }
}
