<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriter as SprykerCustomerWriter;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Shared\Log\LoggerTrait;

class CustomerWriter extends SprykerCustomerWriter implements CustomerWriterInterface
{
    use LoggerTrait;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomerAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateCustomer(
        RestRequestInterface $restRequest,
        RestCustomersAttributesTransfer $restCustomerAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->restApiError->addCustomerReferenceMissingError($restResponse);
        }

        if ($restCustomerAttributesTransfer->getPassword()
            && $restCustomerAttributesTransfer->getPassword() !== $restCustomerAttributesTransfer->getConfirmPassword()) {
            return $this->restApiError->addPasswordsDoNotMatchError(
                $restResponse,
                RestCustomersAttributesTransfer::PASSWORD,
                RestCustomersAttributesTransfer::CONFIRM_PASSWORD
            );
        }

        $customerResponseTransfer = $this->customerReader->findCustomer($restRequest);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->restApiError->addCustomerNotFoundError($restResponse);
        }

        if (!$this->restApiValidator->isSameCustomerReference($restRequest) && !$this->isAdmin($restRequest)) {
            return $this->restApiError->addCustomerUnauthorizedError($restResponse);
        }

        $customerResponseTransfer->getCustomerTransfer()->fromArray(
            $this->getCustomerData($restCustomerAttributesTransfer),
            true
        );

        $customerResponseTransfer = $this->customerClient->updateCustomer($customerResponseTransfer->getCustomerTransfer());

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->restApiError->processCustomerErrorOnUpdate(
                $restResponse,
                $customerResponseTransfer
            );
        }

        $restCustomersResponseAttributesTransfer = $this->customerResourceMapper
            ->mapCustomerTransferToRestCustomersResponseAttributesTransfer(
                $customerResponseTransfer->getCustomerTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerResponseTransfer->getCustomerTransfer()->getCustomerReference(),
            $restCustomersResponseAttributesTransfer
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function isAdmin(RestRequestInterface $restRequest): bool
    {
       try {
           $currentCustomer = $this->customerReader->getCurrentCustomer($restRequest);
           if ($currentCustomer->getCustomerTransfer()->getCustomerReference() === 'PS--1') {
               return true;
           }
       } catch (\Throwable $throwable) {
            // return false
       }

       return false;
    }
}
