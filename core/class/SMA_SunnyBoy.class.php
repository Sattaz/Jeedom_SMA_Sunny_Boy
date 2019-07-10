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

    //Fonction exécutée automatiquement toutes les minutes par Jeedom
    public static function cron() {
		foreach (self::byType('SMA_SunnyBoy') as $SMA_SunnyBoy) {//parcours tous les équipements du plugin SMA_SunnyBoy
			if ($SMA_SunnyBoy->getIsEnable() == 1) {//vérifie que l'équipement est actif
				$cmd = $SMA_SunnyBoy->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
				if (!is_object($cmd)) {//Si la commande n'existe pas
					continue; //continue la boucle
				}
				$cmd->execCmd(); // la commande existe on la lance
			}
		}
    }
    
	/*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
		$this->setDisplay("width","400px");
		$this->setDisplay("height","450px");
    }

    public function postSave() {
		$info = $this->getCmd(null, 'pv_power');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('PV Production', __FILE__));
		}
		$info->setLogicalId('pv_power');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', $this->getConfiguration("Power"));
		$info->setIsHistorized(1);
		$info->setUnite('W');
		$info->setOrder(1);
		$info->save();
		
		$info = $this->getCmd(null, 'pv_total');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('PV Total', __FILE__));
		}
		$info->setLogicalId('pv_total');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setIsHistorized(1);
		$info->setUnite('Wh');
		$info->setOrder(2);
		$info->save();
		
		$info = $this->getCmd(null, 'frequency');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Fréquence', __FILE__));
		}
		$info->setLogicalId('frequency');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 60);
		$info->setIsHistorized(1);
		$info->setUnite('Hz');
		$info->setOrder(3);
		$info->save();
		
		$info = $this->getCmd(null, 'voltage_l1');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Volts Phase 1', __FILE__));
		}
		$info->setLogicalId('voltage_l1');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 250);
		$info->setIsHistorized(1);
		$info->setUnite('V');
		$info->setOrder(4);
		$info->save();
		
		$info = $this->getCmd(null, 'voltage_l2');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Volts Phase 2', __FILE__));
		}
		$info->setLogicalId('voltage_l2');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 250);
		$info->setIsHistorized(1);
		$info->setUnite('V');
		$info->setOrder(5);
		$info->save();
		
		$info = $this->getCmd(null, 'voltage_l3');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Volts Phase 3', __FILE__));
		}
		$info->setLogicalId('voltage_l3');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 250);
		$info->setIsHistorized(1);
		$info->setUnite('V');
		$info->setOrder(6);
		$info->save();
		
		$info = $this->getCmd(null, 'current_l1');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Amps Phase 1', __FILE__));
		}
		$info->setLogicalId('current_l1');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 50);
		$info->setIsHistorized(1);
		$info->setUnite('A');
		$info->setOrder(7);
		$info->save();
		
		$info = $this->getCmd(null, 'current_l2');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Amps Phase 2', __FILE__));
		}
		$info->setLogicalId('current_l2');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 50);
		$info->setIsHistorized(1);
		$info->setUnite('A');
		$info->setOrder(8);
		$info->save();
		
		$info = $this->getCmd(null, 'current_l3');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Amps Phase 3', __FILE__));
		}
		$info->setLogicalId('current_l3');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 50);
		$info->setIsHistorized(1);
		$info->setUnite('A');
		$info->setOrder(9);
		$info->save();
		
		$info = $this->getCmd(null, 'wifi_signal');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Signal Wifi', __FILE__));
		}
		$info->setLogicalId('wifi_signal');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setConfiguration('maxValue', 100);
		$info->setIsHistorized(1);
		$info->setUnite('%');
		$info->setOrder(10);
		$info->save();
		
		$info = $this->getCmd(null, 'sessionID');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Session ID', __FILE__));
		}
		$info->setLogicalId('sessionID');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setIsHistorized(0);
		$info->setIsVisible(0);
		$info->setOrder(11);
		$info->save();
		
		$info = $this->getCmd(null, 'status');
		if (!is_object($info)) {
			$info = new SMA_SunnyBoyCmd();
			$info->setName(__('Statut', __FILE__));
		}
		$info->setLogicalId('status');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setIsHistorized(0);
		$info->setIsVisible(1);
		$info->setOrder(12);
		$info->save();
		
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new SMA_SunnyBoyCmd();
			$refresh->setName(__('Rafraîchir', __FILE__));
		}
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->setOrder(13);
		$refresh->save();
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
		$cmd = $this->getCmd(null, 'refresh'); // On recherche la commande refresh de l’équipement
		if (is_object($cmd)) { //elle existe et on lance la commande
			 $cmd->execCmd();
		}
    }

    public function preRemove() {
       
    }

    public function postRemove() {
        
    }
	
	public function getSmaData() {
		//REFERENCES
		//Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		//https://community.home-assistant.io/t/presenting-sma-converter-values/5033/3
		//http://pydoc.net/pysma/0.1.3/pysma/
		//https://community.openhab.org/t/example-on-how-to-access-data-of-a-sunny-boy-sma-solar-inverter/50963/18
		
		$SMA_IP = $this->getConfiguration("IP");
		$SMA_PASSWORD = $this->getConfiguration("Password");
		
		if (strlen($SMA_IP) == 0) {
			log::add('SMA_SunnyBoy', 'debug','No IP defined for PV inverter interface ...');
			return;
		}
		
		if (strlen($SMA_PASSWORD) == 0) {
			log::add('SMA_SunnyBoy', 'debug','No password defined for PV inverter interface ...');
			return;
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
			log::add('SMA_SunnyBoy', 'error','Error reading Session ID: '.$e);
		}
		
		// COLLECTING VALUES
		//$collection = ('{"destDev":[],"keys":["6100_0046C200","6100_00465700","6100_00464800","6100_00464900","6100_00464A00","6400_00260100","6100_40465300","6100_40465400","6100_40465500","6100_004AB600"]}');
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $collection);
		curl_setopt($ch, CURLOPT_URL, 'https://'.$SMA_IP.'/dyn/getAllOnlValues.json?sid='.$SMA_SID);
		$data = curl_exec($ch);
		
		if (curl_errno($ch)) {
			curl_close ($ch);
			log::add('SMA_SunnyBoy', 'error','Error getting inverter values: '.curl_error($ch));
			$this->checkAndUpdateCmd('status', 'Erreur Données');
			return;
		}
		
		$InverterKey = '';
		$string = $data;
		$start = 'result":{"';
		$end = '"';
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) {
			$InverterKey = '';
		} else {
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			$InverterKey = substr($string, $ini, $len);
		}
		log::add('SMA_SunnyBoy', 'debug','string ='.$string);
		
		$json = json_decode($data, true);
		
		$pv_power = $json['result'][$InverterKey]['6100_0046C200']['1']['0']['val'];
		$pv_total = ($json['result'][$InverterKey]['6400_00260100']['1']['0']['val'])/1;
		$frequency = ($json['result'][$InverterKey]['6100_00465700']['1']['0']['val'])/100;
		$voltage_l1 = ($json['result'][$InverterKey]['6100_00464800']['1']['0']['val'])/100;
		$voltage_l2 = ($json['result'][$InverterKey]['6100_00464900']['1']['0']['val'])/100;
		$voltage_l3 = ($json['result'][$InverterKey]['6100_00464A00']['1']['0']['val'])/100;
		$current_l1 = ($json['result'][$InverterKey]['6100_40465300']['1']['0']['val'])/1000;
		$current_l2 = ($json['result'][$InverterKey]['6100_40465400']['1']['0']['val'])/1000;
		$current_l3 = ($json['result'][$InverterKey]['6100_40465500']['1']['0']['val'])/1000;
		$wifi_signal = $json['result'][$InverterKey]['6100_004AB600']['1']['0']['val'];
		
		if ($pv_power == '') {
			// LOGIN
			$credentials = ('{"pass" : "'.$SMA_PASSWORD.'", "right" : "'.$SMA_RIGHT.'"}');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);
			curl_setopt($ch, CURLOPT_URL, 'https://'.$SMA_IP.'/dyn/login.json');
			$data = curl_exec($ch);
			if (curl_errno($ch)) {
				log::add('SMA_SunnyBoy', 'error','Error login to inverter: '.curl_error($ch));
				curl_close ($ch);
				$this->checkAndUpdateCmd('status', 'Erreur Identification');
				return;
			} else {
				curl_close ($ch);
				$json = json_decode($data, true);
				$SMA_SID = $json['result']['sid'];
				
				$this->checkAndUpdateCmd('sessionID', $SMA_SID);
				$this->checkAndUpdateCmd('pv_power', 0);
				$this->checkAndUpdateCmd('frequency', 0);
				$this->checkAndUpdateCmd('voltage_l1', 0);
				$this->checkAndUpdateCmd('voltage_l2', 0);
				$this->checkAndUpdateCmd('voltage_l3', 0);
				$this->checkAndUpdateCmd('current_l1', 0);
				$this->checkAndUpdateCmd('current_l2', 0);
				$this->checkAndUpdateCmd('current_l3', 0);
				$this->checkAndUpdateCmd('wifi_signal', 0);
								
				$this->checkAndUpdateCmd('status', 'Hors Ligne ...');
				log::add('SMA_SunnyBoy', 'debug','Getting session ID ...');
				return;
			}
			
		} else {
			
			curl_close ($ch);

			$this->checkAndUpdateCmd('pv_power', $pv_power);
			$this->checkAndUpdateCmd('pv_total', $pv_total);
			$this->checkAndUpdateCmd('frequency', $frequency);
			$this->checkAndUpdateCmd('voltage_l1', $voltage_l1);
			$this->checkAndUpdateCmd('voltage_l2', $voltage_l2);
			$this->checkAndUpdateCmd('voltage_l3', $voltage_l3);
			$this->checkAndUpdateCmd('current_l1', $current_l1);
			$this->checkAndUpdateCmd('current_l2', $current_l2);
			$this->checkAndUpdateCmd('current_l3', $current_l3);
			$this->checkAndUpdateCmd('wifi_signal', $wifi_signal);
			
			$this->checkAndUpdateCmd('status', 'OK');
			log::add('SMA_SunnyBoy', 'debug','All good: Session ID='.$SMA_SID.' , Data='.$data);
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
					case 'refresh':
						$info = $eqlogic->getSmaData(); 	//On lance la fonction getSmaData() pour récupérer la production instantanée et on la stocke dans la variable $info
						break;					
		}
    }
    /*     * **********************Getteur Setteur*************************** */
}


