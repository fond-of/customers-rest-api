<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\Kernel\Container;

class CustomersRestApiFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiFactory
     */
    protected $customersRestApiFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customersRestApiToCustomerClientInterfaceMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiToCustomerClientInterfaceMock = $this->getMockBuilder(CustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiFactory = new CustomersRestApiFactory();
        $this->customersRestApiFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testGetFondOfCustomerClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER_B2B)
            ->willReturn($this->customersRestApiToCustomerClientInterfaceMock);

        $this->assertInstanceOf(
            CustomersRestApiToCustomerClientInterface::class,
            $this->customersRestApiFactory->getFondOfCustomerClient()
        );
    }
}
