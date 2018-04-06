<?php
/** AVTransport UPnP Class
 * Used for controlling renderers
 *
 * @author jalder
 */

namespace jalder\Upnp\Renderer;

use jalder\Upnp;

class RemoteVolume {
    public $ctrlurl;
    private $upnp;
    public function __construct($server) {
    $this->upnp = new Upnp\Core();
    $control_url = str_ireplace("Location:", "", $server);
    $xml=simplexml_load_file($control_url);
    foreach($xml->device->serviceList->service as $service){
          if($service->serviceId == 'urn:upnp-org:serviceId:RenderingControl'){
                $chek_url = (substr($service->controlURL,0,1));
                if ($chek_url == '/') {
                   $this->ctrlurl = ($this->upnp->baseUrl($control_url,True).$service->controlURL);
                 } else {
                    $this->ctrlurl = ($this->upnp->baseUrl($control_url,True).'/'.$service->controlURL);
                }
          }
         }
        }


	public function SetVolume($volume)
	{
		$args = array('InstanceId' => 0,'Channel' => 'Master','DesiredVolume' => $volume);
		return $this->upnp->sendRequestToDevice('SetVolume',$args,$this->ctrlurl,$type = 'RenderingControl');
	}

	public function mute()
	{
		$args = array('InstanceId' => 0,'Channel' => 'Master','DesiredMute' => 1);
		return $this->upnp->sendRequestToDevice('SetMute',$args,$this->ctrlurl,$type = 'RenderingControl');
	}
	public function unmute()
	{
		$args = array('InstanceId' => 0,'Channel' => 'Master','DesiredMute' => 0);
		return $this->upnp->sendRequestToDevice('SetMute',$args,$this->ctrlurl,$type = 'RenderingControl');
	}

		//this should be moved to the upnp and renderer model
	public function getControlURL($description_url, $service = 'RenderingControl')
	{
		$description = $this->getDescription($description_url);

		switch($service)
		{
			case 'RenderingControl':
				$serviceType = 'urn:schemas-upnp-org:service:RenderingControl:1';
				break;
			default:
				$serviceType = 'urn:schemas-upnp-org:service:RenderingControl:1';
				break;
		}

		foreach($description['device']['serviceList']['service'] as $service)
		{
			if($service['serviceType'] == $serviceType)
			{
				$url = parse_url($description_url);
				return $url['scheme'].'://'.$url['host'].':'.$url['port'].$service['controlURL'];
			}
		}
	}
}
