<?php
/**
* 2012-2015 ZL Development
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@zakarialounes.fr so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to https://www.zakarialounes.fr for more information.
*
*  @author    ZL Development <me@zakarialounes.fr>
*  @copyright 2012-2015 ZL Development
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_'))
	exit;

/**
 * Function used to update your module from previous versions to the version 1.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_1_1_0($module)
{
  $sqlFile = __DIR__ . '/upgrade-1.1.0.sql';
  if(!file_exists($sqlFile) || !is_readable($sqlFile)) return false;
  $sqlContent = str_replace(array('PREFIX_', 'ENGINE_TYPE'),
                            array(_DB_PREFIX_, _MYSQL_ENGINE_),
                            file_get_contents($sqlFile));
  $sqlContent = preg_split("/;\s*[\r\n]+/", $sqlContent);
  if (!Db::getInstance()->Execute($sqlContent)) {
    return false;
  }

	return $module;
}
