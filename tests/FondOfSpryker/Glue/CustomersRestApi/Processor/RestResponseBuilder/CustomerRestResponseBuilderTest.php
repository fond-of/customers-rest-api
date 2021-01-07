<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomerRestResponseBuilderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilder
     */
    protected $customerRestResponseBuilder;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->restResourceBuilderMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerRestResponseBuilder = new CustomerRestResponseBuilder($this->restResourceBuilderMock);
    }

    /**
     * @return void
     */
    public function testCreateCustomerNotFoundErrorResponse(): void
    {
        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addError')
            ->with(
                static::callback(
                    static function (RestErrorMessageTransfer $restErrorMessageTransfer) {
                        return $restErrorMessageTransfer->getStatus() === Response::HTTP_NOT_FOUND
                            && $restErrorMessageTransfer->getCode() === CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND
                            && $restErrorMessageTransfer->getDetail() === CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND;
                    }
                )
            )->willReturn($this->restResponseMock);

         static::assertEquals(
             $this->restResponseMock,
             $this->customerRestResponseBuilder->createCustomerNotFoundErrorResponse()
         );
    }

    /**
     * @return void
     */
    public function testCreateCustomerRestResponse(): void
    {
        $customerTransferData = [
            'id_customer' => 1,
            'customer_reference' => 'STORE-1',
            'email' => 'foo@bar.com',
        ];

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($customerTransferData);

        $this->customerTransferMock->expects(static::atLeastOnce())
            ->method('getCustomerReference')
            ->willReturn($customerTransferData['customer_reference']);

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResource')
            ->with(
                CustomersRestApiConfig::RESOURCE_CUSTOMERS,
                $customerTransferData['customer_reference'],
                static::callback(
                    static function (RestCustomersResponseAttributesTransfer $restCustomersResponseAttributesTransfer) use ($customerTransferData) {
                        return $restCustomersResponseAttributesTransfer->getEmail() === $customerTransferData['email'];
                    }
                )
            )->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('setPayload')
            ->with($this->customerTransferMock)
            ->willReturn($this->restResourceMock);

        $this->restResourceBuilderMock->expects(static::atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseMock);

        $this->restResponseMock->expects(static::atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceMock)->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->customerRestResponseBuilder->createCustomerRestResponse($this->customerTransferMock)
        );
    }
}
