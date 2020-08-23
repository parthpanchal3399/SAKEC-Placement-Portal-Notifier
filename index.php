<?php
  include "simple_html_dom.php";
  use PHPMailer\PHPMailer\PHPMailer;
  require_once "PHPMailer/PHPMailer.php";
  require_once "PHPMailer/SMTP.php";
  require_once "PHPMailer/Exception.php";
  $curr_id = 0;   //ID from site
  $prev_id = 0;   //ID from db
  $servername = "localhost";
  $username = ""; //Your DB Username
  $password = ""; //Your DB Password  
  $dbname = ""; //Your DB Name
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

  //MAIL SETTINGS
  $recipients = array(
      'recipient1@email.com',
      'recipient2@email.com',
      'recipient3@email.com'
   ); //List of recipients goes here

  $subject = 'New Message Posted';


  //LOGIC
  $data = array(
      "reg_id" => "", //Your SAKEC Placement Portal Reg no
      "password" => "", //Your SAKEC Placement Portal Password
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


  if ($curr_id > $prev_id) {
      $msg = "Hello Friend, <br> There's a new message posted on the Placement Portal : <br><br>";
      $ch = curl_init('http://shahandanchor.com/placement/viewmessage.php?id='.$curr_id);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
      $html = new simple_html_dom();
      $html->load($result);
      $msg .= $html->find('p', 0)->innertext;
      $msg .= "<br><br>For more details, <a href='http://shahandanchor.com/placement/welcome.php'>Click Here</a><br>Thanks! :)<br>";
      $prev_id = $curr_id;
      $sql = "UPDATE IDS SET curr_id=".$prev_id;
      if (mysqli_query($conn, $sql)) {
          echo "Record updated successfully";
      } else {
          echo "Error updating record: " . mysqli_error($conn);
      }
      mysqli_close($conn);
      //SEND MAIL
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = "smtp.gmail.com";
      //$mail->SMTPDebug  = 1;  
      $mail->SMTPAuth = true;
      $mail->Username = "your-email@email.com";  //'FROM' email address (Email will go from this address)
      $mail->Password = ""; //Password for 'your-email@email.com'
      $mail->Port = 587;
      $mail->SMTPSecure = "tls";
      $mail->isHTML(true);
      $mail->setFrom("ppp@test.com", "PP Placement Notification");  //can be anything you want
      foreach($recipients as $person)
      {
          $mail->AddAddress($person);
      }
      $mail->Subject = ($subject);
      $mail->Body = $msg;
      $mail->send();
  }

?>
