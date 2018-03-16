<?php
/*
* @version 0.1 (wizard)
*/
require('upnp/vendor/autoload.php');
use jalder\Upnp\Upnp;
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $qry="1";
  // search filters
  // QUERY READY
  global $save_qry;
  if ($save_qry) {
   $qry=$session->data['ssdp_devices_qry'];
  } else {
   $session->data['ssdp_devices_qry']=$qry;
  }
  if (!$qry) $qry="1";
  $sortby_ssdp_devices="ID DESC";
  $out['SORTBY']=$sortby_ssdp_devices;
  // SEARCH RESULTS
  $res= SQLSelect("SELECT * FROM ssdp_devices WHERE $qry ORDER BY ".$sortby_ssdp_devices);
  if ($res[0]['ID']) {
   //paging($res, 100, $out); // search result paging
   $total=count($res);
   for($i=0;$i<$total;$i++) {
    // some action for every record if required

    $ip = $res[$i]['ADDRESS'];
    if($ip){
      $ip = parse_url($ip)['host'];

      $table_name='pinghosts';
      $pingHostExist=SQLSelectOne("SELECT * FROM $table_name WHERE HOSTNAME='$ip'");
  
      if($pingHostExist && $pingHostExist['ID']){
        $res[$i]['DEVICE_ONLINE_ID'] = $pingHostExist['ID'];
      }

    }
   


    $tmp=explode(' ', $res[$i]['UPDATED']);
    $res[$i]['UPDATED']=fromDBDate($tmp[0])." ".$tmp[1];
   }
   /*
   print("<pre>");
   print_r($res);
   print("</pre>");
*/
   $out['RESULT']=$res;
  }







