<?php
/**
 * @author Jalder
 * Upnp class for interacting with UPnP network devices using PHP socket connections
 *
 * Derived from @author Morten Hekkvang <artheus@github>
 *
 */
require_once('core.php');

class Upnp extends Core{

    public function __construct()
    {

    }

    public function discover()
    {
        return parent::search();
    }

    public function alive()
    {
        return (bool)count($this->discover());
    }

}

