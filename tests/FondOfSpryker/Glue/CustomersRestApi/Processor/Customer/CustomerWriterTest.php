<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Processor\Customer;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostCreatePluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerWriterTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriter
     */
    protected $customerWriter;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected $restRequestInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestCustomersAttributesTransfer
     */
    protected $restCustomersAttributesTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customersRestApiToCustomerClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    protected $customerReaderInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderInterfaceMock;

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostCreatePluginInterface
     */
    protected $customerPostCreatePluginInterfaceMock;

    /**
     * @var \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostCreatePluginInterface[]
     */
    protected $customerPostCreatePlugins;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected $restResponseInterfaceMock;

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var string
     */
    protected $customerReference;

    /**
     * @var array
     */
    private $modifiedArray;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer
     */
    protected $restCustomersResponseAttributesTransferMock;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->customersRestApiToCustomerClientInterfaceMock = $this->getMockBuilder(CustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerReaderInterfaceMock = $this->getMockBuilder(CustomerReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceBuilderInterfaceMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
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

        $this->customerPostCreatePluginInterfaceMock = $this->getMockBuilder(CustomerPostCreatePluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerPostCreatePlugins = [
            $this->customerPostCreatePluginInterfaceMock,
        ];

        $this->restRequestInterfaceMock = $this->getMockBuilder(RestRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restCustomersAttributesTransferMock = $this->getMockBuilder(RestCustomersAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResponseInterfaceMock = $this->getMockBuilder(RestResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->id = 'id';

        $this->customerResponseTransferMock = $this->getMockBuilder(CustomerResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerReference = 'customer-reference';

        $this->modifiedArray = [];

        $this->restCustomersResponseAttributesTransferMock = $this->getMockBuilder(RestCustomersResponseAttributesTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->password = 'password';

        $this->customerWriter = new CustomerWriter(
            $this->customersRestApiToCustomerClientInterfaceMock,
            $this->customerReaderInterfaceMock,
            $this->restResourceBuilderInterfaceMock,
            $this->customerResourceMapperInterfaceMock,
            $this->restApiErrorInterfaceMock,
            $this->restApiValidatorInterfaceMock,
            $this->customerPostCreatePlugins
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomer(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn(null);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(true);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(true);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('modifiedToArray')
            ->with(true, true)
            ->willReturn($this->modifiedArray);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerTransfer')
            ->willReturn($this->customerTransferMock);

        $this->customerTransferMock->expects($this->atLeastOnce())
            ->method('fromArray')
            ->with($this->modifiedArray, true)
            ->willReturnSelf();

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('updateCustomer')
            ->with($this->customerTransferMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getIsSuccess')
            ->willReturn(true);

        $this->customerResourceMapperInterfaceMock->expects($this->atLeastOnce())
            ->method('mapCustomerTransferToRestCustomersResponseAttributesTransfer')
            ->with($this->customerTransferMock)
            ->willReturn($this->restCustomersResponseAttributesTransferMock);

        $this->customerTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerReference')
            ->willReturn($this->customerReference);

        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResource')
            ->with(
                CustomersRestApiConfig::RESOURCE_CUSTOMERS,
                $this->customerReference,
                $this->restCustomersResponseAttributesTransferMock
            )
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResponseInterfaceMock->expects($this->atLeastOnce())
            ->method('addResource')
            ->with($this->restResourceInterfaceMock)
            ->willReturnSelf();

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerCustomerReferenceMissing(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(null);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerReferenceMissingError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerPasswordsDoNotMatch(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn($this->password);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getConfirmPassword')
            ->willReturn('other-password');

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addPasswordsDoNotMatchError')
            ->with(
                $this->restResponseInterfaceMock,
                RestCustomersAttributesTransfer::PASSWORD,
                RestCustomersAttributesTransfer::CONFIRM_PASSWORD
            )->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerCustomerNotFound(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn(null);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(false);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerNotFoundError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerCustomerUnauthorized(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn(null);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(true);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(false);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('getCurrentCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerTransfer')
            ->willReturn($this->customerTransferMock);

        $this->customerTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerReference')
            ->willReturn($this->customerReference);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerUnauthorizedError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerCustomerResponseIsNotSuccess(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn(null);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(true);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(true);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('modifiedToArray')
            ->with(true, true)
            ->willReturn($this->modifiedArray);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerTransfer')
            ->willReturn($this->customerTransferMock);

        $this->customerTransferMock->expects($this->atLeastOnce())
            ->method('fromArray')
            ->with($this->modifiedArray, true)
            ->willReturnSelf();

        $this->customersRestApiToCustomerClientInterfaceMock->expects($this->atLeastOnce())
            ->method('updateCustomer')
            ->with($this->customerTransferMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getIsSuccess')
            ->willReturn(false);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('processCustomerErrorOnUpdate')
            ->with($this->restResponseInterfaceMock, $this->customerResponseTransferMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testUpdateCustomerIsAdminCatch(): void
    {
        $this->restResourceBuilderInterfaceMock->expects($this->atLeastOnce())
            ->method('createRestResponse')
            ->willReturn($this->restResponseInterfaceMock);

        $this->restRequestInterfaceMock->expects($this->atLeastOnce())
            ->method('getResource')
            ->willReturn($this->restResourceInterfaceMock);

        $this->restResourceInterfaceMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($this->id);

        $this->restCustomersAttributesTransferMock->expects($this->atLeastOnce())
            ->method('getPassword')
            ->willReturn(null);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getHasCustomer')
            ->willReturn(true);

        $this->restApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('isSameCustomerReference')
            ->with($this->restRequestInterfaceMock)
            ->willReturn(false);

        $this->customerReaderInterfaceMock->expects($this->atLeastOnce())
            ->method('getCurrentCustomer')
            ->with($this->restRequestInterfaceMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->customerResponseTransferMock->expects($this->atLeastOnce())
            ->method('getCustomerTransfer')
            ->willReturn(null);

        $this->restApiErrorInterfaceMock->expects($this->atLeastOnce())
            ->method('addCustomerUnauthorizedError')
            ->with($this->restResponseInterfaceMock)
            ->willReturn($this->restResponseInterfaceMock);

        $this->assertInstanceOf(
            RestResponseInterface::class,
            $this->customerWriter->updateCustomer(
                $this->restRequestInterfaceMock,
                $this->restCustomersAttributesTransferMock
            )
        );
    }
}
