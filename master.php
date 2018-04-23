<?php
require_once './cfg.php';
require_once './gl_fun.php';
session_start();
if (!isLogin()) die ('You have not log on');
  mysql_connect($HOST, $USER, $PASS) or die ("Не могу создать соединение"); //устанавливаем соединение с хостом, если не получилось завершаем скрипт с ошибкой
  mysql_select_db($DB) or die (mysql_error().' вот такая херня');  			//Выбор базы данных или завершение скрипта

function get_category ($number){
    $str = (string)$number;
    $categories = "";
    if ($str[0] == "1") $categories = $categories." software_request";
    if ($str[1] == "1") $categories = $categories." hardware_request";
    if ($str[2] == "1") $categories = $categories." network_request";
    if ($categories == "") $categories = "other_request";
    return $categories;
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

  $query = "SELECT `requests_name`, `requests_description`, `requests_category`, `requests_clientsID`, `requests_cost`, `requests_time`, `clients_telephone`,	`clients_name`,	`clients_address`, `clients_description`  FROM `requests` LEFT JOIN `clients` ON (requests_clientsID = clients_telephone) WHERE `requests_mastersID`=".$_SESSION['login']." OR `requests_mastersID`=0";
  $result = mysql_query($query) or die("Ошибка " . mysql_error());
  if($result)
  {
    $rows = mysql_num_rows($result);
    if (count($rows)>0)
    {
          $row = mysql_fetch_row($result);
          $requests_name = $row[0];
          $requests_description = $row[1];
          $requests_category = get_category($row[2]);
          $requests_clientsID = $row[3];
          $requests_cost = $row[4];
          $requests_time_left = (int)(($row[5] - time())/60/60)." часов ".(($row[5] - time())/60%60)." минут";
          $clients_telephone = $row[6];
          $clients_name = $row[7];
          $clients_address = $row[8];
          $clients_description = $row[9];

    }
  }
  else {
    echo 'server erorr';
  }
  mysql_free_result($result);
  mysql_close();

  //echo $requests_name;
  //echo $requests_description;
  //echo $requests_category;
  //echo $requests_clientsID;
  //echo $requests_cost;


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
          <div class="col-auto d-flex align-items-center"><a href="main.html" style="color: rgb(79, 109, 140);"><i class="fa fa-sign-out fa-2x" data-toggle="tooltip" title="Выйти"></i></a></div>
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
              <form>
                <h5 class="card-title">Сортировка    </h5>
                <div class="form-group">
                  <select class="custom-select">
                    <option>Стоимость &uarr;</option>
                    <option>Стоимость &darr;</option>
                    <option selected>Горящие заявки</option>
                  </select>
                </div>
                <h5 class="card-title">Виды работ</h5>
                <div class="form-group">
                  <div class="custom-control custom-checkbox" checked="true">
                    <input class="custom-control-input" type="checkbox" id="software"/>
                    <label class="custom-control-label" for="software">Программное обеспечение</label>
                  </div>
                  <div class="custom-control custom-checkbox" checked="true">
                    <input class="custom-control-input" type="checkbox" id="hardware"/>
                    <label class="custom-control-label" for="hardware">Аппаратное обеспечение</label>
                  </div>
                  <div class="custom-control custom-checkbox" checked="true">
                    <input class="custom-control-input" type="checkbox" id="network"/>
                    <label class="custom-control-label" for="network">Сеть</label>
                  </div>
                  <div class="custom-control custom-checkbox" checked="true">
                    <input class="custom-control-input" type="checkbox" id="other"/>
                    <label class="custom-control-label" for="other">Прочие</label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox" checked="true">
                    <input class="custom-control-input" type="checkbox" id="myRequests"/>
                    <label class="custom-control-label" for="myRequests">Мои заявки</label>
                  </div>
                </div>
                <button class="btn btn-outline-primary btn-block">Показать</button>
              </form>
            </div>
          </div>
        </aside>
        <section class="col-md-8 py-3">
          <div class="row no-gutters">

            <div class="col-12 mb-3 <?php echo $requests_category; ?>">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось <?php echo $requests_time_left; ?></small>
                  </div><span class="h4">
                    <div class="badge badge-warning"><?php echo $requests_cost; ?></div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title"><?php echo $requests_name; ?></h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle"><?php echo $clients_address; ?></small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-0" data-toggle="collapse">Описание заявки</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-0">
                          <hr>
                          <div><?php $requests_description; ?></div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>

            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось 2 часа</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">500 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Не включается компьютер</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">Путилково, 24, Дом 7, к1, кв. 40 домофон 45к8112</small></p>
                  <p class="text-muted"><i class="fa fa-user mr-2"></i><small class="card-subtitle">Наталья 8 906 058 45 62</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-0" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-0">
                          <hr>
                          <div>Диагностика</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Отказаться</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 8 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">1800 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Тормозит компьютер, рекламные баннеры в браузере</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">проспект Мира, 86</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-1" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">2</span>
                        <div class="collapse" id="bid-actions-1">
                          <hr>
                          <div>Оптимизация Windows</div>
                          <div>Удаление вирусов</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось 4 часа</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">3000 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Установить Windows, настроить роутер, настроить принтер</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Суворова, 133</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-2" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">3</span>
                        <div class="collapse" id="bid-actions-2">
                          <hr>
                          <div>Установка Windows</div>
                          <div>Настройка роутера</div>
                          <div>Настройка принтера</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 23 часа</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">500 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Не загружается Windows</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Плеханова, 61</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-3" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-3">
                          <hr>
                          <div>Диагностика</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 4 дня</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">1000 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Провести и обжать витую пару</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Мира, 53</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-4" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-4">
                          <hr>
                          <div>Провести и обжать витую пару</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 17 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">2000 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Собрать системный блок</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Бетонщиков, 1</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-5" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-5">
                          <hr>
                          <div>Собрать системный блок</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 10 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">500 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Выключается компьютер</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Садовая, 19</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-6" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-6">
                          <hr>
                          <div>Диагностика</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><i class="fa fa-fire text-danger mr-2" data-toggle="tooltip" title="Горящая заявка"></i><small class="text-muted">Осталось 5 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">1200 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Перегревается системный блок</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Ленина, 164</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-7" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-7">
                          <hr>
                          <div>Диагностика</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 10 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">400 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Вылетает iTunes</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Дзержинского, 10</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-8" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-8">
                          <hr>
                          <div>Установка iTunes</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div><small class="text-muted">Осталось 7 часов</small>
                  </div><span class="h4">
                    <div class="badge badge-warning">500 ₽</div></span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Настроить принтер</h5>
                  <p class="text-muted"><i class="fa fa-map-marker mr-2"></i><small class="card-subtitle">улица Дружбы, 58</small></p>
                  <div class="mb-3">
                    <ul class="list-group">
                      <li class="list-group-item"><a class="mr-2" href="#bid-actions-9" data-toggle="collapse">Список работ</a><span class="badge badge-secondary">1</span>
                        <div class="collapse" id="bid-actions-9">
                          <hr>
                          <div>Настройка принтера</div>
                        </div>
                      </li>
                      <li class="list-group-item"><a class="mr-2" href="#bid-additions-9" data-toggle="collapse">Дополнения</a>
                        <div class="collapse" id="bid-additions-9">
                          <hr>
                          <div>Дратути))0)</div>
                        </div>
                      </li>
                    </ul>
                  </div><a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modal-confirm-bid">Принять</a>
                </div>
              </div>
            </div>
            <nav class="w-100 d-block">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#"><span>«</span></a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">&hellip;</a></li>
                <li class="page-item"><a class="page-link" href="#">26</a></li>
                <li class="page-item"><a class="page-link" href="#"><span>»</span></a></li>
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
          <p>Вы уверены, что желаете принять заявку?</p>
          <p>С Вашего лицевого счёта будет списано 200 ₽</p>
        </div>
        <div class="modal-footer"><a class="btn btn-secondary" href="#" data-dismiss="modal">Отменить</a><a class="btn btn-primary" href="#">Принять</a></div>
      </div>
    </div>
  </div>
  <script src="js/libs.js"></script>
  <script src="js/scripts.js"></script>
</html>
