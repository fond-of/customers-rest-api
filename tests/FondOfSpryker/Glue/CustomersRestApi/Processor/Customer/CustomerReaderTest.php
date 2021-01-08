<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use Codeception\Test\Unit;
use Exception;
use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerReaderTest extends Unit
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerReaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClientMock;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerRestResponseBuilderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected $customerResponseTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \Generated\Shared\Transfer\RestUserTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $restUserTransferMock;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader
     */
    protected $customerReader;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->customerReaderMock = $this->getMockBuilder(CustomerReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerClientMock = $this->getMockBuilder(CustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerRestResponseBuilderMock = $this->getMockBuilder(CustomerRestResponseBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restRequestMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerResponseTransferMock = $this->getMockBuilder(CustomerResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restUserTransferMock = $this->getMockBuilder(RestUserTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerReader = new CustomerReader(
            $this->customerReaderMock,
            $this->customerClientMock,
            $this->customerRestResponseBuilderMock
        );
    }

    /**
     * @return void
     */
    public function testFindCustomer(): void
    {
        $this->customerReaderMock->expects(static::atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestMock)
            ->willReturn($this->customerResponseTransferMock);

        static::assertEquals(
            $this->customerResponseTransferMock,
            $this->customerReader->findCustomer($this->restRequestMock)
        );
    }

    /**
     * @return void
     */
    public function testGetCurrentCustomer(): void
    {
        $surrogateIdentifier = 1;

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($surrogateIdentifier);

        $this->customerClientMock->expects(static::atLeastOnce())
            ->method('getCustomerById')
            ->with($surrogateIdentifier)
            ->willReturn($this->customerTransferMock);

        $customerResponseTransfer = $this->customerReader->getCurrentCustomer($this->restRequestMock);

        static::assertTrue($customerResponseTransfer->getHasCustomer());
        static::assertTrue($customerResponseTransfer->getIsSuccess());
        static::assertEquals($this->customerTransferMock, $customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testGetCurrentCustomerWithException(): void
    {
        $surrogateIdentifier = 1;

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($surrogateIdentifier);

        $this->customerClientMock->expects(static::atLeastOnce())
            ->method('getCustomerById')
            ->with($surrogateIdentifier)
            ->willThrowException(new Exception('foo'));

        $customerResponseTransfer = $this->customerReader->getCurrentCustomer($this->restRequestMock);

        static::assertFalse($customerResponseTransfer->getHasCustomer());
        static::assertFalse($customerResponseTransfer->getIsSuccess());
        static::assertEquals(null, $customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testGetCurrentCustomerWithEmptyRestUser(): void
    {
        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn(null);

        $this->restUserTransferMock->expects(static::never())
            ->method('getSurrogateIdentifier');

        $this->customerClientMock->expects(static::never())
            ->method('getCustomerById');

        $customerResponseTransfer = $this->customerReader->getCurrentCustomer($this->restRequestMock);

        static::assertFalse($customerResponseTransfer->getHasCustomer());
        static::assertFalse($customerResponseTransfer->getIsSuccess());
        static::assertEquals(null, $customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReference(): void
    {
        $customerReference = 'STORE-1';
        $surrogateIdentifier = 1;

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->customerRestResponseBuilderMock->expects(static::never())
            ->method('createCustomerNotFoundErrorResponse');

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($customerReference);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($customerReference);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($surrogateIdentifier);

        $this->customerClientMock->expects(static::atLeastOnce())
            ->method('getCustomerById')
            ->with($surrogateIdentifier)
            ->willReturn($this->customerTransferMock);

        $this->customerRestResponseBuilderMock->expects(static::atLeastOnce())
            ->method('createCustomerRestResponse')
            ->with($this->customerTransferMock)
            ->willReturn($this->restResponseMock);

        static::assertEquals(
            $this->restResponseMock,
            $this->customerReader->getCustomerByCustomerReference($this->restRequestMock)
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReferenceWithEmptyRestUser(): void
    {
        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn(null);

        $this->customerRestResponseBuilderMock->expects(static::atLeastOnce())
            ->method('createCustomerNotFoundErrorResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::never())
            ->method('getResource');

        $this->restResourceMock->expects(static::never())
            ->method('getId');

        $this->restUserTransferMock->expects(static::never())
            ->method('getNaturalIdentifier');

        $this->restUserTransferMock->expects(static::never())
            ->method('getSurrogateIdentifier');

        $this->customerClientMock->expects(static::never())
            ->method('getCustomerById');

        $this->customerRestResponseBuilderMock->expects(static::never())
            ->method('createCustomerRestResponse');

        static::assertEquals(
            $this->restResponseMock,
            $this->customerReader->getCustomerByCustomerReference($this->restRequestMock)
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReferenceWithWrongCustomerReference(): void
    {
        $customerReference = 'STORE-1';
        $currentCustomerReference = 'STORE-2';

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->customerRestResponseBuilderMock->expects(static::atLeastOnce())
            ->method('createCustomerNotFoundErrorResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($customerReference);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($currentCustomerReference);

        $this->restUserTransferMock->expects(static::never())
            ->method('getSurrogateIdentifier');

        $this->customerClientMock->expects(static::never())
            ->method('getCustomerById');

        $this->customerRestResponseBuilderMock->expects(static::never())
            ->method('createCustomerRestResponse');

        static::assertEquals(
            $this->restResponseMock,
            $this->customerReader->getCustomerByCustomerReference($this->restRequestMock)
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReferenceWithInvalidData(): void
    {
        $customerReference = 'STORE-1';
        $surrogateIdentifier = 1;

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->customerRestResponseBuilderMock->expects(static::atLeastOnce())
            ->method('createCustomerNotFoundErrorResponse')
            ->willReturn($this->restResponseMock);

        $this->restRequestMock->expects(static::atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceMock);

        $this->restResourceMock->expects(static::atLeastOnce())
            ->method('getId')
            ->willReturn($customerReference);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($customerReference);

        $this->restUserTransferMock->expects(static::atLeastOnce())
            ->method('getSurrogateIdentifier')
            ->willReturn($surrogateIdentifier);

        $this->customerClientMock->expects(static::atLeastOnce())
            ->method('getCustomerById')
            ->with($surrogateIdentifier)
            ->willThrowException(new Exception('foo'));

        $this->customerRestResponseBuilderMock->expects(static::never())
            ->method('createCustomerRestResponse');

        static::assertEquals(
            $this->restResponseMock,
            $this->customerReader->getCustomerByCustomerReference($this->restRequestMock)
        );
    }
}
