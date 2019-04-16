<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader as SprykerCustomerReader;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class CustomerReader extends SprykerCustomerReader implements CustomerReaderInterface
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClientFondOf;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface $customerResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface $restApiError
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface $restApiValidator
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        CustomerResourceMapperInterface $customerResourceMapper,
        RestApiErrorInterface $restApiError,
        RestApiValidatorInterface $restApiValidator
    ) {
        parent::__construct($restResourceBuilder, $customerClient, $customerResourceMapper, $restApiError, $restApiValidator);
        $this->customerClientFondOf = $customerClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomer(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        try {
            // set reference id as external reference
            $customerTransfer = (new CustomerTransfer())->setExternalReference($restRequest->getResource()->getId());
            $customerTransfer = $this->customerClientFondOf->findCustomerByExternalReference($customerTransfer);
            if ($customerTransfer->getIsSuccess()) {
                return $customerTransfer;
            }
        } catch (Throwable $throwable) {
            // do nothing
        }

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($restRequest->getResource()->getId());

        return $this->customerClientFondOf->findCustomerByReference($customerTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $customerResourceId = $restRequest->getResource()->getId();

        $customerResponseTransfer = $this->getCurrentCustomer($restRequest);
        if ($customerResourceId) {
            if (!$this->restApiValidator->isSameCustomerReference($restRequest)) {
                return $this->restApiError->addCustomerNotFoundError($restResponse);
            }

            $customerResponseTransfer = $this->findCustomer($restRequest);
        }

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->restApiError->addCustomerNotFoundError($restResponse);
        }

        $restCustomersResponseAttributesTransfer = $this
            ->customerResourceMapper
            ->mapCustomerTransferToRestCustomersResponseAttributesTransfer($customerResponseTransfer->getCustomerTransfer());

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerResponseTransfer->getCustomerTransfer()->getCustomerReference(),
            $restCustomersResponseAttributesTransfer
        );

        $restResource->setPayload($customerResponseTransfer->getCustomerTransfer());

        $restResponse->addResource($restResource);

        return $restResponse;
    }
}
