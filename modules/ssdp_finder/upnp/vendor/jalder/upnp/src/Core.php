<?php

namespace jalder\Upnp;

class Core {
    private $user_agent;
    public $cache;

    public function __construct() {
        $this->user_agent = 'Majordomo/ver-x.x UDAP/2.0 Win/7';
        //$this->user_agent = 'Xbox';
    }
    
    public function search($st = 'ssdp:all', $mx = 2, $man = 'ssdp:discover', $from = null, $port = null, $sockTimout = '2')
    {
        //create the socket
        $socket = socket_create(AF_INET, SOCK_DGRAM, 0);
        socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);

        //all
        $request = 'M-SEARCH * HTTP/1.1'."\r\n";
        $request .= 'HOST: 239.255.255.250:1900'."\r\n";
        $request .= 'MAN: "'.$man.'"'."\r\n";
        $request .= 'MX: '.$mx.''."\r\n";
        $request .= 'ST: '.$st.''."\r\n";
        $request .= 'USER-AGENT: '.$this->user_agent."\r\n";
        $request .= "\r\n";
        
        socket_sendto($socket, $request, strlen($request), 0, '239.255.255.250', 1900);

        // send the data from socket
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>$sockTimout, 'usec'=>'128'));
        $response = array();
        do {
            $buf = null;
            if (($len = @socket_recvfrom($socket, $buf, 2048, 0, $ip, $port)) == -1) {
                echo "socket_read() failed: " . socket_strerror(socket_last_error()) . "\n";
            }
            if(!is_null($buf)){
                $data = $this->parseSearchResponse($buf);
                $response[$data['usn']] = $data;
            }
        } while(!is_null($buf));
        socket_close($socket);

        return $response;
    }

   
    public function search_3rddevice($sockTimout = '2') {
        $response = array();
         // сканируем остальные устройства отдельно
        $other = $this->search_OTHER($sockTimout = '2');
        // сканируем магикхом устройства отдельно
        $mghome = $this->search_MAGICHOME($sockTimout = '2');
        // сканируем ксяоми устройства отдельно
        $xyaomi = $this->search_XYAOMIDEVICES($sockTimout = '2');
        // сканируем ксяоми устройства отдельно
        $mag250 = $this->search_MAG250($sockTimout = '2');
        // сканируем ксяоми устройства отдельно
        $onvif = $this->search_ONVIF($sockTimout = '2');
        // соеденяем ответы в кучу
        $response = array_merge($other, $mghome, $xyaomi, $mag250, $onvif);        
        return $response;
    }

//фунция поиска ONVIF устройств
private function search_ONVIF($sockTimout = '2') {
    $response = array();
    $post_data = '<?xml version="1.0" encoding="UTF-8"?><e:Envelope xmlns:e="http://www.w3.org/2003/05/soap-envelope" xmlns:w="http://schemas.xmlsoap.org/ws/2004/08/addressing" xmlns:d="http://schemas.xmlsoap.org/ws/2005/04/discovery" xmlns:dn="http://www.onvif.org/ver10/network/wsdl"><e:Header><w:MessageID>uuid:84ede3de-7dec-11d0-c360-f01234567890</w:MessageID><w:To e:mustUnderstand="true">urn:schemas-xmlsoap-org:ws:2005:04:discovery</w:To><w:Action a:mustUnderstand="true">http://schemas.xmlsoap.org/ws/2005/04/discovery/Probe</w:Action></e:Header><e:Body><d:Probe><d:Types>dn:NetworkVideoTransmitter</d:Types></d:Probe></e:Body></e:Envelope>';
    // create socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    //socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
    socket_bind($socket, 0, 33333);
    socket_sendto($socket, $post_data, strlen($post_data) , 0, '239.255.255.250', 3702);
    socket_set_option($socket, IPPROTO_IP, MCAST_JOIN_GROUP, array('group' => '239.255.255.250'));
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'256'));
    do {
        $buf = null;
        @socket_recvfrom($socket, $buf, 2048, 0, $ip, $port);
        if (!is_null($buf)) {
            // остальные ответы от всехустройств
            $data = $this->parseSearchResponse($buf);
            $response[$data['usn']] = $buf;
            }
         } while (!is_null($buf));
    socket_close($socket);
    return $response;
    }
	
//фунция поиска MAG устройств
private function search_MAG250($sockTimout = '2') {
    $response = array();
    $arr = array('protocol' => 'remote_stb_1.0', 'port' => 6777 );
    $post_data = json_encode($arr);
    // create socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);
    socket_bind($socket, 0, 6777);
    socket_sendto($socket, $post_data, strlen($post_data) , 0, '255.255.255.255', 6000);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'256'));
    do {
        $buf = null;
        @socket_recvfrom($socket, $buf, 4096, 0, $mip, $mport);
        if (!is_null($buf)) {
            if (json_decode($buf, true))  {
                //если это МАГ 250 и емы подобные то парсим этим путем
                $data = $this->parsemag250($buf, $mip);
                $response[$data['usn']] = $data;
            } else {
                // остальные ответы от всехустройств
                $response[$data['usn']] = $buf;
                }
            }
         } while (!is_null($buf));
    socket_close($socket);
    return $response;
    }
	
