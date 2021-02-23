<?php 
    require_once 'config.php';
    $name = "";
    $id   ="";
    $profile = "";
    $cabinet = "";

    if(isset($_REQUEST['me'])) 
    {
        // $name = $_GET['me'];
// var_export($connect);
        $id   = $_GET['me'];//q
        // var_dump($id);
        if (!empty($id)) 
        {
           $data = mysqli_query($database, "SELECT * FROM contestants WHERE contestant_num ='$id' LIMIT 1");
           // var_dump($data);
           while ($row =  mysqli_fetch_assoc($data)) 
           {
            // var_dump($row);
               $name    =    $row['name'];
               $profile = $row['thumbnail'];
               $cabinet = $row['cabinet'];
           }
        }
    }
    else
    {
        // $id   = $_GET['me'];
        // $data = mysqli_query($database, "SELECT * FROM contestants WHERE contestant_id '$id' LIMIT 1");
        //    while ($row =  mysqli_fetch_assoc($data)) 
        //    {
        //        $name = $row['name'];
        //        var_dump($row);
        //    }
    }
    
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pcabinet</title>
	<!-- Favicon icon -->
    <link rel="icon" href="../../../files/assets/images/auth/favicon.ico" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="../../../files/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="../../../files/assets/icon/themify-icons/themify-icons.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="../../../files/assets/icon/icofont/css/icofont.css">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="../../../files/assets/icon/feather/css/feather.css">
    <!-- light gallery css -->
    <!--<link rel="stylesheet" type="text/css" href="../files/bower_components/lightgallery/css/lightgallery.min.css">-->
    <!-- Select 2 css -->
    <link rel="stylesheet" href="../files/bower_components/select2/css/select2.min.css" />
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="../../../files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../files/assets/css/jquery.mCustomScrollbar.css">
    <style type="text/css">
    /* CSS used here will be applied after bootstrap.css */

    body { 
    /* background: url('404.jpg') no-repeat center center fixed; 'http://www.bootdey.com/img/Content/bg_element.jpg'*/
     -webkit-background-size: cover;
     -moz-background-size: cover;
     -o-background-size: cover;
     background-size: cover;
    }

    .panel-default {
     opacity: 0.9;
     margin-top:30px;
    }
    .form-group.last {
     margin-bottom:0px;
    }
    .mymodal {
        /*background: rgba(255, 255, 255, 1);*/
        display: flex;
        flex-direction: row;
        justify-content: center;
        justify-items: center;
        align-content: center;
        /*display: none;*/
    }


    /*for the press me button's annimation....*/
    #messagepage {
        border: 1px solid black;
        width: 300px;
        height: 50px;
        animation: mymove 5s infinite;
    }
    @keyframes mymove {
        50% {
            font-weight: bold;
            color: gray; 
            background-color: red;
            border: 5px solid green;
        }
    }
