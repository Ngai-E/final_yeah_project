<?php session_start();?>
<!DOCTYPE html>
<html lang="en" class="loading">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, smokelate, Theme, Responsive, Fluid, Retina">

    <title>DASHGUM - Bootstrap Admin smokelate</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />

        
    <!-- Custom styles for this smokelate -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <style type="text/css">
      .loading-overlay {
  position:;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0);
  transition: background-color .2s ease-out;
}

.loading-anim {
  position: relative;
  width: 200px;
  height: 200px;
  margin: auto;
  perspective: 800px;
  transform-style: preserve-3d;
  transform: translateZ(-100px) rotateY(-90deg) rotateX(90deg) rotateZ(90deg) scale(0.5);
  opacity: 0;
  transition: all .2s ease-out;
}
.loading-anim .circle {
  width: 100%;
  height: 100%;
  animation: spin 5s linear infinite;
}
.loading-anim .border {
  position: absolute;
  border-radius: 50%;
  border: 3px solid #e34981;
}
.loading-anim .out {
  top: 15%;
  left: 15%;
  width: 70%;
  height: 70%;
  border-left-color: transparent;
  border-right-color: transparent;
  animation: spin 2s linear reverse infinite;
}
.loading-anim .in {
  top: 18%;
  left: 18%;
  width: 64%;
  height: 64%;
  border-top-color: transparent;
  border-bottom-color: transparent;
  animation: spin 2s linear infinite;
}
.loading-anim .mid {
  top: 40%;
  left: 40%;
  width: 20%;
  height: 20%;
  border-left-color: transparent;
  border-right-color: transparent;
  animation: spin 1s linear infinite;
}

.loading .loading-anim {
  transform: translateZ(0) rotateY(0deg) rotateX(0deg) rotateZ(0deg) scale(1);
  opacity: 1;
}

.loading .loading-overlay {
  background: rgba(255, 255, 255, 0.5);
}

.dot {
  position: absolute;
  display: block;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #e34981;
  animation: jitter 5s ease-in-out infinite, fade-in-out 5s linear infinite;
}

.dot:nth-child(1) {
  top: 90px;
  left: 180px;
  animation-delay: 0s;
}

.dot:nth-child(2) {
  top: 135px;
  left: 168px;
  animation-delay: 0.41667s;
}

.dot:nth-child(3) {
  top: 168px;
  left: 135px;
  animation-delay: 0.83333s;
}

.dot:nth-child(4) {
  top: 180px;
  left: 90px;
  animation-delay: 1.25s;
}

.dot:nth-child(5) {
  top: 168px;
  left: 45px;
  animation-delay: 1.66667s;
}

.dot:nth-child(6) {
  top: 135px;
  left: 12px;
  animation-delay: 2.08333s;
}

.dot:nth-child(7) {
  top: 90px;
  left: 0px;
  animation-delay: 2.5s;
}

.dot:nth-child(8) {
  top: 45px;
  left: 12px;
  animation-delay: 2.91667s;
}

.dot:nth-child(9) {
  top: 12px;
  left: 45px;
  animation-delay: 3.33333s;
}

.dot:nth-child(10) {
  top: 0px;
  left: 90px;
  animation-delay: 3.75s;
}

.dot:nth-child(11) {
  top: 12px;
  left: 135px;
  animation-delay: 4.16667s;
}

