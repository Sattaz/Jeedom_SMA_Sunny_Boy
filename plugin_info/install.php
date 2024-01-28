<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function SMA_SunnyBoy_install() {
	$cron = cron::byClassAndFunction('SMA_SunnyBoy', 'daemon');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('SMA_SunnyBoy');
		$cron->setFunction('daemon');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setTimeout(1440);
		$cron->setSchedule('* * * * *');
		$cron->save();
	}
	if (config::byKey('pollInterval','SMA_SunnyBoy') == ''){
		config::save('pollInterval', 30, 'SMA_SunnyBoy');
	}
	$cron->start();
}

function SMA_SunnyBoy_update() {
	$cron = cron::byClassAndFunction('SMA_SunnyBoy', 'daemon');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('SMA_SunnyBoy');
		$cron->setFunction('daemon');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setDeamonSleepTime(1);
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(1440);
		$cron->save();
	}
	if (config::byKey('pollInterval', 'SMA_SunnyBoy','') == '') {
		config::save('pollInterval', 30, 'SMA_SunnyBoy');
	}
	$cron->start();
}

function SMA_SunnyBoy_remove() {
	$cron = cron::byClassAndFunction('SMA_SunnyBoy', 'daemon');
	if (is_object($cron)) {
		$cron->remove();
	}
}
?>
