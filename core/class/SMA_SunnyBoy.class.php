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
  
  	public static function cronHourly() {
    	$h = date('G');
    	if($h == 2) { // Redemarrage du deamon a 2h du matin chaque jour cause fuite memoire
          	log::add(__CLASS__, 'debug', "Deamon: restarting ...");
			self::deamon_start();
          	log::add(__CLASS__, 'debug', "Deamon: restarted!");
    	}
  	}

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
		}
      	$cron->setDeamonSleepTime(config::byKey('pollInterval', __CLASS__, 60));
      	$cron->save();
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction(__CLASS__, 'daemon');
		if (is_object($cron)) {
			$cron->halt();
		}
	}

	public static function daemon() {
      	gc_enable();
      	$mem0 = memory_get_usage();
      	log::add(__CLASS__, 'debug', "Memory_usage Start: $mem0");
		foreach (self::byType(__CLASS__, true) as $eqLogic) {
       		$eqLogic->getSmaData();
		}
     	gc_collect_cycles();
      	$mem1 = memory_get_usage();
      	log::add(__CLASS__, 'debug', "Memory_usage End: $mem1 Conso: ".($mem1-$mem0));
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
		
      	//log::add(__CLASS__, 'debug', "Memory_usage Before getConfiguration: ".memory_get_usage());
      
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
      
      	//log::add(__CLASS__, 'debug', "Memory_usage After getConfiguration: ".memory_get_usage());
      
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
      
      	//log::add(__CLASS__, 'debug', "Memory_usage Config Loaded: ".memory_get_usage());

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
      
      	//log::add(__CLASS__, 'debug', "Memory_usage Stored Session ID Retrieved: ".memory_get_usage());
      
      
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
		
		// COLLECTING VALUES
		$InverterKey = '';
		$collection = ('{"destDev":[],"keys":[]}');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $collection);
		curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/getAllOnlValues.json?sid='.$SMA_SID);
		$data = curl_exec($ch);
      	curl_close($ch); //added
      	unset($ch); //added
		
		if (curl_errno($ch)) {
			log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Cannot get equipment values: '.curl_error($ch));
			$this->checkAndUpdateCmd('status', 'Erreur Données');
          	curl_close($ch);
          	unset($ch);
          	unset($data);
          	return;
		} else {
          	$InverterKey = $this->get_string_between($data,'result":{"','"');
          	//unset($data);
        }
      
      	//log::add(__CLASS__, 'debug', "Memory_usage Collecting Values Completed: ".memory_get_usage());
		
		if ($InverterKey == '') {
			// LOGIN
          	$ch = curl_init(); //added
			$credentials = ('{"pass" : "'.$SMA_PASSWORD.'", "right" : "'.$SMA_RIGHT.'"}');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);
			curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/login.json');
			$data = curl_exec($ch);
          	curl_close($ch); //added
          	unset($ch); //added
			if (curl_errno($ch)) {
				log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Cannot login to equipment: '.curl_error($ch));
				//curl_close($ch);
				$this->checkAndUpdateCmd('status', 'Erreur Identification');
              	//unset($ch);
              	//unset($data);
				return;
			} else {
				//curl_close($ch);
              	//unset($ch);
				$json = json_decode($data, true);
				$SMA_SID = $json['result']['sid'];
				$this->checkAndUpdateCmd('sessionID', $SMA_SID);
				$this->checkAndUpdateCmd('status', 'Hors Ligne ...');
				log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> Getting session ID ...');
              	unset($data);
              	unset($json);
				return;
			}
          
          	//log::add(__CLASS__, 'debug', "Memory_usage SMA Session ID Read Completed : ".memory_get_usage());
			
		} else {
          
			$typeID = 0;
          	if ($DeviceType==10 || $DeviceType==20) {$typeID=1;}
          	if ($DeviceType==30 || $DeviceType==40) {$typeID=65;}
          
       		//Get DC Data
          	if ($DeviceType==10 || $DeviceType==20) {
              	$ch = curl_init(); //added
  				$collection = ('{"destDev":[],"keys":["6380_40251E00","6380_40451F00","6380_40452100"]}');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $collection);
				curl_setopt($ch, CURLOPT_URL, $SMA_HTTP.'://'.$SMA_IP.':'.$SMA_Port.'/dyn/getValues.json?sid='.$SMA_SID);
				$dataDC = curl_exec($ch);
              	curl_close($ch); //added
          		unset($ch); //added
  				$jsonDC = json_decode($dataDC, true);
				$currentDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40452100'][$typeID]['0']['val'])/1000),2);
				$currentDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40452100'][$typeID]['1']['val'])/1000),2);
				$voltageDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40451F00'][$typeID]['0']['val'])/100),1);
				$voltageDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40451F00'][$typeID]['1']['val'])/100),1);
				$powerDC_A = round(floatval(($jsonDC['result'][$InverterKey]['6380_40251E00'][$typeID]['0']['val'])/1),0);
				$powerDC_B = round(floatval(($jsonDC['result'][$InverterKey]['6380_40251E00'][$typeID]['1']['val'])/1),0);
              
              	//log::add(__CLASS__, 'debug', "Memory_usage DC Data Retrieved: ".memory_get_usage());
            }
			
			//curl_close($ch);
          	//unset($ch);
          
          	//log::add(__CLASS__, 'debug', "Memory_usage Last CURL Closed, Start Of Assigning Values To Commands: ".memory_get_usage());

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
          
          	//$hdle = fopen(__DIR__ ."/dataDC.txt", "wb");
			//if($hdle !== FALSE) { fwrite($hdle, $dataDC); fclose($hdle); }
          
          	// jusqu'ici pas de memomy leak
          
          	foreach ($this->getCmd('info') as $cmd) {
        		$cmdLogicalId = $cmd->getLogicalId();
              	//if ($cmdLogicalId == 'balance' && ($DeviceType==30 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, rand(10,15));}
          		if ($cmdLogicalId == 'balance' && ($DeviceType==30 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $balance);}
				if ($cmdLogicalId == 'pv_power' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $pv_power);}
				if ($cmdLogicalId == 'pv_total' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $pv_total);}
				if ($cmdLogicalId == 'frequency' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $frequency);}
				if ($cmdLogicalId == 'voltage_l1' && ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $voltage_l1);}
				if ($cmdLogicalId == 'voltage_l2' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $voltage_l2);}
				if ($cmdLogicalId == 'voltage_l3' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $voltage_l3);}
				if ($cmdLogicalId == 'current_l1' && ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $current_l1);}
				if ($cmdLogicalId == 'current_l2' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $current_l2);}
				if ($cmdLogicalId == 'current_l3' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $current_l3);}
        		if ($cmdLogicalId == 'power_l1' && ($DeviceType==10 || $DeviceType==20 || $DeviceType==30 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $power_l1);}
				if ($cmdLogicalId == 'power_l2' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $power_l2);}
				if ($cmdLogicalId == 'power_l3' && ($DeviceType==20 || $DeviceType==40)) {$this->checkAndUpdateCmd($cmd, $power_l3);}
          		if ($cmdLogicalId == 'voltageDC_A' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $voltageDC_A);}
          		if ($cmdLogicalId == 'voltageDC_B' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $voltageDC_B);}
          		if ($cmdLogicalId == 'currentDC_A' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $currentDC_A);}
          		if ($cmdLogicalId == 'currentDC_B' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $currentDC_B);}
          		if ($cmdLogicalId == 'powerDC_A' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $powerDC_A);}
          		if ($cmdLogicalId == 'powerDC_B' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $powerDC_B);}
				if ($cmdLogicalId == 'wifi_signal' && ($DeviceType==10 || $DeviceType==20)) {$this->checkAndUpdateCmd($cmd, $wifi_signal);}
				if ($cmdLogicalId == 'status') {$this->checkAndUpdateCmd($cmd, 'OK');}
              	//unset($cmdLogicalId);
            }
          
          	unset($data);
          	unset($dataDC);
          	unset($json);
          	unset($jsonDC);
         
          	//log::add(__CLASS__, 'debug', "Memory_usage Last CURL Closed, End Of Assigning Values To Commands: ".memory_get_usage());
          
			//log::add('SMA_SunnyBoy', 'debug', $this->getHumanName().' -> All good: Session ID='.$SMA_SID.', Equipment Key ='.$InverterKey.' , Data='.$data);
                    
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
