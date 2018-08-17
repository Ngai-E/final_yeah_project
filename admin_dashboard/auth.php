<!DOCTYPE html>
<html lang="en">
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


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <?php 
      require ('config.php'); //contains the database connection

      

      /*******************************************************
        outputing fault values in the past week begins here
      *******************************************************/
        $s = date("l",strtotime("today"));   //takes the date of today and get the day in a string.
        $d=strtotime("last $s");  //converts the human readable string to date format e.g if today if friday, it will convert
        $s1 = date("Y-m-d H:i:s", $d); //'last friday' to date in the format specified 'Y-m-d H:i:s'
        $warning_amount = $emergency_amount=0;

        //read the logs from last week
        $sql = "SELECT * FROM `logs` WHERE `parameter_name` = 'auth' && `time` > '$s1' ORDER BY `time` ASC" ; //the query
        $number = 1;
        $result = mysqli_query($conn, $sql);//execute query
        $append = "";
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {

               $append .=  ' <tr>
                                  <td>'.$number++. '</td>
                                  <td>'.$row["time"].'</td>';
                if( $row["value"] == 1  ){
                  $append .= '<td>ON</td></tr> ';
                  $warning_amount++;
                }
                
                elseif ($row["value"] == 0  ) {
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
         $sql = "SELECT * FROM `logs` WHERE `time` > '$s1' && `parameter_name` = 'auth' "; //query for ploting graph
         $result = mysqli_query($conn, $sql); //execute query
         if (mysqli_num_rows($result) > 0) {
            echo "<script> var arraygraph = [];</script>";   //used to plot graph
            echo "<script> var labelgraph = [];</script>";   //used to plot graph
            // store the values of smoke in an array
            while($row = mysqli_fetch_assoc($result)) {
              echo "<script> arraygraph.push(".$row['value'].");</script>"; 
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
               
              <br/>

               <!-- this is the part to show  recent faults over the past week -->
              <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;Operations in the past week</h4>
                            <div class="showback">
                              <b>SUCCESS</b><span class="badge bg-success" style="background-color: #7a9a51"><?php echo $warning_amount; ?></span>&#32;
                             <b>&#32;&#32;FAILURE</b><span class="badge bg-info"><?php echo $emergency_amount; ?></span>
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

                        <br/>
                        <!-- show the information graphically -->
                          <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;Graphical representation of Genset states in the past week</h4>
                              <div class="panel-body text-center">
                                  <canvas id="auth_graph" height="300" width="400"></canvas>
                              </div>
                          </div>
                          
                        <!-- end graphical demonstration -->

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
    
    new Chart(document.getElementById("auth_graph").getContext("2d")).Bar(barChartData);

}();

  </script>



  </body>
</html>
