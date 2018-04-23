<?php
function isLogin(){
  if(isset($_SESSION['login']))
  {
    return true;
  }
  return false;
}
 ?>
