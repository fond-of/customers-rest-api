<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CustomerRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerRestResponse(
        CustomerTransfer $customerTransfer
    ): RestResponseInterface;
}
