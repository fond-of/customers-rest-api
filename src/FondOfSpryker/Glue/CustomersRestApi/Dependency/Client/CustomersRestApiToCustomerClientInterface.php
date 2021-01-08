<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomersRestApiToCustomerClientInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById(int $idCustomer): CustomerTransfer;
}