//фунция поиска ксяоми устройств
private function search_XYAOMIDEVICES($sockTimout = '2') {
    $response = array();
    //create the socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>$sockTimout, 'usec'=>'256'));
    socket_bind($socket, 0, 0);
    // seech ксяоми хом device
    $request = hex2bin('21310020ffffffffffffffffffffffffffffffffffffffffffffffffffffffff');
    socket_sendto($socket, $request, strlen($request), 0, '255.255.255.255', 54321);        
    do {
        $buf = null;
        if (($len = @socket_recvfrom($socket, $buf, 4096, 0, $ip, $port)) == -1) {
                echo "socket_read() failed: " . socket_strerror(socket_last_error()) . "\n";
            }
        if(!is_null($buf)){
            if ((preg_match("/[A-F0-9]{64}/", $buf, $output_array))) {
                $buf=bin2hex($buf);
                $data = $this->parsexaomi($buf, $ip);
                $response[$data['usn']] = $data;
            } else {
                // остальные ответы от всехустройств
                $response[$data['usn']] = $buf;
            }
        }
    } while(!is_null($buf));
    socket_close($socket);
    return $response;
    }
    
private function search_MAGICHOME($sockTimout = '2') {
    $response = array();
    //create the socket
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
    socket_bind($socket, 0, 0);
    // поиск устройств milight, MagicHome
    $request = 'HF-A11ASSISTHREAD';
    socket_sendto($socket, $request, strlen($request), 0, '255.255.255.255', 48899);       
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>$sockTimout, 'usec'=>'256'));
    do {
        $buf = null;
        if (($len = @socket_recvfrom($socket, $buf, 2048, 0, $ip, $port)) == -1) {
            echo "socket_read() failed: " . socket_strerror(socket_last_error()) . "\n";
            }
        if(!is_null($buf)){
            if (preg_match("/.+[,][A-F0-9]{12}[,].+/", $buf, $output_array))  {
               //если это MagicHome и емы подобные то парсим этим путем
                $data = $this->parseMagicHome($buf, $ip);
                $response[$data['usn']] = $data;
            } else {
                // остальные ответы от всехустройств
                $response[$data['usn']] = $buf;
                }
            }
    } while(!is_null($buf));
    socket_close($socket);
    return $response;
    }

    
public function search_OTHER($sockTimout = '2') {
        $response = array();
        //create the socket
        $socket = socket_create(AF_INET, SOCK_DGRAM, 0);
        socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);
        //поиск устройств yeelight
        $request = 'M-SEARCH * HTTP/1.1'."\r\n";
        $request .= 'HOST: 239.255.255.250:1982'."\r\n";
        $request .= 'MAN: "ssdp:discover"'."\r\n";
        $request .= 'MX: 2'."\r\n";
        $request .= 'ST: wifi_bulb'."\r\n";
        $request .= "\r\n";
        socket_sendto($socket, $request, strlen($request), 0, '255.255.255.255', 1982);        
         //all
        $request = 'M-SEARCH * HTTP/1.1'."\r\n";
        $request .= 'HOST: 239.255.255.250:1900'."\r\n";
        $request .= 'MAN: "ssdp:discover"'."\r\n";
        $request .= 'MX: 2'."\r\n";
        $request .= 'ST: ssdp:all'."\r\n";
        $request .= 'USER-AGENT: '.$this->user_agent."\r\n";
        $request .= "\r\n";
        
        // search device of you PC
        socket_sendto($socket, $request, strlen($request), 0, '255.255.255.255', 1900);
        // send the data from socket
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>$sockTimout, 'usec'=>'256'));
        do {
            $buf = null;
            if (($len = @socket_recvfrom($socket, $buf, 2048, 0, $ip, $port)) == -1) {
                echo "socket_read() failed: " . socket_strerror(socket_last_error()) . "\n";
            }
            if (strstr($buf, 'HTTP/1.1 200 OK')) {
                // обычный парсинг строки
                $data = $this->parseSearchResponse($buf);
                $response[$data['usn']] = $data;
            } else {
                // остальные ответы от всехустройств
                $response[$data['usn']] = $buf;
            }
        } while(!is_null($buf));
        socket_close($socket);
		        return $response;
}
	
	
// парсинг ксяоми и их клонов    
private function parsexaomi($response, $ip)
    {
        //var_dump($response);
        $parsedResponse = array();
        $parsedResponse['XHOMEdeviceip'] = $ip;
        $parsedResponse['XHOMEdevicetype'] = substr($response, 32, 4);
        $parsedResponse['XHOMEdeviceID'] = substr($response, 36, 4);
        return $parsedResponse;
    }
    // парсинг MIHOME и их клонов    
