<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Throwable;

class CustomerReaderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader
     */
    protected $customerReader;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderInterface;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customersRestApiToCustomerClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface
     */
    protected $customerResourceMapperInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected $restApiErrorInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface
     */
    protected $restApiValidatorInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $restResourceInterfaceMock;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected $customerResponseTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Throwable
     */
    protected $throwableMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestUserTransfer
     */
    protected $restUserTransferMock;

    /**
     * @var string
     */
    protected $naturalIdentifier;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer
     */
    protected $restCustomersResponseAttributesTransferMock;

    /**
     * @var string
     */
    protected $customerReference;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->restResourceBuilderInterface = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiToCustomerClientInterfaceMock = $this->getMockBuilder(CustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerResourceMapperInterfaceMock = $this->getMockBuilder(CustomerResourceMapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiErrorInterfaceMock = $this->getMockBuilder(RestApiErrorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restApiValidatorInterfaceMock = $this->getMockBuilder(RestApiValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restRequestInterfaceMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->id = 'id';

        $this->customerResponseTransferMock = $this->getMockBuilder(CustomerResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->throwableMock = $this->getMockBuilder(Throwable::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseInterfaceMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restUserTransferMock = $this->getMockBuilder(RestUserTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->naturalIdentifier = 'natural-identifier';

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restCustomersResponseAttributesTransferMock = $this->getMockBuilder(RestCustomersResponseAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerReference = 'customer-reference';

        $this->customerReader = new CustomerReader(
            $this->restResourceBuilderInterface,
            $this->customersRestApiToCustomerClientInterfaceMock,
            $this->customerResourceMapperInterfaceMock,
            $this->restApiErrorInterfaceMock,
            $this->restApiValidatorInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testFindCustomer(): void
    {
        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByExternalReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getIsSuccess')
            ->willReturn(true);

        $this->assertInstanceOf(
            CustomerResponseTransfer::class,
            $this->customerReader->findCustomer(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testFindCustomerCatch(): void
    {
        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByExternalReference')
            ->willThrowException($this->throwableMock);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->assertInstanceOf(
            CustomerResponseTransfer::class,
            $this->customerReader->findCustomer(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReference(): void
    {
        $this->restResourceBuilderInterface->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($this->naturalIdentifier);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(true);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByExternalReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getIsSuccess')
            ->willReturn(true);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(true);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerTransfer')
            ->willReturn($this->customerTransferMock);

        $this->customerResourceMapperInterfaceMock->expects($this->atLeastOnce())
            ->method('mapCustomerTransferToRestCustomersResponseAttributesTransfer')
            ->with($this->customerTransferMock)
            ->willReturn($this->restCustomersResponseAttributesTransferMock);

        $this->customerTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerReference')
            ->willReturn($this->customerReference);

        $this->restResourceBuilderInterface->expects($this->atLeastOnce())
            ->method('createRestResource')
            ->with(
                CustomersRestApiConfig::RESOURCE_CUSTOMERS,
                $this->customerReference,
                $this->restCustomersResponseAttributesTransferMock
            )->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('setPayload')
            ->willReturnSelf();

        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceInterfaceMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerReader->getCustomerByCustomerReference(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReferenceCustomerNotFound(): void
    {
        $this->restResourceBuilderInterface->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($this->naturalIdentifier);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(false);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerNotFoundError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerReader->getCustomerByCustomerReference(
                $this->restRequestInterfaceMock
            )
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCustomerReferenceCustomerNotFoundNoCustomer(): void
    {
        $this->restResourceBuilderInterface->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getRestUser')
            ->willReturn($this->restUserTransferMock);

        $this->restUserTransferMock->expects($this->atLeastOnce())
            ->method('getNaturalIdentifier')
            ->willReturn($this->naturalIdentifier);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(true);

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByExternalReference')
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getIsSuccess')
            ->willReturn(true);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(false);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerNotFoundError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerReader->getCustomerByCustomerReference(
                $this->restRequestInterfaceMock
            )
        );
    }
}
