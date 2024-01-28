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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class SMA_SunnyBoy extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

  	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction(__CLASS__, 'daemon');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction(__CLASS__, 'daemon');
		if (!is_object($cron)) {
			$cron = new cron();
			$cron->setClass(__CLASS__);
			$cron->setFunction('daemon');
			$cron->setEnable(1);
			$cron->setDeamon(1);
			$cron->setTimeout(1440);
			$cron->setSchedule('* * * * *');
			$cron->save();
		}
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction(__CLASS__, 'daemon');
		if (is_object($cron)) {
			$cron->halt();
		}
	}

	public static function daemon() {
		$starttime = microtime(true);
		foreach (self::byType(__CLASS__, true) as $eqLogic) {
          	$cmd = $eqLogic->getCmd(null, 'update');//retourne la commande 'update' si elle existe
			if (is_object($cmd)) {//si la comande existe
				$cmd->execCmd();//alors on l'execute
			}
		}
		$endtime = microtime(true);
		if ($endtime - $starttime < config::byKey('pollInterval', __CLASS__, 60, true)) {
			usleep(floor((config::byKey('pollInterval', __CLASS__) + $starttime - $endtime) * 1000000));
		}
	}
    
    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {

    }

    public function postSave() {
		$action = $this->getCmd(null, 'refresh');
		if (is_object($action)) {$action->remove();}
      
      	$DeviceType = $this->getConfiguration("DeviceType");
      	//Type 10 = Onduleur Monophasé
      	//Type 20 = Onduleur Triphasé
      	//Type 30 = Energy Meter Monophasé
      	//Type 40 = Energy Meter Triphasé
      
     	if ($DeviceType == 10) { //Suppression de toutes commandes sans rapport avec un onduleur monophasé
			$action = $this->getCmd(null, 'voltage_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltage_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'current_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'current_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'power_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'power_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'balance');
          	if (is_object($action)) {$action->remove();}
        }
      
      	if ($DeviceType == 20) {//Suppression de toutes commandes sans rapport avec un onduleur Triphasé
			$action = $this->getCmd(null, 'balance');
          	if (is_object($action)) {$action->remove();}
      	}
      
      	if ($DeviceType == 30) {//Suppression de toutes commandes sans rapport avec un Energy Meter Monophasé
			$action = $this->getCmd(null, 'pv_power');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'pv_total');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'frequency');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'voltage_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltage_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'current_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'current_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'power_l2');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'power_l3');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltageDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltageDC_B');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'currentDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'currentDC_B');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'powerDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'powerDC_B');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'wifi_signal');
          	if (is_object($action)) {$action->remove();}
      	}
      
		if ($DeviceType == 40) {//Suppression de toutes commandes sans rapport avec un Energy Meter Triphasé
			$action = $this->getCmd(null, 'pv_power');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'pv_total');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'frequency');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltageDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'voltageDC_B');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'currentDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'currentDC_B');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'powerDC_A');
          	if (is_object($action)) {$action->remove();}
          	$action = $this->getCmd(null, 'powerDC_B');
          	if (is_object($action)) {$action->remove();}
			$action = $this->getCmd(null, 'wifi_signal');
          	if (is_object($action)) {$action->remove();}
      	}
      
      	if ($DeviceType == 10) { //Onduleur Monophasé
			$info = $this->getCmd(null, 'pv_power');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('PV Production', __FILE__));
				$info->setLogicalId('pv_power');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				//$info->setTemplate('dashboard','line');
				//$info->setTemplate('mobile','line');
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', $this->getConfiguration("Power"));
				$info->setIsHistorized(1);
				$info->setUnite('W');
              	$info->setOrder(1);
			}
			$info->save();
		
			$info = $this->getCmd(null, 'pv_total');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('PV Total', __FILE__));
				$info->setLogicalId('pv_total');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');
          		$info->setConfiguration('historizeRound', 2);
				$info->setIsHistorized(1);
				$info->setUnite('Wh');
              	$info->setOrder(2);
			}
			$info->save();
		
			$info = $this->getCmd(null, 'frequency');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Fréquence', __FILE__));
				$info->setLogicalId('frequency');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 60);
				$info->setIsHistorized(1);
				$info->setUnite('Hz');
				$info->setOrder(3);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'voltage_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L1', __FILE__));
				$info->setLogicalId('voltage_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(4);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'current_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L1', __FILE__));
				$info->setLogicalId('current_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(5);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L1', __FILE__));
				$info->setLogicalId('power_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(6);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'voltageDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension DC-A', __FILE__));
				$info->setLogicalId('voltageDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 1000);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(7);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'voltageDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension DC-B', __FILE__));
				$info->setLogicalId('voltageDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 1000);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(8);              
			}
			$info->save();

         	$info = $this->getCmd(null, 'currentDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité DC-A', __FILE__));
				$info->setLogicalId('currentDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(9);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'currentDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité DC-B', __FILE__));
				$info->setLogicalId('currentDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(10);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'powerDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance DC-A', __FILE__));
				$info->setLogicalId('powerDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(11);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'powerDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance DC-B', __FILE__));
				$info->setLogicalId('powerDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(12);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'wifi_signal');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Signal Wifi', __FILE__));
				$info->setLogicalId('wifi_signal');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 100);
				$info->setIsHistorized(1);
				$info->setUnite('%');
				$info->setOrder(13);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'sessionID');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Session ID', __FILE__));
				$info->setLogicalId('sessionID');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(0);
				$info->setOrder(14);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'status');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Statut', __FILE__));
				$info->setLogicalId('status');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(1);
				$info->setOrder(15);              
			}
			$info->save();
		
     		$refresh = $this->getCmd(null, 'update');
			if (!is_object($refresh)) {
				$refresh = new SMA_SunnyBoyCmd();
				$refresh->setName(__('Rafraîchir', __FILE__));
				$refresh->setEqLogic_id($this->getId());
				$refresh->setLogicalId('update');
				$refresh->setType('action');
				$refresh->setSubType('other');
    			$refresh->setIsVisible(0);
				$refresh->setOrder(50);              
			}
			$refresh->save();
        }
      
      	if ($DeviceType == 20) { //Onduleur Triphasé
			$info = $this->getCmd(null, 'pv_power');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('PV Production', __FILE__));
				$info->setLogicalId('pv_power');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				//$info->setTemplate('dashboard','line');
				//$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', $this->getConfiguration("Power"));
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(1);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'pv_total');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('PV Total', __FILE__));
				$info->setLogicalId('pv_total');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');
          		$info->setConfiguration('historizeRound', 2);
				$info->setIsHistorized(1);
				$info->setUnite('Wh');
				$info->setOrder(2);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'frequency');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Fréquence', __FILE__));
				$info->setLogicalId('frequency');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 60);
				$info->setIsHistorized(1);
				$info->setUnite('Hz');
				$info->setOrder(3);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'voltage_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L1', __FILE__));
				$info->setLogicalId('voltage_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(4);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'voltage_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L2', __FILE__));
				$info->setLogicalId('voltage_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(5);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'voltage_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L3', __FILE__));
				$info->setLogicalId('voltage_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(6);              
			}
			$info->save();

			$info = $this->getCmd(null, 'current_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L1', __FILE__));
				$info->setLogicalId('current_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(7);              
			}
			$info->save();
      		
			$info = $this->getCmd(null, 'current_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L2', __FILE__));
				$info->setLogicalId('current_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(8);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'current_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L3', __FILE__));
				$info->setLogicalId('current_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(9);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L1', __FILE__));
				$info->setLogicalId('power_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(10);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L2', __FILE__));
				$info->setLogicalId('power_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(11);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L3', __FILE__));
				$info->setLogicalId('power_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(12);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'voltageDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension DC-A', __FILE__));
				$info->setLogicalId('voltageDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 1000);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(13);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'voltageDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension DC-B', __FILE__));
				$info->setLogicalId('voltageDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 1000);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(14);              
			}
			$info->save();

         	$info = $this->getCmd(null, 'currentDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité DC-A', __FILE__));
				$info->setLogicalId('currentDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(15);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'currentDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité DC-B', __FILE__));
				$info->setLogicalId('currentDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(16);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'powerDC_A');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance DC-A', __FILE__));
				$info->setLogicalId('powerDC_A');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(17);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'powerDC_B');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance DC-B', __FILE__));
				$info->setLogicalId('powerDC_B');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 10000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(18);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'wifi_signal');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Signal Wifi', __FILE__));
				$info->setLogicalId('wifi_signal');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 100);
				$info->setIsHistorized(1);
				$info->setUnite('%');
				$info->setOrder(19);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'sessionID');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Session ID', __FILE__));
				$info->setLogicalId('sessionID');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(0);
				$info->setOrder(20);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'status');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Statut', __FILE__));
				$info->setLogicalId('status');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(1);
				$info->setOrder(21);              
			}
			$info->save();
		
     		$refresh = $this->getCmd(null, 'update');
			if (!is_object($refresh)) {
				$refresh = new SMA_SunnyBoyCmd();
				$refresh->setName(__('Rafraîchir', __FILE__));
				$refresh->setEqLogic_id($this->getId());
				$refresh->setLogicalId('update');
				$refresh->setType('action');
				$refresh->setSubType('other');
    			$refresh->setIsVisible(0);
				$refresh->setOrder(50);              
			}
			$refresh->save();
		}
      
      	if ($DeviceType == 30) { //Energy Meter Monophasé
			$info = $this->getCmd(null, 'balance');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Balance', __FILE__));
				$info->setLogicalId('balance');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(1);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'voltage_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L1', __FILE__));
				$info->setLogicalId('voltage_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(2);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'current_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L1', __FILE__));
				$info->setLogicalId('current_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(3);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L1', __FILE__));
				$info->setLogicalId('power_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(4);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'sessionID');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Session ID', __FILE__));
				$info->setLogicalId('sessionID');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(0);
				$info->setOrder(5);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'status');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Statut', __FILE__));
				$info->setLogicalId('status');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(1);
				$info->setOrder(6);              
			}
			$info->save();
		
     		$refresh = $this->getCmd(null, 'update');
			if (!is_object($refresh)) {
				$refresh = new SMA_SunnyBoyCmd();
				$refresh->setName(__('Rafraîchir', __FILE__));
				$refresh->setEqLogic_id($this->getId());
				$refresh->setLogicalId('update');
				$refresh->setType('action');
				$refresh->setSubType('other');
    			$refresh->setIsVisible(0);
				$refresh->setOrder(50);              
			}
			$refresh->save();
        }
      
      	if ($DeviceType == 40) { //Energy Meter Triphasé
			$info = $this->getCmd(null, 'balance');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Balance', __FILE__));
				$info->setLogicalId('balance');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(1);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'voltage_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L1', __FILE__));
				$info->setLogicalId('voltage_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(2);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'voltage_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L2', __FILE__));
				$info->setLogicalId('voltage_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(3);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'voltage_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Tension L3', __FILE__));
				$info->setLogicalId('voltage_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 250);
				$info->setIsHistorized(1);
				$info->setUnite('V');
				$info->setOrder(4);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'current_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L1', __FILE__));
				$info->setLogicalId('current_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(5);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'current_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L2', __FILE__));
				$info->setLogicalId('current_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(6);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'current_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Intensité L3', __FILE__));
				$info->setLogicalId('current_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', 0);
				$info->setConfiguration('maxValue', 50);
				$info->setIsHistorized(1);
				$info->setUnite('A');
				$info->setOrder(7);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l1');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L1', __FILE__));
				$info->setLogicalId('power_l1');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(8);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l2');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L2', __FILE__));
				$info->setLogicalId('power_l2');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(9);              
			}
			$info->save();
          
         	$info = $this->getCmd(null, 'power_l3');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Puissance L3', __FILE__));
				$info->setLogicalId('power_l3');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('numeric');
				$info->setTemplate('dashboard','line');
				$info->setTemplate('mobile','line');          
				$info->setConfiguration('minValue', -20000);
				$info->setConfiguration('maxValue', 20000);
				$info->setIsHistorized(1);
				$info->setUnite('W');
				$info->setOrder(10);              
			}
			$info->save();
          
			$info = $this->getCmd(null, 'sessionID');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Session ID', __FILE__));
				$info->setLogicalId('sessionID');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(0);
				$info->setOrder(11);              
			}
			$info->save();
		
			$info = $this->getCmd(null, 'status');
			if (!is_object($info)) {
				$info = new SMA_SunnyBoyCmd();
				$info->setName(__('Statut', __FILE__));
				$info->setLogicalId('status');
				$info->setEqLogic_id($this->getId());
				$info->setType('info');
				$info->setSubType('string');
				$info->setIsHistorized(0);
				$info->setIsVisible(1);
				$info->setOrder(12);              
			}
			$info->save();
		
     		$refresh = $this->getCmd(null, 'update');
			if (!is_object($refresh)) {
				$refresh = new SMA_SunnyBoyCmd();
				$refresh->setName(__('Rafraîchir', __FILE__));
				$refresh->setEqLogic_id($this->getId());
				$refresh->setLogicalId('update');
				$refresh->setType('action');
				$refresh->setSubType('other');
    			$refresh->setIsVisible(0);
				$refresh->setOrder(50);              
			}
			$refresh->save();
        }
      
      	self::deamon_start();
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {

    }

    public function preRemove() {
       
    }

    public function postRemove() {
        
    }
  
 	public function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	public function getSmaData() {
		//REFERENCES
		//Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		//https://community.home-assistant.io/t/presenting-sma-converter-values/5033/3
		//http://pydoc.net/pysma/0.1.3/pysma/
		//https://community.openhab.org/t/example-on-how-to-access-data-of-a-sunny-boy-sma-solar-inverter/50963/18
		
		$SMA_IP = $this->getConfiguration("IP");
		$SMA_Port = $this->getConfiguration("Port");
      	$SMA_Protocol = $this->getConfiguration("Protocol");
		$SMA_PASSWORD = $this->getConfiguration("Password");
		$SMA_HTTP = '';
		
     	$DeviceType = $this->getConfiguration("DeviceType");
      	//Type 10 = Onduleur Monophasé
      	//Type 20 = Onduleur Triphasé
      	//Type 30 = Energy Meter Monophasé
      	//Type 40 = Energy Meter Triphasé
      
		if (strlen($SMA_IP) == 0) {
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> No IP defined for equipment!');
			return;
		}
		
		if (strlen($SMA_PASSWORD) == 0) {
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> No password defined for equipment!');
			return;
		}
		
		if (strlen($SMA_Port) == 0) {
			$SMA_Port = 443;
		}
		
		if ($SMA_Protocol == 20) {
			$SMA_HTTP = 'http';
		} else {
			$SMA_HTTP = 'https';
		}
		
		$SMA_RIGHT = 'usr';
		$ch = curl_init();
		$headers = array();
		$headers[] = "Accept: application/json";
		$headers[] = "Accept-Charset: UTF-8";
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// READ STORED SESSION ID
		try {
			$cmd = $this->getCmd(null, 'sessionID'); // On recherche la commande refresh de l’équipement
			if (is_object($cmd)) { //elle existe et on lance la commande
				$SMA_SID = $cmd->execCmd();
			}
		} catch (Exception $e) {
			$SMA_SID = '';
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Cannot get Session ID: '.$e);
		}
		
		// COLLECTING VALUES
		$InverterKey = '';
		$collection = ('{"destDev":[],"keys":[]}');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $collection);
		curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/getAllOnlValues.json?sid='.$SMA_SID);
		$data = curl_exec($ch);
		
		if (curl_errno($ch)) {
			curl_close ($ch);
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Cannot get equipment values: '.curl_error($ch));
			$this->checkAndUpdateCmd('status', 'Erreur Données');
		} else {
          	$InverterKey = $this->get_string_between($data,'result":{"','"');
		}
		
		if ($InverterKey == '') {
			// LOGIN
			$credentials = ('{"pass" : "'.$SMA_PASSWORD.'", "right" : "'.$SMA_RIGHT.'"}');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);
			curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/login.json');
			$data = curl_exec($ch);
			if (curl_errno($ch)) {
				log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Cannot login to equipment: '.curl_error($ch));
				curl_close ($ch);
				$this->checkAndUpdateCmd('status', 'Erreur Identification');
				return;
			} else {
				curl_close ($ch);
				$json = json_decode($data, true);
				$SMA_SID = $json['result']['sid'];
				$this->checkAndUpdateCmd('sessionID', $SMA_SID);
				$this->checkAndUpdateCmd('status', 'Hors Ligne ...');
				log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Getting session ID ...');
				return;
			}
			
		} else {
          
			$typeID = 0;
          	if ($DeviceType==10 || $DeviceType==20) {$typeID=1;}
          	if ($DeviceType==30 || $DeviceType==40) {$typeID=65;}
          
       		//Get DC Data
          	if ($DeviceType==10 || $DeviceType==20) {
  				$collection = ('{"destDev":[],"keys":["6380_40251E00","6380_40451F00","6380_40452100"]}');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $collection);
				curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/getValues.json?sid='.$SMA_SID);
				$dataDC = curl_exec($ch);
  				$jsonDC = json_decode($dataDC, true);
				$currentDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40452100'][$typeID]['0']['val'])/1000),2);
				$currentDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40452100'][$typeID]['1']['val'])/1000),2);
				$voltageDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40451F00'][$typeID]['0']['val'])/100),1);
				$voltageDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40451F00'][$typeID]['1']['val'])/100),1);
				$powerDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40251E00'][$typeID]['0']['val'])/1),0);
				$powerDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40251E00'][$typeID]['1']['val'])/1),0);
            }
			
			curl_close ($ch);

			$json = json_decode($data, true);
		
          	$balance = round(($json['result'][$InverterKey]['6100_40263F00'][$typeID]['0']['val']) * -1,0);
			$pv_power = round($json['result'][$InverterKey]['6100_0046C200'][$typeID]['0']['val'],0);
			$pv_total = round(($json['result'][$InverterKey]['6400_00260100'][$typeID]['0']['val'])/1,0);
			$frequency = round(($json['result'][$InverterKey]['6100_00465700'][$typeID]['0']['val'])/100,1);
			$voltage_l1 = round(($json['result'][$InverterKey]['6100_00464800'][$typeID]['0']['val'])/100,1);
			$voltage_l2 = round(($json['result'][$InverterKey]['6100_00464900'][$typeID]['0']['val'])/100,1);
			$voltage_l3 = round(($json['result'][$InverterKey]['6100_00464A00'][$typeID]['0']['val'])/100,1);
			$current_l1 = round(($json['result'][$InverterKey]['6100_40465300'][$typeID]['0']['val'])/1000,2);
			$current_l2 = round(($json['result'][$InverterKey]['6100_40465400'][$typeID]['0']['val'])/1000,2);
			$current_l3 = round(($json['result'][$InverterKey]['6100_40465500'][$typeID]['0']['val'])/1000,2);
			$wifi_signal = round($json['result'][$InverterKey]['6100_004AB600'][$typeID]['0']['val'],0);
           	$power_l1 = round(floatval(($json['result'][$InverterKey]['6100_40464000'][$typeID]['0']['val'])/1),0);
  			$power_l2 = round(floatval(($json['result'][$InverterKey]['6100_40464100'][$typeID]['0']['val'])/1),0);
  			$power_l3 = round(floatval(($json['result'][$InverterKey]['6100_40464200'][$typeID]['0']['val'])/1),0);
			
          	if ($DeviceType==30 || $DeviceType==40) {$this->checkAndUpdateCmd('balance', $balance);}
			if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('pv_power', $pv_power);}
			if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('pv_total', $pv_total);}
			if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('frequency', $frequency);}
			if ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40) {$this->checkAndUpdateCmd('voltage_l1', $voltage_l1);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('voltage_l2', $voltage_l2);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('voltage_l3', $voltage_l3);}
			if ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40) {$this->checkAndUpdateCmd('current_l1', $current_l1);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('current_l2', $current_l2);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('current_l3', $current_l3);}
        	if ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40) {$this->checkAndUpdateCmd('power_l1', $power_l1);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('power_l2', $power_l2);}
			if ($DeviceType==20 || $DeviceType==40) {$this->checkAndUpdateCmd('power_l3', $power_l3);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('voltageDC_A', $voltageDC_A);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('voltageDC_B', $voltageDC_B);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('currentDC_A', $currentDC_A);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('currentDC_B', $currentDC_B);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('powerDC_A', $powerDC_A);}
          	if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('powerDC_B', $powerDC_B);}
			if ($DeviceType==10 || $DeviceType==20) {$this->checkAndUpdateCmd('wifi_signal', $wifi_signal);}
			
			$this->checkAndUpdateCmd('status', 'OK');
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> All good: Session ID='.$SMA_SID.', Equipment Key ='.$InverterKey.' , Data='.$data);
              
			return;
		}
		
	}
	
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class SMA_SunnyBoyCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
				$eqlogic = $this->getEqLogic();
				switch ($this->getLogicalId()) {
                    case 'update':
						$info = $eqlogic->getSmaData();
						break;					
		}
    }
    /*     * **********************Getteur Setteur*************************** */
}
