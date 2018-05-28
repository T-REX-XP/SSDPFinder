<?php
/** AVTransport UPnP Class
 * Used for controlling renderers
 *
 * @author jalder
 */

namespace jalder\Upnp\BinaryLight;

use jalder\Upnp;

class Remote
{


	public $ctrlurl;
	private $upnp;

	public function __construct($device)
	{
        $this->upnp = new Upnp\Core();
        $this->ctrlurl = $device;
        if($device['description']['device']['serviceList']['service']['serviceId'] == 'urn:upnp-org:serviceId:SwitchPower:1'){
            $this->ctrlurl = $this->upnp->baseUrl($device['location']).$device['description']['device']['serviceList']['service']['controlURL'];
	     	print ($this->ctrlurl);
            }
     }

	
	//this should be moved to the upnp and renderer model
	public function getControlURL($description_url, $service = 'SwitchPower')
	{
		$description = $this->getDescription($description_url);

		switch($service)
		{
			case 'SwitchPower':
				$serviceType = 'urn:schemas-upnp-org:service:SwitchPower:1';
				break;
			default:
				$serviceType = 'urn:schemas-upnp-org:service:SwitchPower:1';
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


	public function off()
	{
		$args = array('BinaryState'=>0);
		return $this->upnp->sendRequestToDevice('SetBinaryStateResponse',$args,$this->ctrlurl,$type = 'SwitchPower');
	}

	public function on()
	{
		$args = array('BinaryState'=>1);
		return $this->upnp->sendRequestToDevice('SetBinaryStateResponse',$args,$this->ctrlurl,$type = 'SwitchPower');
	}
}