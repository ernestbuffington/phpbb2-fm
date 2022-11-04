<html>
<body>

<?php

// With this PHP script you can easily protect your online folders.
// Simply upload this file into the folder that you want to protect
// e.g. http://www.yourdomain.com/folder/htaccess-protectthisfolder.php
// and then run it.
// It will ask you for the desired username and the desired password
// and will then create the needed .htaccess and .htpasswd files to password-protect your folder

// This file works as a standalone for ANY folder but is actually part of the 
// instructions "How to make your phpBB more secure and to protect it against hackers" at
// http://www.in-my-opinion.org/in-my-opinion-3734.html
// All questions and suggestions can be formulated at that URL.


$nameofscript = basename($HTTP_SERVER_VARS['PHP_SELF']);

if ((file_exists('.htaccess')) || (file_exists('.htpasswd')))
	{
	echo 'Sorry, a ".htaccess" or a ".htpasswd" file exists already in this folder. You have to delete it first!';
	}
else
	{
	if (($username) && ($password))
		{
		if (strlen($password) > 8)
			{
			echo 'Password can be max. 8 characters long';
			}
		else
			{
			$passwordhash = crypt($password,chr(rand(65,90)).chr(rand(65,90)));
			// echo '<br />'.$passwordhash;
			
			$htaccess = <<<HEREDOC
Options -Indexes

<Files .htaccess>
order allow,deny
deny from all
</Files>
<Files .htpasswd>
order allow,deny
deny from all
</Files>

AuthUserFile {fullpath}/.htpasswd
AuthGroupFile /dev/null
AuthName "Password Protected Area"
AuthType Basic
Require valid-user
HEREDOC;
			$htpasswd = $username.':'.$passwordhash;
			$htaccess = str_replace('{fullpath}',dirname(__FILE__),$htaccess);
			
			// Write .htpasswd (The file that contains the username and the password)
			if ($handle = @fopen('.htpasswd', 'w'))
				{
				fwrite($handle, $htpasswd);
				fclose($handle);
				}
			else
				{
				echo '<br />'.'Cannot write ".htpasswd". Are the folder permissions set to "world writable"?';
				}
			
			// Write .htaccess (The file that manages the security)
			if ($handle = @fopen('.htaccess', 'w'))
				{
				fwrite($handle, $htaccess);
				fclose($handle);
				}
			else
				{
				echo '<br />'.'Cannot write ".htaccess". Are the folder permissions set to "world writable"?';
				}
			
			if ((@filesize('.htaccess') > 0) && (@filesize('.htpasswd') > 0))
				{
				echo <<<HEREDOC
<br />OK, your folder should be protected now!
<br />If someone tries to call/see a file within this folder he will have to know the username and password.
<br />(You maybe need to close all browser windows now to see the access protection working.)
<br />You can now safely delete "$nameofscript".
HEREDOC;
				}
			else
				{
				echo '<br />'.'Something went wrong!';
				}
			}
		}
	else
		{
		echo <<<HEREDOC
1) If the script fails to work then set the attributes of the folder where "$nameofscript" is located to 755 or to 777. You can re-change the attributes later.
<br />
2) Specify a fantasy username and a fantasy password below and press "submit"
<br />
		<center>
		<form method="post" action="$nameofscript">
		Desired username: <input name="username">
		<br />
		Desired password: <input name="password">
		<br />
		<input type="submit">
		
		</form>
		</center>


HEREDOC;
		}
	
	}
?>

</body>
</html>