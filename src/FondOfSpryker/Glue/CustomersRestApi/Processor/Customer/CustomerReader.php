<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader as SprykerCustomerReader;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class CustomerReader extends SprykerCustomerReader implements CustomerReaderInterface
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

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
        $this->customerClient = $customerClient;
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
            $customerTransfer = $this->customerClient->findCustomerByExternalReference($customerTransfer);
            if ($customerTransfer->getIsSuccess()) {
                return $customerTransfer;
            }
        } catch (Throwable $throwable) {
            // do nothing
        }

        return parent::findCustomer($restRequest);
    }
}
