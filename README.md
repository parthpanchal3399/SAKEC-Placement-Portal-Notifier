# SAKEC-Placement-Portal-Notifier
A very untidy script that montitors the SAKEC Placement Portal for new updates and notifies via email if any update.
Uses PHP [Simple HTML DOM Parser](https://simplehtmldom.sourceforge.io/) and [PHPMailer] (https://github.com/PHPMailer/PHPMailer)

# Steps to Deploy
1. Clone the directory to your local machine
2. Create a free account on 000webhost.com
3. Create a database (on 000webhost) and create a Table 'IDS' with one column 'curr_id' (Integer)
4. Add the index.php file to your site and edit all the credentials (Database, SAKEC Placemment Portal, 'your-email@email.com', its password and recipients list)
5. Goto your-email@email.com's security setting page and “Allow less secure apps”.
6. Goto this [link] (https://www.google.com/accounts/DisplayUnlockCaptcha) and hit continue.
7. Run index.php
8. Create an account on cron-job.org and setup a cronjob that hits the website every 10 min.
9. You are now all set. Note that you can use $mail->SMTPDebug=1 to debug the mail functionality

# To-Do
~~1. Making the mail content dynamic.~~ <br>
~~2. Better way to send emails in bulk (avoiding the 50emails/day limit)~~ <br>
3. Use Application specific password to make the script more secure

