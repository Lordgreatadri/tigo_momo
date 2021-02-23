<?php 
require_once 'config.php';

session_start();


?>



<!DOCTYPE html>
<html lang="en">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <title>PCABINET <?php echo date('Y'); ?> | MobileContent.com.gh </title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="Lordgreat-Adri Emmanuel">
    <!-- Favicon icon -->
    <link rel="icon" href="../../files/assets/images/auth/favicon.ico" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="../../files/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="../../files/assets/icon/themify-icons/themify-icons.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="../../files/assets/icon/icofont/css/icofont.css">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="../../files/assets/icon/feather/css/feather.css">
    <!-- light gallery css -->
    <!--<link rel="stylesheet" type="text/css" href="../../files/bower_components/lightgallery/css/lightgallery.min.css">-->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../../files/bower_components/select2/css/select2.min.css" />
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="../../files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../files/assets/css/jquery.mCustomScrollbar.css">



    <style>
        button:hover
        {

            background-color:  #ffb533 ;
            color: white;
            border: 1px solid gray;
            border-top-left-radius: 15px;
            border-bottom-right-radius: 15px;
            box-shadow: 0 6px #666;
            transform: translateY(4px);
            background-color:  #ffb533 ;
        }

        button:active 
        {
          background-color: #2D9037;
          box-shadow: 0 5px #666;
          transform: translateY(4px);
        }
    </style>
</head>

<body>
<!-- Pre-loader start -->
<div class="theme-loader">
    <div class="ball-scale">
        <div class='contain'>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
            <div class="ring">
                <div class="frame"></div>
            </div>
        </div>
    </div>
