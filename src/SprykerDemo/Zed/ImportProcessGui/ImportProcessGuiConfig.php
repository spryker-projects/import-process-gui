<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessGui;

use Orm\Zed\ImportProcess\Persistence\Map\PyzImportProcessTableMap;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ImportProcessGuiConfig extends AbstractBundleConfig
{
    /**
     * @phpstan-var array<string, string>
     * @var array
     */
    public const STATUS_CLASS_LABEL_MAPPING = [
        PyzImportProcessTableMap::COL_STATUS_CREATED => 'label-warning',
        PyzImportProcessTableMap::COL_STATUS_FINISHED => 'label-success',
        PyzImportProcessTableMap::COL_STATUS_FAILED => 'label-danger',
    ];
}
