<?php


public function getNotificationByUsertId($id){


}









$boutiquier = auth()->user(); 


$getMyNotify = Notification::where("notifiable_id", $boutiquier->id)->get();