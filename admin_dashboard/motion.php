<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DASHGUM - Bootstrap Admin Template</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>


    <?php 
      require ('config.php'); //contains the database connection

      //establishing a session for easy exchange of states
      $_SESSION["send"] = "1";
      echo "Session variables are set.";

      if(isset($_GET['send'])){ 
        //echo " got it";
        $_SESSION["send"] = "1";
        header("location: temp_log.php");
      }

      /********************************************************************
      this section selects threshold values from database and outputs
      ********************************************************************/
      $normal=$error=$warning=$alert=$emergency=$critical = "";//initialising the threshold values

      $sql = "SELECT * FROM parameter_threshold WHERE parameter_name = 'motion' ";//statement to be executed

      $result = mysqli_query($conn, $sql); //execute query
      
      if (mysqli_num_rows($result) == 1) {
          //select the threshold values from the returned string
         while($row = mysqli_fetch_assoc($result)) {
            $normal = $row['normal_value'];
            $warning = $row['warning_value'];
            $error = $row['error_value'];
            $critical = $row['critical_value'];
            $alert = $row['alert_value'];
            $emergency = $row['emergency'];
           
      }

      } else {
          echo "";
      }
      /**********************************************************
      outputing of threshold values ends here
      **********************************************************/

      /*******************************************************
        outputing fault values in the past week begins here
      *******************************************************/
        $s = date("l",strtotime("today"));   //takes the date of today and get the day in a string.
       // echo "last $s";
        $d=strtotime("last $s");  //converts the human readable string to date format e.g if today if friday, it will convert
        //echo "$d";
        $s1 = date("Y-m-d H:i:s", $d); //'last friday' to date in the format specified 'Y-m-d H:i:s'
       //echo "$s1";
        $warning_amount = $error_amount =$critical_amount=$emergency_amount=$alert_amount= 0;

        //read the logs from last week
        $sql = "SELECT `motion`, `time` FROM `logs1` WHERE `time` > '$s1' && `motion` >= '$warning' ORDER BY `time` ASC" ; //the query
        $number = 1;
        $result = mysqli_query($conn, $sql);//execute query
        $append = "";
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

               $append .=  ' <tr>
                                  <td>'.$number++. '</td>
                                  <td>'.$row["time"].'</td>';
                if( $row['motion'] >= $warning && $row['motion'] < $error  ){
                  $append .= '<td>warning</td>
                                  <td><span class="badge" style="background-color: #7a9a51"><b style="visibility: hidden;">5</b></span></td>
                              </tr> ';
                  $warning_amount++;
                }
                elseif ($row['motion'] >= $error && $row['motion'] < $critical  ) {
                  $append .= '<td>error</td>
                                  <td><span class="badge" style="background-color: #41cac0"><b style="visibility: hidden;">5</b></span></td>
                              </tr> ';
                  $error_amount++;
                }
                elseif ($row['motion'] >= $critical && $row['motion'] < $alert  ) {
                  $append .= '<td>critical</td>
                                  <td><span class="badge" style="background-color: #2A3542"><b style="visibility: hidden;">5</b></span></td>
                              </tr> ';
                  $critical_amount++;
                }
                elseif ($row['motion'] >= $alert && $row['motion'] < $emergency  ) {
                  $append .= '<td>alert</td>
                                  <td><span class="badge" style="background-color: #FCB322"><b style="visibility: hidden;">5</b></span></td>
                              </tr> ';
                  $alert_amount++;
                }
                elseif ($row['motion'] >= $emergency  ) {
                  $append .= '<td>emergency</td>
                                  <td><span class="badge" style="background-color: #ff6c60"><b style="visibility: hidden;">5</b></span></td>
                              </tr> ';
                  $emergency_amount++;
                }
            }
        } else {
            echo "";
        }


      /****************************************************
        outputing fault values in the past week ends here
      ****************************************************/

      /**************************************
        plotting the graph with values from db
      ****************************************/
         $sql = "SELECT motion FROM `logs1` WHERE `time` > '$s1'"; //query for ploting graph
         $result = mysqli_query($conn, $sql); //execute query
         if (mysqli_num_rows($result) > 0) {
            echo "<script> var arraygraph = [];</script>";   //used to plot graph
            echo "<script> var labelgraph = [];</script>";   //used to plot graph
            // store the values of motion in an array
            while($row = mysqli_fetch_assoc($result)) {
              echo "<script> arraygraph.push(".$row['motion'].");</script>"; 
              echo "<script> labelgraph.push(' ');</script>"; 

            }
          }

          else{
            echo "";
          }


      /*******************************
        end graph plot
      **********************************/

      mysqli_close($conn);

    ?>
    

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <?php include 'header.php'?>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <?php include 'sidebar.php'?>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
      		  <div class="row mt">
                  
                   

                        <div class="col-lg-6 col-md-6 col-sm-12">
                          <div class="content-panel" >
                          <h4><i class="fa fa-angle-right"></i> Table of threshold values</h4><hr>
                          <table class="table table-striped table-advance table-hover">
                            
                            
                              <thead>
                              <tr>
                                  <th><i class="fa fa-bullhorn"></i> Notification Type</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> Threshold</th>
                                  <th>colour code</th>
                                  <th><i class=" fa fa-edit"></i> description</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td><a href="basic_table.html#">normal</a></td>
                                  <td class="hidden-phone" id="normal_thresh" ><?php echo $normal;?></td>
                                  <th><span class="badge" style="background-color: #27ef17"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">values above these should indicate as normal</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">warning</a></td>
                                  <td class="hidden-phone" id="warning_thresh" > <?php echo $warning;?></td>
                                  <th><span class="badge" style="background-color: #7a9a51"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">Someone is around</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">error</a></td>
                                  <td class="hidden-phone" id="error_thresh" ><?php echo $error;?></td>
                                  <th><span class="badge" style="background-color: #41cac0"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">Person persisting</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">critical</a></td>
                                  <td class="hidden-phone" id="critical_thresh" ><?php echo $critical;?></td>
                                  <th><span class="badge" style="background-color: #2A3542"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">Attention someone on site</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">alert</a></td>
                                  <td class="hidden-phone" id="alert_thresh" ><?php echo $alert;?></td>
                                  <th><span class="badge" style="background-color: #FCB322"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">Asking for authentication</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">emergency</a></td>
                                  <td class="hidden-phone" id="emerg_thresh"><?php echo $emergency;?></td>
                                  <th><span class="badge" style="background-color: #ff6c60"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">Unauthenticated Entry detected!! Camera turned ON</span></td>
                                  <td>
                                                   
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      </td>               
                              </tr>
                              
                              </tbody>
                          </table>
                  </div>
                  <br>
                   <!-- this is the part to show  recent faults over the past week -->
                         <div class="content-panel showback">
                            <h4><i class="fa fa-angle-right"></i>&#32;recent faults in the past week</h4>
                            <div >
                              <span class="badge bg-success" style="background-color: #7a9a51"><?php echo $warning_amount; ?></span>
                              <span class="badge bg-info"><?php echo $error_amount; ?></span>
                              <span class="badge bg-inverse"><?php echo $critical_amount; ?></span>
                              <span class="badge bg-warning"><?php echo $alert_amount; ?></span>
                              <span class="badge bg-important"><?php echo $emergency_amount; ?></span>
                            </div>
                            <hr>
                          <table class="table">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Date</th>
                                  <th>Type</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody id="fault_table">
                                <?php echo $append; ?>
                              </tbody>
                          </table>
                        </div> <!-- end fault over the past week -->
                </div>
                  
                         
                        <div class="col-lg-6 col-md-6 col-sm-12">
                          <div class="content-panel showback" style="padding: 15px">
                              <h4><i class="fa fa-angle-right"></i> Current state</h4><hr>
                              <div class="panel-body text-center" style="width: 100%;height: 230px">
                                  will show a picture here
                              </div>
                              <form action="" method="GET">
                            <input type="hidden" name="send" value="GET">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Get Current Value</button>
                          </form>
                          </div>
                          <br>
                          <!-- show the information graphically -->
                          <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;Graphical representation of motion fluctuation in the past week</h4>
                              <div class="panel-body text-center">
                                  <canvas id="motion_graph" height="300" width="400"></canvas>
                              </div>
                          </div>
                          
                        <!-- end graphical demonstration -->


                        </div>
                   
                </div>  <!-- close row -->

                        
          </section><!--/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <?php include 'footer.php'?>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jjquery-1.8.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->
    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>
    <script src="assets/js/chart-master/Chart.js"></script>

  <script> 

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

      //function to plot the graph
      var Script = function () {
        var barChartData = {
            labels : labelgraph,    //array obtained after reading the database
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    data : arraygraph  //array obtained after reading the database
                }
            ]

        };
    
    new Chart(document.getElementById("motion_graph").getContext("2d")).Bar(barChartData);

}();

  </script>

  </body>
</html>
