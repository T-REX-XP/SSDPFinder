<?php
$status = $this->getProperty("onoff");
if ($status){
  $this->setProperty("onoff",0);
} else {
  $this->setProperty("onoff",1);
};
