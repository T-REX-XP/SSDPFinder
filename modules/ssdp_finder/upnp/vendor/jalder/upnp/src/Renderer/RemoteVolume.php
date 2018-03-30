<?php
/** AVTransport UPnP Class
 * Used for controlling renderers
 *
 * @author jalder
 */

namespace jalder\Upnp\Renderer;

use jalder\Upnp;

class RemoteVolume
{

	/**
	 * Renderers may have the following UPnP Methods:
	 * SetAVTransportURI
	 * SetNextAVTransportURI
	 * GetMediaInfo
	 * GetMediaInfo_Ext
	 * GetTransportInfo
	 * GetPositionInfo
	 * GetDeviceCapabilities
	 * GetTransportSettings
	 * Stop
	 * Play
	 * Pause
	 * Record
	 * Seek
	 * Next
	 * Previous
	 * Ext_Exit
	 * SetPlayMode
	 * SetRecordQualityMode
	 * GetCurrentTransportActions
	 */

	public $ctrlurl;
	private $upnp;

	public function __construct($device)
	{
        $this->upnp = new Upnp\Core();
        $this->ctrlurl = $device;
        if(is_array($device['description']['device']['serviceList']['service'])){
            foreach($device['description']['device']['serviceList']['service'] as $service){
                if($service['serviceId'] == 'urn:upnp-org:serviceId:RenderingControl'){
                    $this->ctrlurl = $this->upnp->baseUrl($device['location']).$service['controlURL'];
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