.dot:nth-child(12) {
  top: 45px;
  left: 168px;
  animation-delay: 4.58333s;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes jitter {
  0% {
    transform: scale(1, 1);
  }
  25% {
    transform: scale(0.7, 0.7);
  }
  50% {
    transform: scale(1, 1);
  }
  75% {
    transform: scale(1.3, 1.3);
  }
  100% {
    transform: scale(1, 1);
  }
}
@keyframes fade-in-out {
  0% {
    opacity: 0.8;
  }
  25% {
    opacity: 0.2;
  }
  75% {
    opacity: 1;
  }
  100% {
    opacity: 0.8;
  }
}

    </style>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <?php 
      require ('config.php'); //contains the database connection

      $_SESSION["send"] = "1";
      echo "Session variables are set.";

      if(isset($_GET['send'])){ 
        //echo " got it";
        $_SESSION["send"] = "1";
        header("location: temp_log.php");
      }

      /*******************************************************
        outputing fault values in the past week begins here
      *******************************************************/
        $s = date("l",strtotime("today"));   //takes the date of today and get the day in a string.
        $d=strtotime("last $s");  //converts the human readable string to date format e.g if today if friday, it will convert
        $s1 = date("Y-m-d H:i:s", $d); //'last friday' to date in the format specified 'Y-m-d H:i:s'
        $warning_amount = $emergency_amount=0;


         $sql = "SELECT `generator`, `time` FROM `logs1` WHERE `time` > '$s1' ORDER BY `time` ASC" ; //the query
        $number = 1;
        $result = mysqli_query($conn, $sql);//execute query
        $append = "";
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

               $append .=  ' <tr>
                                  <td>'.$number++. '</td>
                                  <td>'.$row["time"].'</td>';
                if( $row["generator"] == 1  ){
                  $append .= '<td>ON</td></tr> ';
                  $warning_amount++;
                }
                
                elseif ($row["generator"] == 0  ) {
                  $append .= '<td>OFF</td></tr> ';
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
         $sql = "SELECT generator FROM `logs1` WHERE `time` > '$s1'"; //query for ploting graph
         $result = mysqli_query($conn, $sql); //execute query
         if (mysqli_num_rows($result) > 0) {
            echo "<script> var arraygraph = [];</script>";   //used to plot graph
            echo "<script> var labelgraph = [];</script>";   //used to plot graph
            // store the values of smoke in an array
            while($row = mysqli_fetch_assoc($result)) {
              echo "<script> arraygraph.push(".$row['generator'].");</script>"; 
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
      <!-- header start -->
      <?php include 'header.php'; ?>
      <!-- header end -->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      
      <?php include 'sidebar.php' ?>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
                  <div class="row mt">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i> Genset Current State</h4>
                            <div class=" text-center" >
                              <div  id="animate" ><br><br>
                                 <div class='loading-overlay'></div>
                                    <div class='loading-anim'>
                                      <div class='border out'></div>
                                      <div class='border in'></div>
                                      <div class='border mid'></div>
                                      <div class='circle'>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                        <span class='dot'></span>
                                      </div>
                                    </div>
                              </div>
                            </div>
                              </div> <br>
                     <div class="content-panel showback">
                            <h4><i class="fa fa-angle-right"></i>&#32;Operations in the past week</h4>
                            <div >
                              <b>ON</b><span class="badge bg-success" style="background-color: #7a9a51"><?php echo $warning_amount; ?></span>&#32;
                             <b>&#32;&#32;OFF</b><span class="badge bg-info"><?php echo $emergency_amount; ?></span>
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

                    </div> <!-- closing the col -->
                      
              
               <!-- this is the part to show  recent faults over the past week -->
              <div class="col-lg-6 col-md-6 col-sm-12">
                
               
                          <div class="content-panel">
                              <h4><i class="fa fa-angle-right"></i> Put ON/OFF Genset or Test</h4>
                              <div class="panel-body text-center showback" >
                                  <div class="col-sm-6 text-center" id="ON" style="width: 100%;">
                                  <input type="checkbox"  checked="" data-toggle="switch" />
                              </div>
                          </div>
                          <form action="" method="GET">
                            <input type="hidden" name="send" value="GET">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Get Current Value</button>
                          </form>
                      </div>
                  <br>
                    
                      <!-- show the information graphically -->
                          <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;Graphical representation of Genset states in the past week</h4>
                              <div class="panel-body text-center">
                                  <canvas id="gen_graph" height="300" width="400"></canvas>
                              </div>
                          </div>
                          
                        <!-- end graphical demonstration -->

                    </div> <!-- closing the col dive -->
              </div>


             
                        
          </section><!--/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <?php include 'footer.php' ?>
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
       <!--script for this page-->
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <!--custom checkbox & radio-->
    <!--custom switch-->
  <script src="assets/js/bootstrap-switch.js"></script>
  
  <!--custom tagsinput-->
  <script src="assets/js/jquery.tagsinput.js"></script>
  
  <script src="assets/js/form-component.js"></script>
    
  <script>
      //custom select box
      //the genset_Off is the icom to show if user clicks off and vise versa
      var genset_OFF = "<img class=\"img-circle\" src=\"assets/img/ui-danro.jpg\" width=\"40%\" height=\"\" align=\"\">";
      var genset_ON = "<div class=\"panel-body text-center\" id=\"animate\" <div class='loading-overlay'></div> <div class='loading-anim'><div class='border out'></div><div class='border in'></div><div class='border mid'></div><div class='circle'><span class='dot'></span> <span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot'></span> <span class='dot'></span><span class='dot'></span></div></div></div>";

  document.getElementById('ON').addEventListener('click', function(evt) { 
  document.getElementById('animate').innerHTML= document.getElementById('animate').innerHTML === genset_OFF ? genset_ON : genset_OFF;
  document.querySelector('html').classList.toggle('loading');
}, false);

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
    
    new Chart(document.getElementById("gen_graph").getContext("2d")).Bar(barChartData);

}();

  </script>



  </body>
</html>
