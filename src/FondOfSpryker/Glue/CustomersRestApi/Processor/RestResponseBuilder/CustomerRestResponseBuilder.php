<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomerRestResponseBuilder implements CustomerRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCustomerRestResource(
        CustomerTransfer $customerTransfer
    ): RestResourceInterface {
        $restCustomersResponseAttributesTransfer = (new RestCustomersResponseAttributesTransfer())
            ->fromArray($customerTransfer->toArray(), true);

        return $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerTransfer->getCustomerReference(),
            $restCustomersResponseAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerRestResponse(
        CustomerTransfer $customerTransfer
    ): RestResponseInterface {
        $customerRestResource = $this->createCustomerRestResource($customerTransfer)
            ->setPayload($customerTransfer);

        return $this->restResourceBuilder->createRestResponse()
            ->addResource($customerRestResource);
    }
}