</style>
</head>
<body style="background: url('banner.png') repeat center center fixed;">

	<script>
        function pressme(){
            $("#testModal").modal('show');//url('404.jpg') no-repeat center center fixed;
        }
    </script>
    <div class="container">
        <div class="row">
            
            <div class="mymodal col-md-8">
                <button style="margin: 150px;" class="btn btn-success press" id="messagepage" onclick="pressme();">Press Me</button>
            </div>

                    <div class="modal col-sm-12 col-md-10 clo=lg-10" data-keyboard="false"  data-backdrop="static" id="testModal" tabindex="-1" data-wow-duration='0.5s' data-wow-delay='0.3s'>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <img src="banner.png" style="margin-top: 5px; margin-bottom: 5px; border-radius: 100%; width: 50px; height: 50px;" class="img-fluid" >

                                    <h4 class="modal-title pull-right">Payment Plan for <b><?php echo $name; ?></b>

                                     <!-- <label id="contestant_id" name="contestant_id"></label> --></h4>
                                    <button class="close pull-left" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="">
                                    <div class="col-md-6">
                                        <img src="<?php echo $profile; ?>" alt="photo of contestant" class="constant-detail-img contestant-img">
                                    </div>
                                </div>
                                <!-- action="process_payment.php"  method="post"<hr style="border: 1px solid silver; width: 100%"> 22.5  -->
                                 <form  name="payment" id="payment" class="payment"  onsubmit="return validateEntryForm();">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="amount">Bulk Vote</label>
                                            <select name="amount" class="form-control amount" id="amount">
                                                <option value="">select bulk votes</option>
                                                <option value="1.00">₵1 => 1 vote</option>
                                                <option value="5.00">₵5 => 5 votes</option>
                                                <option value="10.00">₵10 => 10 votes</option>
                                                <option value="50.00">₵50 => 50 votes</option>
                                                <option value="100.00">₵100 => 100 votes</option>
                                                <option value="250.00">₵250 => 250 votes</option>
                                                <option value="500.00">₵500 => 500 votes</option>
                                                <option value="800.00">₵800 => 800 votes</option>
                                                <option value="1000.00">₵1000 => 1000 votes</option>
                                            </select>
                                        </div>


                                        <div class="form-group inline">
                                            <label for="inputUserName">Choose payment option</label> <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="network" name="network" value="mtn-gh" id="mtn-gh"onClick="checkIfVisacard()" required><!--  onClick="checkIfVisacard()" --> 
                                                <img src="../../../files/assets/images/logo_mtn.png" style="margin-top: 0px; margin-bottom: 5px; border-radius: 100%; width: 35px; height: 35px;" class="img-fluid">
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="network"  name="network" value="tigo-gh" id="tigo-gh" onClick="checkIfVisacard()" required><!--  onClick="checkIfVodafone()" -->
                                                <img src="AirtelTigo.jpg" style="margin-top: 0px; margin-bottom: 5px; border-radius: 100%; width: 35px; height: 35px;" class="img-fluid">
                                            </label>
                                            <!-- <label class="radio-inline">
                                                <input type="radio" class="network"  name="network" value="airtel-gh" id="airtel-gh" onClick="checkIfVisacard()" required> 
                                                <img src="../../vendor_assets_files/assets/images/logo_airtel.png" style="margin-top: 0px; margin-bottom: 5px; border-radius: 100%; width: 35px; height: 35px;" class="img-fluid">
                                            </label> -->
                                            <label class="radio-inline">
                                                <input type="radio" class="network"  name="network" id="rad_voda_token" value="vodafone-gh-ussd" onClick="checkIfVisacard()" required>
                                                <img src="../../../files/assets/images/logo_voda.png" style="margin-top: 0px; margin-bottom: 5px; border-radius: 100%; width: 35px; height: 35px;" class="img-fluid">
                                             </label>

                                             <label class="radio-inline">
                                                <input type="hidden" class="network" name="network" id="visa_card" value="visa_card" onClick="return checkIfVisacard()">
                                                <!-- <img src="../files/assets/images/logo_visa.png" style="margin-top: 5px; margin-bottom: 5px; border-radius: 100%; width: 60px; height: 60px;" class="img-fluid"> -->
                                             </label>
                                            <br>
                                        </div>


                                        <div class="form-group">
                                             <label for="mobile_number" id="mobile_number">Mobile Number</label>
                                            <label for="phone_numb" id="mobile_money_number">Mobile Money Number</label>
                                            <input type="text" class="form-control number" name="number" id="phone_numb" placeholder="mobile number" required="">
                                             <input type="hidden" name="contestant_id" id="contestant_id" class="contestant_id" value="<?php echo $id; ?>">
                                             <input type="hidden" name="nominee_name" id="contestant_name" class="nominee_name" value="<?php echo $name; ?>">
                                             <input type="hidden" name="voteidentifier" id="voteidentifier"  value="<?php echo $cabinet; ?>">
                                             <input type="hidden" name="api_key" class="api_key" value="mcc@tigoc@sh">
                                        </div>

                                        <!-- <div class="form-group" id="voda_token_div">
                                            <label for="token">Vodafone Token</label>
                                            <input type="text" class="form-control" name="token" id="token" placeholder="voda token">
                                        </div> -->
                                        <!-- <input type="submit" class="btn btn-primary" name="sender"> -->
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-sm btn-primary" type="submit" name="send">Cast Vote</button>
                                        <button class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                
                                
                            </div>                          
                        </div>                       
                    </div>
            </div>
    </div>

    <script type="text/javascript" src="../../../files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="../../../files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="../../../files/bower_components/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/modernizr/js/css-scrollbars.js"></script>
    <!-- isotope js -->
    <script src="../../../files/assets/pages/isotope/jquery.isotope.js"></script>
    <script src="../../../files/assets/pages/isotope/isotope.pkgd.min.js"></script>

    <script type="text/javascript" src="../../../files/bower_components/i18next/js/i18next.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="../../../files/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
    <!-- Custom js -->

    <script src="../../../files/assets/js/pcoded.min.js"></script>
    <script src="../../../files/assets/js/vartical-layout.min.js"></script>
    <script src="../../../files/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>


    <script type="text/javascript" src="../../../files/assets/js/script.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>    
    
    <script>
        // new WOW().init();
        
    $(document).ready(function(){
        $("#testModal").modal('show');
    });



    $(document).ready(function(){
        $('.voter').on('click',function(){
                $("#contestant_id").val($(this).data('contestant-id'));
                $("#contestant_name").val($(this).data('contestant-name'));

                $("#testModal").modal("toggle");
                $("#testModal").modal("show");
        });

       //document.getElementById('voda_token_div').style.display="none";
       // document.getElementById('voda_token_div').style.display="none";
       document.getElementById('mobile_number').style.display = 'none';
        document.querySelector('#mobile_money_number').style.display = 'block';
    });


    function validateEntryForm()
    {
        var amount = document.forms['payment']['amount'];
        var channel = document.forms['payment']['network'];
        var number = document.forms['payment']['number'];
        // var visa_card = document.forms['payment']['visa_card'];
        // var token = document.forms['payment']['token'];


        if(amount.value.trim() =="")
        {
            alert("please select bulk vote");
            return false;
        }

        if(channel.value.trim() =="")
        {
           alert("please select your payment option");
           return false;
        }

        if(channel.value == 'visa_card') 
        {

        }else
        {
            if(number.value.trim() == "")
            {
                alert("please enter mobile money number!");
                return false;
            }
        }


        if(number.value.trim() != "") 
        {
            if(number.value.trim().length >= 10)
            {
              // return  validatePhoneNumber(phone_number);
            }
        }
    }


    function validatePhoneNumber(contactValue)
    {
        var phonenoFormat = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
        var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
        if(contactValue.value.match(phoneno))
        {
           return true;
        }else
        {
            alert("Contact value not valid, enter valid numbers only");
            return false;
        }
    }


    function checkIfVisacard()
    {
        if(document.getElementById('visa_card').checked) 
        {
           document.querySelector('#mobile_number').style.display = 'block';
           document.querySelector('#mobile_money_number').style.display = 'none';
        }else{
           document.querySelector('#mobile_number').style.display = 'none';
           document.querySelector('#mobile_money_number').style.display = 'block';
        }    
    }



