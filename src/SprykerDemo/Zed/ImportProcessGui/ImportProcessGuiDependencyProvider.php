<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ImportProcessGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_ACL = 'FACADE_ACL';
    public const FACADE_PRODUCT_IMPORT_PROCESS = 'FACADE_PRODUCT_IMPORT_PROCESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addAclFacade($container);
        $container = $this->addImportProcessFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclFacade(Container $container)
    {
        $container->set(static::FACADE_ACL, function (Container $container) {
            return $container->getLocator()->acl()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addImportProcessFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT_IMPORT_PROCESS, function (Container $container) {
            return $container->getLocator()->importProcess()->facade();
        });

        return $container;
    }
}
