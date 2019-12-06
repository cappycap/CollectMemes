<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['spinsLeft']) and isset($_POST['nextSpin']) and isset($_POST['collectStatus'])) {

  if ((int)$_POST['spinsLeft'] == 0 or (int)$_POST['collectStatus'] == 0) {

    $response['html'] = "<html><body style='background:#111111;margin:0;height:100%;'><p id='demo' style='margin-top:10px;color:#ffffff;font-size:20px;text-align:center;width:100%;'></p><body></html>

    <script>
    // Set the date we're counting down to
    var countDownDate = " . $_POST['nextSpin'] . ";

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime() / 1000;

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var minutes = Math.floor((distance % (60 * 60)) / (60));
      var seconds = Math.floor((distance % (60)));

      // Output the result in an element with id='demo'
      document.getElementById('demo').innerHTML = minutes + 'm ' + seconds + 's ';

      // If the count down is over, write some text
      if (distance < 0) {
        clearInterval(x);
        document.getElementById('demo').innerHTML = 'EXPIRED';
      }
    }, 1000);
    </script>";

  } else {

    $response['html'] = "<html><body style='text-align:center;background:#111111;margin-top:15px;height:100%;'><span style='color:#0CE84A;font-size:20px;text-align:center;width:100%;'>" . $_POST['spinsLeft'] . " spins left</span></body></html>";

  }

}

echo json_encode($response);

$con->close();
?>
