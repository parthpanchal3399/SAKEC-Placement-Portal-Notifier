<?php
  $curr_id = 0;   //ID from site
  $prev_id = 0;   //ID from db
  $servername = "localhost";
  $username = ""; //Your DB Username
  $password = ""; //Your DB Password
  $dbname = "placement_notifier";
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT curr_id from IDS";
  $sqlresult = mysqli_query($conn, $sql);

  if (mysqli_num_rows($sqlresult) > 0) {
      $prev_id = mysqli_fetch_assoc($sqlresult)['curr_id'];
  } else {
    echo "0 results";
  }



  $to = 'emailto1@gmail.com, emailto2@gmail.com'; //Recipient Email Addresses separated by comma
  $subject = 'New Message Posted';
  $msg = 'Check Placement Portal';
  $headers = "From: PP Placement Notification <ppp@test.com>\r\n";

  $data = array(
      "reg_id" => "", //SAKEC Placement Portal Reg.No
      "password" => "", //SAKEC Placement Portal Password
      "login" => "Sign In"
  );

  $ch = curl_init('http://shahandanchor.com/placement/');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);

  $result = substr($result, strpos($result, 'id=')+5);
  $result = substr($result, strpos($result, 'id='));
  $curr_id = (int)substr($result, strpos($result, 'id=')+4, 4);

  //Checking for update
  if ($curr_id > $prev_id) {
      $prev_id = $curr_id;
      $sql = "UPDATE IDS SET curr_id=".$prev_id;
      if (mysqli_query($conn, $sql)) {
          echo "Record updated successfully";
      } else {
          echo "Error updating record: " . mysqli_error($conn);
      }
      mail($to, $subject, $msg, $headers);
      mysqli_close($conn);
  }
?>
