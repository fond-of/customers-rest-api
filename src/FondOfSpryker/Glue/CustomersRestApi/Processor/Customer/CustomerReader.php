<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use Exception;
use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    protected $customerReader;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface
     */
    protected $customerRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface $customerReader
     * @param \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface $customerRestResponseBuilder
     */
    public function __construct(
        CustomerReaderInterface $customerReader,
        CustomersRestApiToCustomerClientInterface $customerClient,
        CustomerRestResponseBuilderInterface $customerRestResponseBuilder
    ) {
        $this->customerReader = $customerReader;
        $this->customerClient = $customerClient;
        $this->customerRestResponseBuilder = $customerRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restUserTransfer = $restRequest->getRestUser();

        if ($restUserTransfer === null) {
            return $this->customerRestResponseBuilder->createCustomerNotFoundErrorResponse();
        }

        $customerReference = $restRequest->getResource()->getId();
        $currentCustomerReference = $restUserTransfer->getNaturalIdentifier();

        if ($customerReference !== null && $customerReference !== $currentCustomerReference) {
            return $this->customerRestResponseBuilder->createCustomerNotFoundErrorResponse();
        }

        $customerResponseTransfer = $this->getCurrentCustomer($restRequest);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->customerRestResponseBuilder->createCustomerNotFoundErrorResponse();
        }

        return $this->customerRestResponseBuilder->createCustomerRestResponse(
            $customerResponseTransfer->getCustomerTransfer()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomer(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        return $this->customerReader->findCustomer($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCurrentCustomer(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setIsSuccess(false)
            ->setHasCustomer(false);

        $restUserTransfer = $restRequest->getRestUser();

        if ($restUserTransfer === null || $restUserTransfer->getSurrogateIdentifier() === null) {
            return $customerResponseTransfer;
        }

        try {
            $customerTransfer = $this->customerClient->getCustomerById($restUserTransfer->getSurrogateIdentifier());
            $customerResponseTransfer->setCustomerTransfer($customerTransfer);
        } catch (Exception $exception) {
            return $customerResponseTransfer;
        }

        return $customerResponseTransfer->setIsSuccess(true)
            ->setHasCustomer(true);
    }
}
