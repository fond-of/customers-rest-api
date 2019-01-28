<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader as SprykerCustomerReader;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class CustomerReader extends SprykerCustomerReader
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomer(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        try {
            // set id as external reference
            $customerTransfer = (new CustomerTransfer())->setExternalReference($restRequest->getResource()->getId());

            return $this->customerClient->findCustomerByExternalReference($customerTransfer);
        } catch (Throwable $throwable) {
            // do nothing
        }

        return parent::findCustomer($restRequest);
    }
}
