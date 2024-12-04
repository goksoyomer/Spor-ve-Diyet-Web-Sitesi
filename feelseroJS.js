
//var findLocation = document.getElementById("notificationBell").getBoundingClientRect();
//document.getElementById("notifyDiv").style.left = findLocation.left;
//document.getElementById("notifyDiv").style.top = findLocation.top;
//var notifyWidthCtrl = window.getComputedStyle(document.getElementById("notifyDiv")).getPropertyValue("width").match(/\d+/);
//var notifyHeightCtrl = window.getComputedStyle(document.getElementById("notifyDiv")).getPropertyValue("height").match(/\d+/);
//document.getElementById("notificationBell").addEventListener("click", openCloseNotify);
var isOpen = window.getComputedStyle(document.getElementById("leftNavBar")).getPropertyValue("width").match(/\d+/);
document.getElementById("openNavBar").addEventListener("click", openCloseNav);
 function openCloseNav(){
  if(isOpen == 0){
    document.getElementById("leftNavBar").style.width = "250px";
    isOpen = 250;
    return;
  }else if(isOpen == 250){
    document.getElementById("leftNavBar").style.width = "0px";
    isOpen = 0;
  }
}
 /*function openCloseNotify(){
  if(notifyWidthCtrl == 0 && notifyHeightCtrl == 0){
    document.getElementById("notifyDiv").style.width = "240px";
    document.getElementById("notifyDiv").style.height = "300px";
    notifyWidthCtrl = 240;
    notifyHeightCtrl = 300;
    return;
  }else if(notifyWidthCtrl == 240 && notifyHeightCtrl == 300){
    document.getElementById("notifyDiv").style.width = "0px";
    document.getElementById("notifyDiv").style.height = "0px";
    notifyWidthCtrl = 0;
    notifyHeightCtrl = 0;
  }
}*/
