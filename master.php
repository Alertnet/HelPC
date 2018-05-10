        <?php
require_once './cfg.php';
require_once './gl_fun.php';
session_start();
if (!isLogin()) {
  header('Location: ./login.php');
  exit;
}
  mysql_connect($HOST, $USER, $PASS) or die ("Не могу создать соединение"); //устанавливаем соединение с хостом, если не получилось завершаем скрипт с ошибкой
  mysql_select_db($DB) or die (mysql_error().' вот такая херня');  		     	//Выбор базы данных или завершение скрипта
if (isset($_GET["logout"])) {
  session_unset();
  session_destroy();
  header('Location: ./index.html');
  exit;
}
if (isset($_GET["action"])) {
  $action=$_GET["action"];
  if ($action=="accept") {
	$query = "UPDATE `requests` SET `requests_mastersID` = '".$_SESSION['login']."' WHERE `requests`.`requests_id` = ".$_GET['requestid'].";";
    $result = mysql_query($query) or die("Ошибка " . mysql_error());
	$query = "SELECT `requests_cost` FROM `requests` WHERE `requests_id`=".$_GET['requestid'].";";
	$result = mysql_query($query) or die("Ошибка " . mysql_error());
	$cost = mysql_fetch_row($result)[0];
	$query = "UPDATE `masters` SET `masters_balance` = `masters_balance`-".$cost." WHERE `masters`.`masters_log` = ".$_SESSION['login'].";";
    $result = mysql_query($query) or die("Ошибка " . mysql_error());
  }
  if ($action=="decline") {
    $query = "UPDATE `requests` SET `requests_mastersID` = '0' WHERE `requests`.`requests_id` = ".$_GET['requestid'].";";
    $result = mysql_query($query) or die("Ошибка " . mysql_error());
  }
}
if (isset($_GET["page"])) {$page = $_GET["page"];}
else {$page = "1";}
if (isset($_GET["sort"])) {$sort = $_GET["sort"];}
else {
  $sort = "";
}
if (isset($_GET["own"]))  {$own  = $_GET["own"];}
else {
  $own = 'off';
}
if (isset($_GET["category"])) {$category = $_GET["category"];}
else {
  $category = "";
}

class Request
{
  public $requests_mastersID    = "";
  public $requests_name         = "";
  public $requests_description  = "";
  public $requests_category     = "";
  public $requests_clientsID    = "";
  public $clients_name          = "";
  public $clients_description   = "";
  public $requests_cost         = "";
  public $requests_time_left    = "";
  public $clients_address       = "";
  public $requests_id           = "";
  public $forPrint              = 1;

  function __construct($requests_name, $requests_description, $requests_category, $requests_clientsID, $requests_cost, $requests_time, $clients_telephone, $clients_name, $clients_address, $clients_description, $requests_mastersID, $requests_id)
  {
    $this->requests_id           = $requests_id;
    $this->requests_mastersID     = $requests_mastersID;
    $this->requests_name          = $requests_name;
    $this->requests_description   = $requests_description;
    $this->requests_category      = $requests_category;
    if ($this->requests_mastersID == $_SESSION['login']){
      $this->requests_clientsID     = $requests_clientsID;
      $this->clients_name           = $clients_name;
      $this->clients_description    = $clients_description;
      $this->clients_telephone      = $clients_telephone;
    }
    $this->requests_cost          = $requests_cost;
    $this->requests_time_left     = (int)(($requests_time - time())/60/60)." часов ".(($requests_time - time())/60%60)." минут";
    $this->clients_address        = $clients_address;
  }




