<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessGui\Communication\Controller;

use SprykerDemo\Zed\ImportProcessGui\ImportProcessGuiConfig;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ImportProcessGui\Communication\ImportProcessGuiCommunicationFactory getFactory()
 * @method \SprykerDemo\Zed\ImportProcessGui\ImportProcessGuiConfig getConfig()
 */
class IndexController extends AbstractController
{
    use BundleConfigResolverAwareTrait;

    protected const PARAM_ID_PROCESS = 'id-process';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()
            ->createTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function viewAction(Request $request)
    {
        $idImportProcess = $this->castId($request->query->get(static::PARAM_ID_PROCESS));

        $importProcessTransfer = $this->getFactory()
            ->getImportProcessFacade()
            ->findImportProcessById($idImportProcess);

        return $this->viewResponse([
            'idImportProcess' => $idImportProcess,
            'importProcess' => $importProcessTransfer,
            'labelClass' => $importProcessTransfer ? ImportProcessGuiConfig::STATUS_CLASS_LABEL_MAPPING[$importProcessTransfer->getStatus()] : '',
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeImportAction(Request $request)
    {
        $idImportProcess = $this->castId($request->query->get(static::PARAM_ID_PROCESS));

        $importProcessTransfer = $this->getFactory()
            ->getImportProcessFacade()
            ->findImportProcessById($idImportProcess);

        $this->getFactory()->getImportProcessFacade()->runDetachedImportProcess($importProcessTransfer);

        $this->addSuccessMessage('Import process started successfully.');

        return $this->redirectResponse(Url::generate('view', [
            static::PARAM_ID_PROCESS => $importProcessTransfer->getIdImportProcess(),
        ])->build());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function viewStatusAction(Request $request): array
    {
        $idImportProcess = $this->castId($request->query->get(static::PARAM_ID_PROCESS));

        $importProcessTransfer = $this->getFactory()
            ->getImportProcessFacade()
            ->findImportProcessById($idImportProcess);

        return $this->viewResponse([
            'importProcess' => $importProcessTransfer,
            'labelClass' => ImportProcessGuiConfig::STATUS_CLASS_LABEL_MAPPING[$importProcessTransfer->getStatus()],
        ]);
    }
}
