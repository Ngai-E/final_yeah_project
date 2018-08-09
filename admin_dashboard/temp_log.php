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
                  <div class="col-md-12">
                      <div class="content-panel">
                          <h4><i class="fa fa-angle-right"></i> Table of threshold values</h4><hr><table class="table table-striped table-advance table-hover">
                            
                            
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
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #27ef17"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature drop below is value is not accepted</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">warning</a></td>
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #7a9a51"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature increase above this value should signal operator and turn on fan</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">error</a></td>
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #41cac0"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature increase above this value should signal operator about possible damage</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">critical</a></td>
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #2A3542"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature increase above this value should signal operator about possible damage</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">alert</a></td>
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #FCB322"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature increase above this value should alert operator about possible damage</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              <tr>
                                  <td><a href="basic_table.html#">emergency</a></td>
                                  <td class="hidden-phone"></td>
                                  <th><span class="badge" style="background-color: #ff6c60"><b style="visibility: hidden;">5</b></span></th>
                                  <td><span class="hidden-phone">temperature increase above this value should signal operator about possible damage</span></td>
                                  <td>
                                      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                                      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                      <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                                  </td>
                              </tr>
                              
                              </tbody>
                          </table>
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              </div>
              <br/>

              <!-- this is the part to show  recent faults over the past week -->
              <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;recent faults in the past week</h4>
                            <div class="showback">
                              <span class="badge bg-success" style="background-color: #7a9a51">15</span>
                              <span class="badge bg-info">20</span>
                              <span class="badge bg-inverse">25</span>
                              <span class="badge bg-warning">30</span>
                              <span class="badge bg-important">35</span>
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
                              <tbody>
                              <tr>
                                  <td>1</td>
                                  <td>12/03/18;12:30pm</td>
                                  <td>warning</td>
                                  <td><span class="badge" style="background-color: #7a9a51"><b style="visibility: hidden;">5</b></span></td>
                              </tr>
                              <tr>
                                  <td>2</td>
                                  <td>12/03/18;12:30pm</td>
                                  <td>error</td>
                                  <td><span class="badge" style="background-color: #41cac0"><b style="visibility: hidden;">5</b></span></td>
                              </tr>
                              <tr>
                                  <td>3</td>
                                  <td>12/03/18;12:30pm</td>
                                  <td>critical</td>
                                  <td><span class="badge" style="background-color: #2A3542"><b style="visibility: hidden;">5</b></span></td>
                              </tr>
                              <tr>
                                  <td>4</td>
                                  <td>12/03/18;12:30pm</td>
                                  <td>alert</td>
                                  <td><span class="badge" style="background-color: #FCB322"><b style="visibility: hidden;">5</b></span></td>
                              </tr>
                              <tr>
                                  <td>5</td>
                                  <td>12/03/18;12:30pm</td>
                                  <td>emergency</td>
                                  <td><span class="badge" style="background-color: #ff6c60"><b style="visibility: hidden;">5</b></span></td>
                              </tr>
                              </tbody>
                          </table>
                        </div> <!-- end fault over the past week -->

                        <br/>
                        <!-- show the information graphically -->
                          <div class="content-panel">
                            <h4><i class="fa fa-angle-right"></i>&#32;Graphical representation of temperature fluctuation in the past week</h4>
                              <div class="panel-body text-center">
                                  <canvas id="temp_graph" height="300" width="400"></canvas>
                              </div>
                          </div>
                          
                        <!-- end graphical demonstration -->

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

      var Script = function () {
        var set = [20,48,40,19,96,27,100,50,200];
        var label = ["","","","","","","","",""];
        var lineChartData = {
            labels : label,
            datasets : [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    data : set
                }
            ]

        };
    
    new Chart(document.getElementById("temp_graph").getContext("2d")).Line(lineChartData);

}();

  </script>

  </body>
</html>
