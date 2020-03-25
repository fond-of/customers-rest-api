<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use Codeception\Test\Unit;
use FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomersRestApiToCustomerClientBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge
     */
    protected $customersRestApiToCustomerClientBridge;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\CustomerB2b\CustomerB2bClientInterface
     */
    protected $customerB2bClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected $customerResponseTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->customerB2bClientInterfaceMock = $this->getMockBuilder(CustomerB2bClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerResponseTransferMock = $this->getMockBuilder(CustomerResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiToCustomerClientBridge = new CustomersRestApiToCustomerClientBridge(
            $this->customerB2bClientInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testFindCustomerByExternalReference(): void
    {
        $this->customerB2bClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByExternalReference')
            ->with($this->customerTransferMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->assertInstanceOf(
            CustomerResponseTransfer::class,
            $this->customersRestApiToCustomerClientBridge->findCustomerByExternalReference(
                $this->customerTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testFindCustomerByReference(): void
    {
        $this->customerB2bClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerByReference')
            ->with($this->customerTransferMock)
            ->willReturn($this->customerResponseTransferMock);

        $this->assertInstanceOf(
            CustomerResponseTransfer::class,
            $this->customersRestApiToCustomerClientBridge->findCustomerByReference(
                $this->customerTransferMock
            )
        );
    }

    /**
     * @return void
     */
    public function testFindCustomerById(): void
    {
        $this->customerB2bClientInterfaceMock->expects($this->atLeastOnce())
            ->method('findCustomerById')
            ->with($this->customerTransferMock)
            ->willReturn($this->customerTransferMock);

        $this->assertInstanceOf(
            CustomerTransfer::class,
            $this->customersRestApiToCustomerClientBridge->findCustomerById(
                $this->customerTransferMock
            )
        );
    }
}