private function parseMagicHome($response, $ip) {
    //var_dump($response);
    $parsedResponse = array();
    $par=explode(",",$response);
    $parsedResponse['MHip'] = $ip;
    $parsedResponse['MHMAC'] = $par[1];
    $parsedResponse['MHname'] = $par[2];
    return $parsedResponse;
    }

// парсинг маг250 и их клонов
private function parsemag250($response, $ip)
    {
        //var_dump($response);
        $messages = explode(",", $response);
        $parsedResponse = array();
        foreach( $messages as $row ) {
            $parsedResponse['MAGaddres'] = $ip;
            if( stripos( $row, '"name":') === 0 )
                $parsedResponse['MAGname'] = str_ireplace( '"', '', str_ireplace( '"name":"', '', $row ));
            if( stripos( $row, '"serialNumber":"') === 0 )
                $parsedResponse['MAGSN'] = str_ireplace( '"', '', str_ireplace( '"serialNumber":"', '', $row ));
            if( stripos( $row, '"type":"') === 0 )
                $parsedResponse['type'] = str_ireplace( '"', '', str_ireplace( '"type":"', '', $row ));
        }
        return $parsedResponse;
    }
// парсим осталные ответы
private function parseSearchResponse($response)
    {
        //var_dump($response);
        $messages = explode("\r\n", $response);
        $parsedResponse = array();
        foreach( $messages as $row ) {
            if( stripos( $row, 'http' ) === 0 )
                $parsedResponse['http'] = $row;
            if( stripos( $row, 'cach' ) === 0 )
                $parsedResponse['cache-control'] = str_ireplace( 'cache-control: ', '', $row );
            if( stripos( $row, 'date') === 0 )
                $parsedResponse['date'] = str_ireplace( 'date: ', '', $row );
            if( stripos( $row, 'ext') === 0 )
                $parsedResponse['ext'] = str_ireplace( 'ext: ', '', $row );
            if( stripos( $row, 'loca') === 0 )
                $parsedResponse['location'] = str_ireplace( 'location: ', '', $row );
            if( stripos( $row, 'serv') === 0 )
                $parsedResponse['server'] = str_ireplace( 'server: ', '', $row );
            if( stripos( $row, 'st:') === 0 )
                $parsedResponse['st'] = str_ireplace( 'st: ', '', $row );
            if( stripos( $row, 'usn:') === 0 )
                $parsedResponse['usn'] = str_ireplace( 'usn: ', '', $row );
            if( stripos( $row, 'cont') === 0 )
                $parsedResponse['content-length'] = str_ireplace( 'content-length: ', '', $row );
        }
        $parsedResponse['description'] = $this->getDescription($parsedResponse['location']);
        return $parsedResponse;
    }
    
    public function getDescription($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        libxml_use_internal_errors(true); 
        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $desc = (array)json_decode($json, true);
        curl_close($ch);
        return $desc;
    }

    public function getHeader($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($content, 0, $size);
        curl_close($ch);
        $messages = explode("\r\n", $header);
        $parsed = [];
        foreach($messages as $m) {
            if(count(explode(':',$m))>1){
                list($param, $value) = explode(':',$m, 2);
                $parsed[$param] = $value;
            } else{
                $parsed[$m] = $m;
            }
        }
        $parsed['httpCode'] = $httpCode;
        return $parsed;
    }

    public function sendRequestToDevice($method, $arguments, $url, $type, $hostIp = '127.0.0.1', $hostPort = '80')
    {
        $body  ='<?xml version="1.0" encoding="utf-8"?>' . "\r\n";
        $body .='<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">';
        $body .='<s:Body>';
        $body .='<u:'.$method.' xmlns:u="urn:schemas-upnp-org:service:'.$type.':1">';
        foreach( $arguments as $arg=>$value ) {
            $body .='<'.$arg.'>'.$value.'</'.$arg.'>';
        }
        $body .='</u:'.$method.'>';
        $body .='</s:Body>';
        $body .='</s:Envelope>';
 
        $header = array(
            'Host: '.$this->getLocalIp().':'.$hostPort,
            'User-Agent: '.$this->user_agent, //fudge the user agent to get desired video format
            'Content-Length: ' . strlen($body),
            'Connection: close',
            'Content-Type: text/xml; charset="utf-8"',
            'SOAPAction: "urn:schemas-upnp-org:service:'.$type.':1#'.$method.'"',
        );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, TRUE );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $body );
        $response = curl_exec( $ch );
        curl_close( $ch );
        $doc = new \DOMDocument();
        $doc->loadXML($response);
        $result = $doc->getElementsByTagName('Result');
        if(is_object($result->item(0))){
            return $result->item(0)->nodeValue;
        }
        return $response;
    }
    // получает айпи адрес с портом или без 
    public function baseUrl($url)
    {
        $url = parse_url($url);
        return $url['scheme'].'://'.$url['host'].':'.$url['port'];
    }

    public function setUserAgent($agent)
    {
        $this->user_agent = $agent;
    }
    //получаем hostname адрес локального компьютера
    private function getLocalIp() { 
      return gethostbyname(trim(`hostname`)); 
    }
}
