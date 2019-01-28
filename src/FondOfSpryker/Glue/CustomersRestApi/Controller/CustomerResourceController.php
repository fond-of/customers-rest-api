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
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates customer data."
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Failed to save customer.",
     *              "403": "Unauthorized request.",
     *              "404": "Customer not found."
     *          }
     *     }
     * })
     *
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