</div>
<!-- Pre-loader end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">

                <div class="navbar-logo">
                    <a class="mobile-menu" id="mobile-collapse" href="#!">
                        <i class="feather icon-menu"></i>
                    </a>
                    <a href="http://mobilecontent.com.gh" target="_blank">
                        <!-- <img  style="margin-top: 35px; margin-bottom: 25px; border-radius: 100%; width: 80px; height: 80px; background-color: white; border-radius: 50px;" class="img-fluid" src="../files/assets/images/logo.png" alt="Theme-Logo" /> -->
                        <!-- ../files/assets/images/auth/logo.png -->

                        
                        <!-- <img style="margin-top: 6px; margin-bottom: 10px; border-radius: 100%; width: 50px; height: 50px; " class="img-fluid" src="gowaloggo.png" alt="GOWA" /> -->
                    </a>
                    <a class="mobile-options">
                        <i class="feather icon-more-horizontal"></i>
                    </a>
                </div>

                <div class="navbar-container container-fluid">
                    <ul class="nav-left">
                        <li class="header-search">
                            <div class="main-search morphsearch-search">
                                <div class="input-group">
                                    <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                                    <input type="text" class="form-control">
                                    <span class="input-group-addon search-btn"><i class="feather icon-search"></i></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#!" onclick="javascript:toggleFullScreen()">
                                <i class="feather icon-maximize full-screen"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav-right">
                        <li class="header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather icon-bell"></i>
                                    <!-- <span class="badge bg-c-pink">5</span> -->
                                </div>
                                <ul class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                    <li>
                                        <h6>Notifications</h6>
                                        <!-- <label class="label label-danger">New</label> -->
                                        
                                    </li>
                                     
                                </ul>
                            </div>
                        </li>
                        <li class="header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="displayChatbox dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather icon-message-square"></i>
                                    <!-- <span class="badge bg-c-green">3</span> -->
                                </div>
                            </div>
                        </li>
                        <li class="user-profile header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                   <!--  <img src="../files/assets/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                                    <span>Current User</span>
                                    <i class="feather icon-chevron-down"></i> -->
                                </div>
                                <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">

                                    <!-- <li>
                                        <a href="auth-normal-sign-in.html">
                                            <i class="feather icon-log-out"></i> Logout
                                        </a>
                                    </li> -->
                                </ul>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar chat start -->
        <!-- <div id="sidebar" class="users p-chat-user showChat">
            <div class="had-container">
                <div class="card card_main p-fixed users-main">
                     
                </div>
            </div>
        </div> -->
         
        
        <!-- Sidebar inner chat end-->
        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                <nav class="pcoded-navbar">
                    <div class="pcoded-inner-navbar main-menu">
                        <div class="pcoded-navigatio-lavel">View Performance</div>
                        <ul class="pcoded-item pcoded-left-item">
                            <li class="pcoded-hasmenu">
                                <a href=""><!-- contestants.php -->
                                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                    <span class="pcoded-mtext">Contestant</span>
                                </a>
                                 
                             
                        </ul>
                         
                         
                    </div>
                </nav>




                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
                        <!-- Main-body start -->
                        <div class="main-body">
                            <div class="page-wrapper">
                                <!-- Page-header start -->
                                <div class="page-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <div class="d-inline">
                                                    <h4 style="color: #404e67;">PRESIDENTIAL CABINET  <?php echo date('Y'); ?> </h4>
                                                    <br>
                                                   <!--  <h6 style="color:  #d8553a;">Please press <b>vote</b> button or click on the picture to cast vote </h6> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php 
                                    if(isset($_SESSION['notice_message']) && $_SESSION['notice_message'] != "") 
                                    {
                                       echo "<div  class='alert alert-info alert-dismissible ' role='alert' style='color: green'><button type='button' class='close' data-dismiss='alert' aria-label ='close'><span aria-hiddden='true'>x</span></button><b>".$_SESSION['notice_message']."</b></div>";
                                        unset($_SESSION['notice_message']);
                                    }else
                                    {
                                        unset($_SESSION['notice_message']);
                                    }
                                ?>
                                <hr>
                                <!-- Page-header end -->

                                    <!-- Page body start -->
                                    <div class="page-body masonry-page">

                                        <!-- Gallery with description card start -->
                                        <h5 class="m-b-20" style="font-family: Serif; font-weight: 8px; color: #404e67;">CHOOSE A SUB CATEGORY YOU WISH TO VOTE FOR</h5>
                                        <div class="default-grid row">
                                            <div class="row lightboxgallery-popup">
                                                <?php
                                                    $connect->setAttribute(PDO::ATTR_AUTOCOMMIT,FALSE);
                                                    $query = "SELECT DISTINCT(cabinet) AS cabinet FROM contestants  ORDER BY cabinet ASC";

                                                    $statement = $connect->prepare($query);
                                                    $statement->execute();
                                                    $results = $statement->fetchAll();

                                                    foreach ($results as $value) {    
                                                ?>

                                                    <div class="col-md-3 default-grid-item">
                                                    <div class="card gallery-desc" style="border: 2px solid #f85110; border-top-left-radius: 35px; border-bottom-right-radius: 35px; border-left: 12px solid #f85110; " >
                                                        
                                                        <div class="card-block">
                                                            <h6 class="job-card-desc">
                                                            
                                                                <p class="text-muted1 text-info1 job-meta-data" style="color: #f85110;">
                                                                    <b><?php echo ucfirst($value['cabinet']);?> CATEGORY </b>  
                                                                </p> 
                                                            </h6>
                                                            
                                                    
                                                            <div class="job-meta-data">
                                                                <a href="category/?q=<?php echo $value['cabinet']; ?>">                           
                                                                    <button style="background-color: #f85110 ;" id="btnsubmit" class='btn btn-sm btn-primary1 btn-block voter' data-contestant-id ='<?php //echo $value["contestant_num"];?>'  data-contestant-name ='<?php //echo $value["name"];?>' data-voteidentifier ='<?php// echo $value["category"];?>' >
                                                                        <b><span style="color: #404e67;">Select Category</span> </b>
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <?php   //endforeach;
                                                    //mysqli_close($database);
                                                    $connect = null;
                                                    }
                                                ?>
                                                 
                                                 
                                                 
                                                 
                                                 
                                                 
                                                 
                                            </div>
                                        </div>



                                    </div>
                                    <!-- Page body end -->
                                </div>
                            </div>
                            <!-- Main-body end -->
                           <!--  <div id="styleSelector">

                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="../files/assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="../files/assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="../files/assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="../files/assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="../files/assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
    <!-- Warning Section Ends -->
    <!-- Required Jquery -->
    <script type="text/javascript" src="../../files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="../../files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="../../files/bower_components/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="../../files/bower_components/modernizr/js/css-scrollbars.js"></script>
    <!-- isotope js -->
    <script src="../../files/assets/pages/isotope/jquery.isotope.js"></script>
    <script src="../../files/assets/pages/isotope/isotope.pkgd.min.js"></script>

    <script type="text/javascript" src="../../files/bower_components/i18next/js/i18next.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="../../files/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
    <!-- Custom js -->

    <script src="../../files/assets/js/pcoded.min.js"></script>
    <script src="../../files/assets/js/vartical-layout.min.js"></script>
    <script src="../../files/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">   
        $(document).ready(function() {
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            })
            $(".code").on("contextmenu", function(e) {
                return false;
            })
        })
    </script>

    <script type="text/javascript">
        $(window).on('load', function() {
            var $container = $('.filter-container');
            $container.isotope({
                filter: '*',
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            var $grid = $('.default-grid').isotope({
                itemSelector: '.default-grid-item',
                masonry: {}
            });
        });
    </script>
    <script type="text/javascript" src="../../files/assets/js/script.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<script type="text/javascript">
    // $(document).ready(function(){
    //     $('.voter').on('click',function(){
    //             $("#contestant_id").val($(this).data('contestant-id'));
    //             $("#contestant_name").val($(this).data('contestant-name'));
    //             $("#voteidentifier").val($(this).data('voteidentifier'));

    //             $("#testModal").modal("toggle");
    //             $("#testModal").modal("show");
    //     });

    //    document.getElementById('voda_token_div').style.display="none";
    //    document.getElementById('mobile_number').style.display = 'none';
        
    // });
    

</script>


<script >
    // function checkIfVisacard()
    // {
    //     if(document.getElementById('visa_card').checked) 
    //     {
    //        document.querySelector('#mobile_number').style.display = 'block';
    //        document.querySelector('#mobile_money_number').style.display = 'none';
    //     }else{
    //        document.querySelector('#mobile_number').style.display = 'none';
    //        document.querySelector('#mobile_money_number').style.display = 'block';
    //     }    
    // }
    // function validateEntryForm()
    // {
    //     var vote_count = document.forms['payment']['vote_count'];
    //     var network = document.forms['payment']['network'];
    //     var phone_number = document.forms['payment']['phone_number'];
    //     // var visa_card = document.forms['payment']['visa_card'];
    //     var token = document.forms['payment']['token'];


    //     if(vote_count.value.trim() =="")
    //     {
    //         alert("please select bulk vote");
    //         return false;
    //     }

    //     if(network.value.trim() =="")
    //     {
    //        alert("please select your payment option");
    //        return false;
    //     }

    //     if(network.value == 'visa_card') 
    //     {
    //         document.getElementById('mobile_number').style.display = 'block';
    //     }else
    //     {
    //         document.getElementById('mobile_number').style.display = 'none';
    //         if(phone_number.value.trim() == "")
    //         {
    //             alert("please enter mobile money number!");
    //             return false;
    //         }
    //     }


    //     if(phone_number.value.trim() != "") 
    //     {
    //         if(phone_number.value.trim().length <= 14)
    //         {
    //           return  validatePhoneNumber(phone_number);
    //         }
    //     }
    // }


    // function validatePhoneNumber(contactValue)
    // {
    //     var phonenoFormat = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
    //     if(contactValue.value.match(phonenoFormat))
    //     {
    //        return true;
    //     }else
    //     {
    //         alert("Contact value not valid, enter valid numbers only");
    //         return false;
    //     }
    // }


    // function checkIfVodafone()
    // {
    //     if(document.getElementById('rad_voda_token').checked) 
    //     {
    //        document.querySelector('#voda_token_div').style.display = 'block';
    //     }else{
    //        document.querySelector('#voda_token_div').style.display = 'none';
    //     }    
    // }


    // function checkIfVisacard()
    // {
    //     if(document.getElementById('visa_card').checked) 
    //     {
    //        document.querySelector('#phone_numberlabel').val() = 'Mobile Number';
    //     }else{
    //        document.querySelector('#phone_numberlabel').val() = 'Mobile Money Number';
    //     }    
    // }


    // $(document).ready(function(){
    //     $(".payment").submit(function(event) {
    //         event.preventDefault();
    //         var amount = $(".vote_count").val();
    //         var channel = $(".network").val();
    //         var api_key = $(".api_key").val();
    //         var nominee_name = $(".contestant_name").val();
    //         var contestant_id = $(".contestant_id").val();
    //         var category = $(".voteidentifier").val();
    //         var number = $(".phone_number").val();

    //         //restrict voting if amount is not selected
    //         if (amount == "") {
    //             alert('Please select vote plan to continue.');
    //             return false;
    //         }//../../miss_ghana_api/
    //         if (document.getElementById('visa_card').checked){          
    //             $.ajax({
    //                 url: "payment-api/card_pay/process_order.php",
    //                 type: "POST",
    //                 // contentType: "application/json; charset=utf-8",
    //                 dataType: "json",
    //                 data: {
    //                     api_key:"r@gnalrok", 
    //                     price: amount, 
    //                     contestant_name: nominee_name,
    //                     voter: number,
    //                     contestant_id: contestant_id,
    //                     category:category,
    //                     device: "web"
    //                 },
    //                 beforeSend: function() {
    //                     $('.payment').trigger('reset');
    //                     $("#testModal").modal('hide');
    //                 },
    //                 success:function(response){
    //                    console.log(response['action_url']);
 
    //                   window.location.href = response['action_url'];
    //                 },
    //                 error:function(response){
    //                    console.log(response['action_url']);
    //                     alert(response['action_url']);
    //                   window.location.href = response['action_url'];
    //                 }
    //             });
    //         }else
    //         {
    //             $.ajax({
    //                 url: "payment-api/momo/execute_pay.php",
    //                 type: "POST",
    //                 data: {
    //                     nominee_name:nominee_name,
    //                     amount:amount,
    //                     api_key:"r@gnalrok",
    //                     number:number,
    //                     channel:channel,
    //                     nominee_code:contestant_id,
    //                     category: category,
    //                     device:'web'
    //                 },
    //                 beforeSend: function() 
    //                 {
    //                     $('.payment').trigger('reset');
    //                     $("#testModal").modal('hide');
    //                 },
    //                 success:function(response){
    //                     alert("Vote is being processed. Check and confirm payment!");
    //                     $('.payment').trigger('reset');
    //                     window.location.reload();
    //                 }
    //             });
    //         }
    //     });
    // });

</script>
</body>



</html>