  function print_request (){
    if ($this->requests_mastersID != $_SESSION['login']){
    echo '
    <div class="col-12 mb-3 '.get_category($this->requests_category).'" data-id="'.$this->requests_id.'">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось '.$this->requests_time_left.'</small>
          </div><span class="h4">
            <div class="badge badge-warning">'.$this->requests_cost.' &#8381;</div></span>
        </div>
        <div class="card-body">
          <h5 class="card-title">'.$this->requests_name.'</h5>
          <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">'.$this->clients_address.'</small></p>
          <div class="mb-3">
            <ul class="list-group">
              <li class="list-group-item"><a class="mr-2" href="#bid-'.$this->requests_id.'" data-toggle="collapse">Описание заявки</a><span class="badge badge-secondary">1</span>
                <div class="collapse" id="bid-'.$this->requests_id.'">
                  <hr>
                  <div>'.$this->requests_description.'</div>
                </div>
              </li>
            </ul>
          </div><a class="btn btn-outline-primary" data-action="accept" href="#" data-id="'.$this->requests_id.'"data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
        </div>
      </div>
    </div>
    ';}
    else{
      echo '
      <div class="col-12 mb-3 '.get_category($this->requests_category).'" data-id="'.$this->requests_id.'">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось '.$this->requests_time_left.'</small>
            </div><span class="h4">
              <div class="badge badge-warning">'.$this->requests_cost.' &#8381;</div></span>
          </div>
          <div class="card-body">
            <h5 class="card-title">'.$this->requests_name.'</h5>
            <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">'.$this->clients_address.' '.$this->clients_description.'</small></p>
            <p class="text-muted"><i class="fa fa-user mr-2"></i><small class="card-subtitle">'.$this->clients_name.' '.$this->clients_telephone.'</small></p>
            <div class="mb-3">
              <ul class="list-group">
                <li class="list-group-item"><a class="mr-2" href="#bid-'.$this->requests_id.'" data-toggle="collapse">Описание заявки</a><span class="badge badge-secondary">1</span>
                  <div class="collapse" id="bid-'.$this->requests_id.'">
                    <hr>
                    <div>'.$this->requests_description.'</div>
                  </div>
                </li>
              </ul>
            </div><a class="btn btn-outline-danger" href="#" data-id="'.$this->requests_id.'"data-action="decline" data-toggle="modal" data-target="#modal-confirm-bid">Отказаться</a>
          </div>
        </div>
      </div>
      ';}
  }
}


function get_category ($number){
    $str = (string)$number;
    $categories = "";
    if ($str[0] == "1") $categories = $categories." software_request";
    if ($str[1] == "1") $categories = $categories." hardware_request";
    if ($str[2] == "1") $categories = $categories." network_request";
    if ($categories == "") $categories = "other_request";
    return $categories;
}

function sortByUpCost($f1, $f2)
{
  if($f1->requests_cost < $f2->requests_cost) return -1;
  elseif($f1->requests_cost > $f2->requests_cost) return 1;
  else return 0;
}
function sortByDownCost($f1, $f2)
{
  if($f1->requests_cost > $f2->requests_cost) return -1;
  elseif($f1->requests_cost < $f2->requests_cost) return 1;
  else return 0;
}
function sortByUpTime($f1, $f2)
{
  if($f1->requests_time_left < $f2->requests_time_left) return -1;
  elseif($f1->requests_time_left > $f2->requests_time_left) return 1;
  else return 0;
}
function sortByDownTime($f1, $f2)
{
  if($f1->requests_time_left < $f2->requests_time_left) return -1;
  elseif($f1->requests_time_left > $f2->requests_time_left) return 1;
  else return 0;
}

  $query = "SELECT `masters_balance`, `masters_img`, `masters_rating`, `masters_quantity`, `masters_name`, `masters_surename` FROM `masters` WHERE masters_log=".$_SESSION['login'];
  $result = mysql_query($query) or die("Ошибка " . mysql_error());
  if($result)
  {
	  $rows = mysql_num_rows($result);
    if (count($rows)>0)
    {
          $row = mysql_fetch_row($result);
          $balance = $row[0];
          $img = $row[1];
          $rating = $row[2];
          $quantity = $row[3];
          $name = $row[4];
          $surename = $row[5];
		}
  }
  else {
    echo 'server erorr';
  }

  $query = "SELECT `requests_name`, `requests_description`, `requests_category`, `requests_clientsID`, `requests_cost`, `requests_time`, `clients_telephone`,	`clients_name`,	`clients_address`, `clients_description`, `requests_mastersID`, `requests_id`  FROM `requests` LEFT JOIN `clients` ON (requests_clientsID = clients_telephone) WHERE `requests_mastersID`=".$_SESSION['login']." OR `requests_mastersID`=0";
  $result = mysql_query($query) or die("Ошибка " . mysql_error());
  if($result)
  {
    $rows = mysql_num_rows($result);
    if (count($rows)>0)
    {
      $requests=[];
      for ($i=0; $i < $rows; $i++) {
        $row = mysql_fetch_row($result);
        $requests[$i] = new Request($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11]);
      }
    }
  }
  else {
    echo 'server erorr';
  }
  mysql_free_result($result);
  mysql_close();
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <link href="css/style.css" rel="stylesheet">
    <title>HelPC - Личный Кабинет</title>

