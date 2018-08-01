<?php
class Yeelight {
	private $jobs = array();

	public function __construct($controladress) {
		$this->ip = $this->getIp($controladress, false);
                $this->port = $this->getPort($controladress);
		if (!$this->verifyConnection()) throw new Exception("Failed connecting to Yeelight device.");
	}

	private function verifyConnection() {
		$this->fp = fsockopen($this->ip, $this->port, $this->errno, $this->errstr, 30);
		if (!$this->fp) return false;

		stream_set_blocking($this->fp, false);
		return true;
	}

	private function getNextID() {
		if(!empty($this->jobs)) return count($this->jobs);
		else return 0;
	}

	public function __call($method, $args) {
		$jObj = new stdClass;
		$jObj->id = $this->getNextID();
		$jObj->method = $method;
		$jObj->params = $args;

		$this->jobs[] = $jObj;
		return $this;
	}

	public function commit() {
		if (!$this->verifyConnection()) throw new Exception("Failed connecting to Yeelight device.");
		foreach($this->jobs as $job) {
			$jStr = json_encode($job);
			fwrite($this->fp, $jStr . "\r\n");
			fflush($this->fp);

			usleep(100 * 1000);

			$out[] = fgets($this->fp);
		}

		$this->jobs = array();
		if(!empty($out)) return $out;
		else return true;
	}

	public function disconnect() {
		fclose($this->fp);
	}

        public function getIp($baseUrl,$withPort) {
        	if( !empty($baseUrl) ){
                $parsed_url = parse_url($baseUrl);
                if($withPort ==true){
                    $baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port']; 
                }else{
                    $baseUrl = $parsed_url['host'];
                }
            }
            return  $baseUrl;
        }
        
        public function getPort($address){
          $baseUrl="";
        	if( !empty($address) ){
                $parsed_url = parse_url($address);
                $baseUrl = $parsed_url['port'];
                }
            return  $baseUrl;
        
         }
}
