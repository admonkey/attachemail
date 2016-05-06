<?php

function mail_attachment($files, $path, $mailto, $mailcc, $from_mail, $from_name, $replyto, $subject, $message) {
  $uid = md5(uniqid(time()));

  $header = "From: $from_name <$from_mail>\r\n";
  $header .= "Cc: $mailcc\r\n";
  $header .= "Reply-To: $replyto\r\n";
  $header .= "MIME-Version: 1.0\r\n";
  $header .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n\r\n";
  $header .= "This is a multi-part message in MIME format.\r\n";
  $header .= "--$uid\r\n";
  $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
  $header .= 'X-Mailer: PHP/' . phpversion() ."\r\n\r\n";;
  $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
  $header .= "$message\r\n\r\n";

      foreach ($files as $filename) { 

          $file = "$path/$filename";
          $file_size = filesize($file);
          $handle = fopen($file, "r");
          $content = fread($handle, $file_size);
          fclose($handle);
          $content = chunk_split(base64_encode($content));

          $header .= "--$uid\r\n";
          $header .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n"; // use different content types here
          $header .= "Content-Transfer-Encoding: base64\r\n";
          $header .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
          $header .= "$content\r\n\r\n";
      }

  $header .= "--$uid--";
  return mail($mailto, $subject, "", $header);
}

$attach_files = array("attachment1.txt","attachment2.txt");
$email_to = "jpucket@uark.edu";
$email_cc = "jeffpuckett2@gmail.com";
$email_from = "administrator@waltahr.uark.edu";
$email_from_name = "Admin";

$email_subject = "Meaningful Content";
$email_message = "
  <html><body>
    <p>Here's some profound email message.</p>
  </body></html>
";

var_dump(mail_attachment($attach_files, __DIR__, $email_to, $email_cc, $email_from, $email_from_name, $email_from, $email_subject, $email_message));