  </head>
  <body class="bg-light">
    <div class="bg-dark">
      <div class="container py-3">
        <div class="row justify-content-between">
          <div class="col-auto d-flex align-items-center"><img class="rounded-circle float-left mr-3" src="img/<?php echo $img;?>" alt="profile-photo" width="75px" height="75px">
            <div><span class="text-light"><?php  echo $name." ".$surename;?></span>
              <div style="color: rgb(79, 109, 140);"><i class="fa fa-star-o mr-2" style="color: rgb(79, 109, 140);" data-toggle="tooltip" title="Рейтинг"></i><span class="text-white"><?php echo $rating; ?></span></div>
              <div><i class="fa fa-credit-card mr-2" style="color: rgb(79, 109, 140);" data-toggle="tooltip" title="Баланс"></i><span class="text-white mr-2"><?php  echo $balance;?></span><a class="badge badge-primary" href="#">пополнить</a></div>
            </div>
          </div>
          <div class="col-auto d-flex align-items-center"><a href="./master.php?logout=1" style="color: rgb(79, 109, 140);"><i class="fa fa-sign-out fa-2x" data-toggle="tooltip" title="Выйти"></i></a></div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row align-items-start">
        <aside class="sidebar col-md-4 pt-3 sticky-top">
          <div class="card">
            <div class="card-header border-bottom-0">
              <h5 class="mb-0">Настройки<a class="float-right" href="#sort" data-toggle="collapse"><i class="text-secondary fa fa-bars"></i></a></h5>
            </div>
            <div class="card-body border-top collapse" id="sort">
              <form action="master.php">
                <h5 class="card-title">Сортировка    </h5>
                <div class="form-group">
                  <select class="custom-select" name="sort">
                    <option <?php if ($sort=="sortByDownCost") echo "selected "; ?> value="sortByDownCost">Стоимость &darr;</option>
                    <option <?php if ($sort=="sortByUpCost") echo "selected "; ?> value="sortByUpCost">Стоимость &uarr;</option>
                    <option <?php if ($sort=="sortByUpTime") echo "selected "; ?> value="sortByUpTime">Осталось времени &uarr;</option>
                    <option <?php if ($sort=="sortByDownTime") echo "selected "; ?> value="sortByDownTime">Осталось времени &darr;</option>
                  </select>
                </div>
                <h5 class="card-title">Виды работ</h5>
                <div class="form-group">
				  <div class="custom-control custom-radio" >
                    <input <?php if ($category=="") echo "checked "; ?> class="custom-control-input" type="radio" name="category" value="" id="all"/>
                    <label class="custom-control-label" for="all">Все категории</label>
                  </div>
                  <div class="custom-control custom-radio" >
                    <input <?php if ($category=="100") echo "checked "; ?> class="custom-control-input" type="radio" name="category" value="100" id="software"/>
                    <label class="custom-control-label" for="software">Программное обеспечение</label>
                  </div>
                  <div class="custom-control custom-radio" >
                    <input 	<?php if ($category=="010") echo "checked "; ?>class="custom-control-input" type="radio" name="category" value="010" id="hardware"/>
                    <label class="custom-control-label" for="hardware">Аппаратное обеспечение</label>
                  </div>
                  <div class="custom-control custom-radio" >
                    <input <?php if ($category=="001") echo "checked "; ?>class="custom-control-input" type="radio" name="category" value="001" id="network"/>
                    <label class="custom-control-label" for="network">Сеть</label>
                  </div>
                  <div class="custom-control custom-radio" >
                    <input <?php if ($category=="000") echo "checked "; ?>class="custom-control-input" type="radio" name="category" value="000" id="other"/>
                    <label class="custom-control-label" for="other">Прочие</label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox" checked="true">
                    <input <?php if ($own=="on") echo "checked "; ?>class="custom-control-input" type="checkbox" name="own" id="myRequests"/>
                    <label class="custom-control-label" for="myRequests">Только мои заявки</label>
                  </div>
                </div>
                <button class="btn btn-outline-primary btn-block" type="submit">Показать</button>
              </form>
            </div>
          </div>
        </aside>
        <section class="col-md-8 py-3">
          <div class="row no-gutters">