</script>  

<script>
    $(document).ready(function(){
        
        $(".payment").submit(function(event){
            event.preventDefault();
            var amount = $(".amount").val();
            var channel = $(".network").val();
            var api_key = $(".api_key").val();
            var nominee_name = $(".nominee_name").val();
            var contestant_id = $(".contestant_id").val();
            var cabinet = $("#voteidentifier").val();
            var number = $(".number").val();

            //restrict voting if amount is not selected
            if (amount == "") {
                alert('Please select vote plan to continue.');
                return false;
            }

            if (channel == "") {
                alert('Please select your payment option');
                return false;
            }

            if(document.getElementById('visa_card').checked)
            {          
                $.ajax({
                    url: "payment-api/card_pay/process_order.php",
                    type: "POST",
                    // contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    data: {
                        api_key:api_key, 
                        price:amount, 
                        contestant_name:nominee_name,
                        contestant_id:contestant_id,
                        voter:number,
                        device:"online"
                    },
                    beforeSend: function() {
                        $('.payment').trigger('reset');
                        $("#testModal").modal('hide');
                    },
                    success:function(response){
                       // console.log(response);
                      window.location.href = response['action_url'];
                    }//JSON.stringify()
                });
            }else
            {
                $.ajax({
                    url:"http://178.79.172.242/adri/payments/tigo/process_initiatiate_request.php",
                    type: "POST",
                    data: {
                        contestant_name:nominee_name,
                        Amount:amount,
                        CustomerMsisdn:number,
                        channel:channel,
                        contestant_code:contestant_id,
                        cabinet: cabinet,
                        Product:'vote',
                        RefNo:api_key
                    },
                    beforeSend: function() 
                    {
                        $('.payment').trigger('reset');
                        $("#testModal").modal('hide');
                    },
                    success:function(response){
                        alert("Vote is being processed. Check to confirm payment on your phone.");
                        window.location.reload();              
                    },
                    error:function(response){
                       console.log(response.Data.Description);
                        alert(response.Data.Description);
                    }
                });
            }
        });
    });
</script>        
</body>
</html>