<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessGui\Communication\Table;

use Orm\Zed\ImportProcess\Persistence\Map\PyzImportProcessTableMap;
use Orm\Zed\ImportProcess\Persistence\PyzImportProcess;
use Orm\Zed\ImportProcess\Persistence\PyzImportProcessQuery;
use Pyz\Zed\Acl\Business\AclFacadeInterface;
use SprykerDemo\Zed\ImportProcessGui\ImportProcessGuiConfig;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ImportProcessGuiTable extends AbstractTable
{
    public const ACTIONS = 'actions';

    public const COL_ID = PyzImportProcessTableMap::COL_ID_IMPORT_PROCESS;
    public const COL_STATUS = PyzImportProcessTableMap::COL_STATUS;
    public const COL_CREATED_AT = PyzImportProcessTableMap::COL_CREATED_AT;

    /**
     * @var \Orm\Zed\ImportProcess\Persistence\PyzImportProcessQuery
     */
    protected $importProcessQuery;

    /**
     * @var \Pyz\Zed\Acl\Business\AclFacadeInterface
     */
    protected $aclFacade;

    /**
     * @param \Orm\Zed\ImportProcess\Persistence\PyzImportProcessQuery $importProcessQuery
     * @param \Pyz\Zed\Acl\Business\AclFacadeInterface $aclFacade
     */
    public function __construct(
        PyzImportProcessQuery $importProcessQuery,
        AclFacadeInterface $aclFacade
    ) {
        $this->importProcessQuery = $importProcessQuery;
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID => '#',
            static::COL_STATUS => 'Status',
            static::COL_CREATED_AT => 'Created at',
            static::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);
        $config->addRawColumn(self::COL_STATUS);

        $config->setSortable([
            static::COL_ID,
            static::COL_STATUS,
            static::COL_CREATED_AT,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $this->importProcessQuery->where(PyzImportProcessTableMap::COL_FK_USER . '=?', $this->aclFacade->getCurrentUser()->getIdUser());
        $result = [];

        /** @var \Orm\Zed\ImportProcess\Persistence\PyzImportProcess[] $queryResult */
        $queryResult = $this->runQuery($this->importProcessQuery, $config, true);

        foreach ($queryResult as $importProcessEntity) {
            $result[] = [
                static::COL_ID => $importProcessEntity->getIdImportProcess(),
                static::COL_STATUS => $this->createStatusLabel($importProcessEntity->getStatus()),
                static::COL_CREATED_AT => $importProcessEntity->getCreatedAt()->format('Y-m-d H:i:s'),
                self::ACTIONS => $this->getActionButtons($importProcessEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\ImportProcess\Persistence\PyzImportProcess $importProcessEntity
     *
     * @return string
     */
    protected function getActionButtons(PyzImportProcess $importProcessEntity)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate('/import-process-gui/index/view', ['id-process' => $importProcessEntity->getIdImportProcess()]),
            'View'
        );

        if ($importProcessEntity->getStatus() === PyzImportProcessTableMap::COL_STATUS_CREATED) {
            $buttons[] = $this->generateEditButton(
                Url::generate('/import-process-gui/index/execute-import', ['id-process' => $importProcessEntity->getIdImportProcess()]),
                'Rerun import'
            );
        }

        return implode(' ', $buttons);
    }

    /**
     * @param string $currentStatus
     *
     * @return string
     */
    protected function createStatusLabel(string $currentStatus): string
    {
        return $this->generateLabel(ucwords($currentStatus), ImportProcessGuiConfig::STATUS_CLASS_LABEL_MAPPING[$currentStatus]);
    }
}
