<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use Codeception\Test\Unit;
use FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use FondOfSpryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface as SprykerCustomersRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\Kernel\Container;

class CustomersRestApiFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $additionalCustomerClientInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilderMock;

    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiFactory
     */
    protected $customersRestApiFactory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerClientInterfaceMock = $this->getMockBuilder(SprykerCustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->additionalCustomerClientInterfaceMock = $this->getMockBuilder(CustomersRestApiToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restResourceBuilderMock = $this->getMockBuilder(RestResourceBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiFactory = new class ($this->restResourceBuilderMock) extends CustomersRestApiFactory {
            /**
             * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
             */
            protected $restResourceBuilder;

            /**
             * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
             */
            public function __construct(RestResourceBuilderInterface $restResourceBuilder)
            {
                $this->restResourceBuilder = $restResourceBuilder;
            }

            /**
             * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
             */
            public function getResourceBuilder()
            {
                return $this->restResourceBuilder;
            }
        };

        $this->customersRestApiFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testGetCustomerReader(): void
    {
        $this->containerMock->expects(static::atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects(static::atLeastOnce())
            ->method('get')
            ->withConsecutive(
                [CustomersRestApiDependencyProvider::CLIENT_CUSTOMER],
                [CustomersRestApiDependencyProvider::ADDITIONAL_CLIENT_CUSTOMER]
            )->willReturnOnConsecutiveCalls(
                $this->customerClientInterfaceMock,
                $this->additionalCustomerClientInterfaceMock
            );

        static::assertInstanceOf(
            CustomerReader::class,
            $this->customersRestApiFactory->createCustomerReader()
        );
    }
}