            <?php
				if ($balance<0){
				echo "Для продолжения работы в личном кабинете необходимо пополнить баланс";
				die();
				}
								
              switch ($sort) {
                case 'sortByUpCost':
                  usort($requests,"sortByUpCost");
                  break;
                case 'sortByDownCost':
                  usort($requests,"sortByDownCost");
                  break;
                case 'sortByUpTime':
                  usort($requests,"sortByUpTime");
                  break;
                case 'sortByDownTSime':
                  usort($requests,"sortByDownTSime");
                  break;
                default:
                  break;
              }


              if ($category!=""){
                  for ($i=0; $i < count($requests); $i++) {if ($requests[$i]->requests_category != $category)  {$requests[$i]->forPrint = 0; }}
              }

              if ($own == 'on') {
                for ($i=0; $i < count($requests); $i++) {
                  if ($requests[$i]->requests_mastersID!=$_SESSION['login']) {
                    $requests[$i]->forPrint = 0;
                  }
                }
              }
			$requestsFP =[];
			for ($i=0; $i < count($requests); $i++){
				if ($requests[$i]->forPrint == 1){
					array_push($requestsFP, $requests[$i]);
				}				
			}
			
			  
			  
              $to = ((int)$page)*10;
              $from = $to-10;
              if (count($requestsFP)==0) {
                echo "Заявок с такими параметрами нет";
              }
              else {
              for ($i=$from; $i < $to; $i++) {
                if ($i < count($requestsFP))
                {$requestsFP[$i]->print_request();}
              }
            }
            ?>


            <nav class="w-100 d-block">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#"><span>«</span></a></li>
                <?php
                  $countOfPages = count($requests)/10;
                  for ($i=1; $i < $countOfPages+1; $i++) {
                    if ($i==$page) {
                      echo '<li class="page-item active"><a class="page-link" href="master.php?own='.$_GET['own'].'&sort='.$_GET['sort'].'&page='.$i.'">'.$i.'</a></li>';
                    }
                    else {
                      echo '<li class="page-item active"><a class="page-link" href="master.php?own='.$_GET['own'].'&sort='.$_GET['sort'].'&page='.$i.'">'.$i.'</a></li>';
                    }
                  }
                ?>
                <li class="page-item disabled"><a class="page-link" href="#"><span>»</span></a></li>
              </ul>
            </nav>
          </div>
        </section>
      </div>
    </div>
    <div class="container">
      <footer class="sticky-footer text-center">
        <p style="color: rgba(52, 58, 64, .3);">&copy; 2017 HelPC. All Rights Reserved.</p>
      </footer>
    </div>
  </body>
  <div class="modal fade" id="modal-confirm-bid" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">

        </div>
        <div class="modal-footer"><a class="btn btn-secondary" href="#" data-dismiss="modal">Отменить</a><a class="btn btn-primary" href="#">Принять</a></div>
      </div>
    </div>
  </div>
  <script src="js/libs.js"></script>
  <script src="js/scripts.js"></script>
</html>
