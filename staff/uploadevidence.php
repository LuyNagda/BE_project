<?php
session_start();
// error_reporting(0);
if(isset($_SESSION['staff']) && $_SESSION['staff']==true) {
  require_once "../functions/database_functions.php";
  $conn = db_connect();
  $user = $_SESSION['user'];
  $query = "SELECT * FROM cases WHERE clientname IN (SELECT name FROM clients WHERE assto='$user')";
  $result = mysqli_query($conn, $query);

  $evidencequery = "SELECT * FROM evidence";
  $evidenceresult = mysqli_query($conn, $evidencequery);
  $evidenceresult = mysqli_fetch_assoc($evidenceresult);

  //casenotif query
  $casenotifquery = "SELECT clientname, hearingdate FROM cases WHERE clientname IN (SELECT name FROM clients WHERE assto='$user') AND hearingdate >= CURDATE() AND status!='CLOSED' ORDER BY hearingdate LIMIT 1";
  $casenotifresult = mysqli_query($conn, $casenotifquery);
  $casenotifresult = mysqli_fetch_assoc($casenotifresult);

  //tasknotif query
  $tasknotifquery = "SELECT assto, deadline FROM tasks WHERE assto='$user' AND deadline >= CURDATE() AND status!='COMPLETED' ORDER BY deadline LIMIT 1";
  $tasknotifquery = mysqli_query($conn, $tasknotifquery);
  $tasknotifresult = mysqli_fetch_assoc($tasknotifquery);

  //appnotif query
  $appnotifquery = "SELECT cname, date, time FROM appointment WHERE cname IN (SELECT name FROM clients WHERE assto='$user') AND date >= CURDATE() AND status!='CLOSED' AND status!='CANCELLED' ORDER BY date LIMIT 1";
  $appnotifquery = mysqli_query($conn, $appnotifquery);
  $appnotifresult = mysqli_fetch_assoc($appnotifquery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Software for Advocates
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar bg-white" data-color="purple" data-background-color="white">
      <div class="logo bg-white"><a href="#" class="simple-text logo-normal">
          Software for Advocates
        </a></div>
      <div class="sidebar-wrapper bg-white">
        <ul class="nav">
          <li class="nav-item ">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./client.php">
              <i class="material-icons">person_add</i>
              <p>Clients</p>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./cases.php">
              <i class="material-icons">gavel</i>
              <p>Case</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./task.php">
              <i class="material-icons">add_task</i>
              <p>Task</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./appointment.php">
              <i class="material-icons">event</i>
              <p>Appointment</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand">Cases</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form" style="display: none">
              <div class="input-group no-border">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search...">
                <button type="button" class="btn btn-white btn-round btn-just-icon">
                <i class="material-icons">search</i>
                <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="./dashboard.php">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    dashboard
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">3</span>
                  <p class="d-lg-none d-md-block">
                    notifications
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <?php if($casenotifresult == NULL) { ?>
                  <a class="dropdown-item" href="./cases.php">No Upcoming Cases</a>
                  <?php } else { ?>
                  <a class="dropdown-item" href="./cases.php">Your next hearing date is: <?php echo $casenotifresult['hearingdate']; ?> for client: <?php echo $casenotifresult['clientname']; ?></a>
                  <?php } if($tasknotifresult == NULL) { ?>
                  <a class="dropdown-item" href="./task.php">No Upcoming Tasks</a>
                  <?php } else { ?>
                  <a class="dropdown-item" href="./task.php">Task assigned to: <?php echo $tasknotifresult['assto']; ?> is due on: <?php echo $tasknotifresult['deadline']; ?></a>
                  <?php } if($appnotifresult == NULL) {?>
                  <a class="dropdown-item" href="./appointment.php">No Upcoming Appointments</a>
                  <?php } else { ?>
                  <a class="dropdown-item" href="./appointment.php">You have an appointment with: <?php echo $appnotifresult['cname']; ?> on date: <?php echo $appnotifresult['date']; ?> and time: <?php echo $appnotifresult['time']; ?></a>
                  <?php } ?>  
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="./profile.php">Profile</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../logout.php">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Upload Evidence</h4>
                </div>
                <div class="card-body">
                  <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="row">
                      <div class="col-6 form-group">
                      <label for="in" class="text-primary">Select Client</label>
                        <select class="form-control" name="in">
                        <option></option>
                        <?php while($array = mysqli_fetch_assoc($result)): ?>
                        <option><?php echo $array['clientname']; ?></option>
                        <?php endwhile; ?>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                    <div class="form-group form-file-upload form-file-multiple pl-3">
                        <input type="file" name="files[]" id="files[]" class="inputFileHidden" multiple>
                        <div class="input-group">
                            <input type="text" class="form-control inputFileVisible" placeholder="Multiple Files" multiple>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-fab btn-round btn-primary">
                                    <i class="material-icons">attach_file</i>
                                </button>
                            </span>
                        </div>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a>
                  Software for Advocates
                </a>
              </li>
              <li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            Luy, Yash, Neha for a better web.
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!-- Chartist JS -->
  <script src="../assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <script>
    jQuery(document).ready(function() {
      jQuery().ready(function() {
        jQuerysidebar = jQuery('.sidebar');

        jQueryfull_page = jQuery('.full-page');

        jQuerysidebar_responsive = jQuery('body > .navbar-collapse');

        window_width = jQuery(window).width();

        fixed_plugin_open = jQuery('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if (jQuery('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            jQuery('.fixed-plugin .dropdown').addClass('open');
          }

        }

        jQuery('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if (jQuery(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        jQuery('.fixed-plugin .active-color span').click(function() {
          jQueryfull_page_background = jQuery('.full-page-background');

          jQuery(this).siblings().removeClass('active');
          jQuery(this).addClass('active');

          var new_color = jQuery(this).data('color');

          if (jQuerysidebar.length != 0) {
            jQuerysidebar.attr('data-color', new_color);
          }

          if (jQueryfull_page.length != 0) {
            jQueryfull_page.attr('filter-color', new_color);
          }

          if (jQuerysidebar_responsive.length != 0) {
            jQuerysidebar_responsive.attr('data-color', new_color);
          }
        });

        jQuery('.switch-sidebar-mini input').change(function() {
          jQuerybody = jQuery('body');

          jQueryinput = jQuery(this);

          if (md.misc.sidebar_mini_active == true) {
            jQuery('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            jQuery('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            jQuery('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              jQuery('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }
        });
      });
    });
  </script>
  <script>
    // FileInput
    jQuery('.form-file-simple .inputFileVisible').click(function() {
      jQuery(this).siblings('.inputFileHidden').trigger('click');
    });

    jQuery('.form-file-simple .inputFileHidden').change(function() {
      var filename = jQuery(this).val().replace(/C:\\fakepath\\/i, '');
      jQuery(this).siblings('.inputFileVisible').val(filename);
    });

    jQuery('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(function() {
      jQuery(this).parent().parent().find('.inputFileHidden').trigger('click');
      jQuery(this).parent().parent().addClass('is-focused');
    });

    jQuery('.form-file-multiple .inputFileHidden').change(function() {
      var names = '';
      for (var i = 0; i < jQuery(this).get(0).files.length; ++i) {
        if (i < jQuery(this).get(0).files.length - 1) {
          names += jQuery(this).get(0).files.item(i).name + ', ';
        } else {
          names += jQuery(this).get(0).files.item(i).name;
        }
      }
      jQuery(this).siblings('.input-group').find('.inputFileVisible').val(names);
    });

    jQuery('.form-file-multiple .btn').on('focus', function() {
      jQuery(this).parent().siblings().trigger('focus');
    });

    jQuery('.form-file-multiple .btn').on('focusout', function() {
      jQuery(this).parent().siblings().trigger('focusout');
    });

    jQuery(document).ready(function(){
      if (jQuery(window).width() < 768) {
        jQuery("a").css("white-space", "wrap");
        } else {
        jQuery("a").css("white-space", "nowrap");
      }
    });
  </script>
</body>

</html>
<?php
  if(isset($_POST['submit'])){
    $clientname = trim($_POST['in']);
    $query = "SELECT ID FROM cases WHERE clientname='$clientname'";
    $result = mysqli_query($conn, $query);
    $array = mysqli_fetch_assoc($result);
    $ID = $array['ID'];
    $finalarray = array();
    foreach($_FILES['files']['name'] as $key => $name){
        $newFilename = time() . "_" . $name;
        move_uploaded_file($_FILES['files']['tmp_name'][$key], '../admin/upload/' . $newFilename);
        $location = '../admin/upload/' . $newFilename;
        $temparray = $location;
        array_push($finalarray, $temparray);
    }
    if ($ID == $evidenceresult['cid']) {
      $ev1 = "SELECT files FROM evidence WHERE cid='$ID'";
      $evresult = mysqli_query($conn, $ev1);
      $evidence = mysqli_fetch_assoc($evresult);
      $evidence = $evidence['files'];
      $evidencearray = explode(',',$evidence);
      array_push($finalarray, $evidencearray);
      $jsondata = json_encode($finalarray);
      $finalresult = mysqli_query($conn, "UPDATE evidence SET files='$jsondata' WHERE cid='$ID'");
    }
    else {
      $jsondata = json_encode($finalarray);
      $finalresult = mysqli_query($conn,"INSERT INTO evidence (cid, files) VALUES ('$ID', '$jsondata')");
    }


    if(!$finalresult) {
      echo "<script>alert('Uploading Failed. Please retry again later!');
            window.location.href='./cases.php';
		  </script>";
    }
    else {
      echo "<script>alert('Files have been successfully uploaded!');
			window.location.href='./cases.php';
		  </script>";
  }
}
}
else {
  header("Location: ../index.php");
}
?>