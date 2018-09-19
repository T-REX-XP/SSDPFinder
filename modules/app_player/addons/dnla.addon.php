<?php

/*
	Addon dnla for app_player
*/

class dnla extends app_player_addon {
	
	// Private properties
	private $curl;
	private $address;
	
	// Constructor
	function __construct($terminal) {
		$this->title = 'DNLA media player';
		$this->description = 'Проигрывание видео - аудио ';
		$this->description .= 'на всех устройства поддерживающих такой протокол. ';
		
		$this->terminal = $terminal;
		$this->reset_properties();
		// MediaRenderer
		include_once(DIR_MODULES.'app_player/libs/MediaRenderer/MediaRenderer.php');
                include_once(DIR_MODULES.'app_player/libs/MediaRenderer/MediaRendererVolume.php');
		// Curl
		$this->curl = curl_init();
		$this->address = 'http://'.$this->terminal['HOST'].':'.(empty($this->terminal['PLAYER_PORT'])?80:$this->terminal['PLAYER_PORT']);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		if($this->terminal['PLAYER_USERNAME'] || $this->terminal['PLAYER_PASSWORD']) {
			curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($this->curl, CURLOPT_USERPWD, $this->terminal['PLAYER_USERNAME'].':'.$this->terminal['PLAYER_PASSWORD']);
		}
	}
	
	// Destructor
	function destroy() {
		curl_close($this->curl);
	}
	
	// Play
	function play($input) {
		$this->reset_properties();
                $remote = new MediaRenderer($this->terminal['HOST']);
                $this->success = $remote->play($input);
		return $this->success;
	}

	// Stop
	function stop() {
		$this->reset_properties();
                $remote = new MediaRenderer($this->terminal['HOST']);
                $this->success = $remote->stop();
		return $this->success;
	}
	
	// Next
	function next() {
		$this->reset_properties();
                $remote = new MediaRenderer($this->terminal['HOST']);
                $this->success = $remote->next();
		return $this->success;
	}
	
	// Previous
	function previous() {
		$this->reset_properties();
                $remote = new MediaRenderer($this->terminal['HOST']);
                $this->success = $remote->previous();
		return $this->success;
	}

	
	// Set volume
	function set_volume($level) {
		$this->reset_properties();
		$this->reset_properties();
                $remotevolume = new MediaRendererVolume($this->terminal['HOST']);
                $this->success = $remotevolume->SetVolume($level);
		return $this->success;
	}    
}

?>
