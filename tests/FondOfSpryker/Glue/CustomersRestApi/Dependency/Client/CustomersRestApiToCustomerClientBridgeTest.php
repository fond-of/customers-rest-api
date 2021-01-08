<?php

namespace FondOfSpryker\Glue\CustomersRestApi\Dependency\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\CustomerClientInterface;

class CustomersRestApiToCustomerClientBridgeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferMock;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge
     */
    protected $customersRestApiToCustomerClientBridge;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->customerClientInterfaceMock = $this->getMockBuilder(CustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerTransferMock = $this->getMockBuilder(CustomerTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiToCustomerClientBridge = new CustomersRestApiToCustomerClientBridge(
            $this->customerClientInterfaceMock
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerById(): void
    {
        $idCustomer = 1;

        $this->customerClientInterfaceMock->expects(static::atLeastOnce())
            ->method('getCustomerById')
            ->with($idCustomer)
            ->willReturn($this->customerTransferMock);

        static::assertEquals(
            $this->customerTransferMock,
            $this->customersRestApiToCustomerClientBridge->getCustomerById($idCustomer)
        );
    }
}
