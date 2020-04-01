<?php

namespace FondOfSpryker\Glue\CustomersRestApi;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\Container;

class CustomersRestApiDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Glue\CustomersRestApi\CustomersRestApiDependencyProvider
     */
    protected $customersRestApiDependencyProvider;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\Container
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customersRestApiDependencyProvider = new CustomersRestApiDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideDependencies(): void
    {
        $this->assertInstanceOf(
            Container::class,
            $this->customersRestApiDependencyProvider->provideDependencies(
                $this->containerMock
            )
        );
    }
}
