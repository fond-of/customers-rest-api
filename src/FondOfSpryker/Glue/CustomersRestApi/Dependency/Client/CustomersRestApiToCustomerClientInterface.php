<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface as SprykerCustomersRestApiToCustomerClientInterface;

interface CustomersRestApiToCustomerClientInterface extends SprykerCustomersRestApiToCustomerClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByExternalReference(CustomerTransfer $customerTransfer): CustomerResponseTransfer;
}
