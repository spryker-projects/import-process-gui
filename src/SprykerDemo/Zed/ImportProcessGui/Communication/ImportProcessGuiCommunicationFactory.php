<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessGui\Communication;

use Orm\Zed\ImportProcess\Persistence\PyzImportProcessQuery;
use Pyz\Zed\Acl\Business\AclFacadeInterface;
use SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface;
use SprykerDemo\Zed\ImportProcessGui\Communication\Table\ImportProcessGuiTable;
use SprykerDemo\Zed\ImportProcessGui\ImportProcessGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \SprykerDemo\Zed\ImportProcessGui\ImportProcessGuiConfig getConfig()
 */
class ImportProcessGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerDemo\Zed\ImportProcessGui\Communication\Table\ImportProcessGuiTable
     */
    public function createTable(): ImportProcessGuiTable
    {
        return new ImportProcessGuiTable(
            $this->createImportProcessQuery(),
            $this->getAclFacade()
        );
    }

    /**
     * @return \Orm\Zed\ImportProcess\Persistence\PyzImportProcessQuery
     */
    protected function createImportProcessQuery(): PyzImportProcessQuery
    {
        return new PyzImportProcessQuery();
    }

    /**
     * @return \Pyz\Zed\Acl\Business\AclFacadeInterface
     */
    public function getAclFacade(): AclFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessGuiDependencyProvider::FACADE_ACL);
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface
     */
    public function getImportProcessFacade(): ImportProcessFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessGuiDependencyProvider::FACADE_PRODUCT_IMPORT_PROCESS);
    }
}
