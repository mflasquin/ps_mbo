<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */
declare(strict_types=1);

namespace PrestaShop\Module\Mbo\Traits\Hooks;

use Exception;
use PrestaShop\Module\Mbo\Addons\DataProvider;
use PrestaShop\Module\Mbo\Modules\Module;
use PrestaShop\Module\Mbo\Modules\Repository;

trait UseAdminModuleInstallRetrieveSource
{
    /**
     * Hook actionAdminModuleInstallRetrieveSource.
     */
    public function hookActionAdminModuleInstallRetrieveSource(array $params): ?string
    {
        if (empty($params['name'])) {
            return null;
        }

        $moduleName = (string) $params['name'];

        /** @var Module $module */
        $module = $this->get('mbo.modules.repository')->getModule($moduleName);

        if (null === $module) {
            return null;
        }

        if (
            // download the module from addons and unzip the sources to the PS modules folder
            $this->get('mbo.addon.module.data_provider.addons')->downloadModule(
                (int) $module->get('id')
            )
        ) {
            // ??
            $modulePath = _PS_MODULE_DIR_ . $moduleName;
            if (is_dir($modulePath)) {
                return $modulePath;
            }
        }

        throw new Exception('Unable to download module');
    }
}
