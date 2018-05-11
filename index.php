<?php 
  header("refresh: 3600;");
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="src/css/bootstrap.min.css">
  <!-- <script type="text/javascript" src="src/js/bootstrap.min.js"></script> -->

  <!-- <link href="https://fonts.googleapis.com/css?family=Mina" rel="stylesheet"> -->
  <!-- <script type="text/javascript" src="src/js/jquery.min.js"></script> -->
  <style>
    html{
      overflow: hidden;
        position: relative;
    }
    body{
      font-family: 'Mina', 'Segoe UI';
      background-image: url("src/img/body.jpg");
      background-size: cover;
    }
    #wrapper{
      margin-right: auto; /* 1 */
      margin-left:  auto; /* 1 */
      width: 100% ;
      max-width: 1600px; /* 2 */

      padding-right: 10px; /* 3 */
      padding-left:  10px; /* 3 */
    }
    table{
      font-size: 30pt;
    }
    table li{
      font-size: 20pt;      
    }
    ul{
      /*list-style-image: url('https://image.flaticon.com/icons/png/128/78/78016.png');*/
    }
    th{
      vertical-align:middle;
      font-size: 22pt;
      text-align: center;   
    }
    .big{
      font-size: 30pt;
    }
    .medium{
      font-size: 25pt;
      text-align: center;
    }
    .marquee {
      margin-top:0;
        position: relative;
        box-sizing: border-box;
        animation: marquee 15s linear infinite;
    }
    /*@keyframes marquee{
      from{
        margin-top: 10%;
      }to{
        margin-top:-50%;
      }
    }*/
    .table-wrap{
      overflow: hidden;
        position: relative;
        box-sizing: border-box;
    }
    #table-bordered{
      margin-top: -1%;
    }

    .footer {
       position: fixed;
       left: 0;
       bottom: 0;
       width: 100%;
       background-image: url(src/img/foot.jpg);
       color: white;
       text-align: center;
    }
  </style>
</head>
<body>
<?php
  // header("refresh: 86400;");
  require_once __DIR__.'/google-api-php-client-2.2.1/vendor/autoload.php';

  session_start();

  $client = new Google_Client();
  $client->setAuthConfig('client_secrets.json');
  $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
  $client->setAccessType('offline');
  // $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/jadwalKabanWai/oauth2callback.php';
  //     header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Print the next 10 events on the user's calendar.
    $calendarId = 'primary';
    $optParams = array(
      'maxResults' => 10,
      'orderBy' => 'startTime',
      'singleEvents' => TRUE,
      'timeMin' => date('c'),
    );
    
    $service = new Google_Service_Calendar($client);
    $results = $service->events->listEvents($calendarId, $optParams);
    // $results2 = $service->events->get($calendarId, $optParams);
    // echo $results2->getSummary();
    ?>
    <div id="wrapper">
      <div style="font-size: 30pt;">
      <img src="src/img/kemenkumham.jpg" style="float: left;margin:1%;width: 6%;max-width: 100pt;"/>
        <span style="text-align:center;padding-top:1%;color:#0984e3;width:84%;float:left">
          <b>AGENDA BAGIAN KEPEGAWAIAN</b>
          <br>
          <div style="font-size: 20pt;"><?php echo date('d M Y'); ?></div>
        </span>
        <img src="src/img/imi.png" style="margin:1%;right: 0;width: 6%;max-width: 100pt;" />
      </div>
      <br>
      <div class="table-wrap">
          <table class="table table-bordered">
              <tr class="table-light" align="center";">
                <th width="2%" style="vertical-align: middle" rowspan="2">NO</th>
                <th width="31%" style="vertical-align: middle" rowspan="2">KEGIATAN</th>
                <th width="26%" style="vertical-align: middle" colspan="2">WAKTU</th>
                <th width="19%" style="vertical-align: middle" rowspan="2">TEMPAT</th>
                <th width="22%" style="vertical-align: middle" rowspan="2">KETERANGAN</th>
              </tr>
              <tr class="table-light" align="center" style="font-size: 20pt">
                <td width="13%">MULAI</td>
                <td width="13%">SELESAI</td>
              </tr>
          </table>
      </div>
      <?php
      if (count($results->getItems()) == 0) {
        print "No upcoming events found.\n";
      } else {
        // print "Upcoming events:\n";
        $i=1;
        ?>

        <div class="table-wrap">
        <table class="table table-bordered" id="table-bordered">
        <?php
        foreach ($results->getItems() as $event) {
          // echo "<pre>";
          // print_r($event);
          // echo "</pre>";
          $start = new DateTime($event->start->dateTime);
          $end = new DateTime($event->end->dateTime);
          if (empty($start)) {
            $start = $event->start->date;
          }
          if (empty($end)) {
            $end = $event->end->date;
          }
          $sisa_waktu = date_diff(date_create($start->format('d-M-Y')),date_create())->days+1;
          if($sisa_waktu >= 1){
            $sisa_waktu="<b>(".$sisa_waktu." HARI LAGI)</b>";
          }else{
            $sisa_waktu="<b>(HARI INI)</b>";
          }
          ?>
          <!--Table-->
          
            <!--Table head-->
              <!--Table body-->
                  <tr>
                      <th rowspan="2" scope="row" width="4.5%"><?php echo $i;?></th>
                      <td rowspan="2" width="31.5%"><b><?php echo $event->getSummary();?></b></td>
                      <td align="center" width="13.5%"><?php echo $start->format('d M')."<br>".$start->format('H:i');?></td>
                      <td align="center" width="13.5%"><?php echo $end->format('d M')."<br>".$end->format('H:i');?></td>
                      <td rowspan="2" width="15%"><?php echo $event->location;?></td>
                      <td rowspan="2" width="22%"><?php echo $event->description;?></td>

                  </tr>
                  <tr>
                    <td colspan="2" align="center"><?php echo $sisa_waktu;?></td>
                  </tr>
          <?php
          // printf("%s (%s)\n", $event->getSummary(), $start);
          $i++;
        }
        ?>
          </table>
        </div>
          <!--Table-->
        <?php
      }

    } else {

      $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/jadwalKabanWai/oauth2callback.php';
      // $token = $client->getAccessToken();
      // $authObj = json_decode($token);
      // if(isset($authObj->refresh_token)){
      //   save_refresh_token($authObj->refresh_token);
      // }
      header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
  ?>
    </div>

  <div class="footer">
    <img style="width: 30%;" src="src/img/LOGO.png"/>
  </div>
  <script>
      console.log($('.table-bordered').height());
      console.log(screen.height);
      console.log($('.table-bordered').height() / screen.height);
      if(($('.table-bordered').height() / screen.height)*100 >= 75 ){
        console.log('marquee');
        document.getElementById('table-bordered').setAttribute('class','table table-bordered marquee');
      }else{
        console.log('no marquee');
      }

      var n = $('#table-bordered').height();
      n = (n/17)*-1;
      n = n+'%';
      console.log(n);
      var style = document.createElement('style');
      style.type = 'text/css';
      var keyFrames = '\
      @keyframes marquee {\
          from{\
          margin-top: 10%;\
        }to{\
          margin-top:'+n+';\
        }\
      }';
      style.innerHTML = keyFrames.replace(/A_DYNAMIC_VALUE/g, "180deg");
      document.getElementsByTagName('table')[0].appendChild(style);
    </script>
</body>
</html>