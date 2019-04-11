<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Controller;

use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

use Spryker\Glue\CustomersRestApi\Controller\CustomerResourceController as SprykerCustomerResourceController;

/**
 * @method \FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerResourceController extends SprykerCustomerResourceController
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createFondOfCustomerReader()
            ->getCustomerByCustomerReference($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patchAction(RestRequestInterface $restRequest, RestCustomersAttributesTransfer $customerTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createCustomerWriter()
            ->updateCustomer($restRequest, $customerTransfer);
    }
}
