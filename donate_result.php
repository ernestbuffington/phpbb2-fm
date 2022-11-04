<?php
/** 
*
* @package phpBB2
* @version $Id:	donate_result.php,v 1.0.0.1 2004/10/23 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($HTTP_POST_VARS as $key => $value) 
{
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = @fsockopen('www.paypal.com', 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = htmlspecialchars($HTTP_POST_VARS['item_name']);
$item_number = htmlspecialchars($HTTP_POST_VARS['item_number']);
$payment_status = htmlspecialchars($HTTP_POST_VARS['payment_status']);
$payment_amount = htmlspecialchars($HTTP_POST_VARS['mc_gross']);
$payment_currency = htmlspecialchars($HTTP_POST_VARS['mc_currency']);
$txn_id = htmlspecialchars($HTTP_POST_VARS['txn_id']);
$receiver_email = htmlspecialchars($HTTP_POST_VARS['receiver_email']);
$payer_email = htmlspecialchars($HTTP_POST_VARS['payer_email']);

if (!$fp) 
{
	// HTTP ERROR
} 
else 
{
	fputs($fp, $header . $req);
	while (!feof($fp)) 
	{
		$res = fgets($fp, 1024);
		if (strcmp($res, "VERIFIED") == 0) 
		{
			// check the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
		
			//item_number format 12-0 $user_id-$anonymous
			$pos = strpos($item_number, '-', 0);
			$user_id = 0;
			$anonymous = 0;
			if($pos !== false)
			{
				$user_id = intval(substr($item_number, 0, $pos));
				$anonymous = intval(substr($item_number, $pos + 1));
			}	
			if($user_id <= 0)
			{
				$user_id = ANONYMOUS;
			}
			if($anonymous != 1)
			{
				$anonymous = 0;
			}

			$sql = "SELECT * 
				FROM " . USERS_TABLE . " 
				WHERE user_id = " . $user_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				$user_id = ANONYMOUS;
			}
			$lwuserdata = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		
			if($lwuserdata['user_id'] <= 0)
			{
				$user_id = ANONYMOUS;
			}

			// update the payee's account with payment
			$poster_convertor = lw_cal_cash_exchange_rate($payment_currency, $board_config) + 0; 
			if($poster_convertor <= 0)
			{
				$poster_convertor = 1.0;
			}
	
			$lw_mny_payee = ($payment_amount + 0.00) / ($poster_convertor);
				
			$payment_amount = $lw_mny_payee;
			$payment_currency = $board_config['paypal_currency_code'];

			if(strcasecmp($payment_status, 'Completed') == 0)
			{
				if($user_id > 0)
				{
					if(intval($board_config['donate_to_points']) > 0)
					{			
						$sql = "UPDATE " . USERS_TABLE . " 
							SET user_points = user_points + " . (intval(intval($board_config['donate_to_points']) * ($payment_amount + 0.00))) . " 
							WHERE user_id = " . $user_id;
						if ( !($result = $db->sql_query($sql)) )
						{
							//do nothing
						}
					}
					else if (intval($board_config['donate_to_posts']) > 0)
					{			
						$sql = "UPDATE " . USERS_TABLE . " 
							SET user_posts = user_posts + " . (intval(intval($board_config['donate_to_posts']) * ($payment_amount + 0.00))) . " 
							WHERE user_id = " . $user_id;
						if ( !($result = $db->sql_query($sql)) )
						{
							//do nothing
						}
					}
						
					$sql = "SELECT SUM(lw_money) 
						FROM " . ACCT_HIST_TABLE . " 
						WHERE comment LIKE 'Donation from%%' 
							AND user_id = " . $user_id;
					$amount_donated = ($payment_amount + 0.00);
					if($result = $db->sql_query($sql))
					{
						if($row = $db->sql_fetchrow($result))
						{
							$amount_donated = $amount_donated + $row["SUM(lw_money)"];
						}
					}

					$grptojoin = 0;
					if( intval($board_config['donate_to_grp_one']) > 0 && ($board_config['to_grp_one_amount'] + 0.00) < ($amount_donated) )
					{
						$grptojoin = intval($board_config['donate_to_grp_one']);
					}
				
					if(intval($board_config['donate_to_grp_two']) > 0 && ($board_config['to_grp_two_amount'] + 0.00) < ($amount_donated) && ($board_config['to_grp_one_amount'] + 0.00) < ($board_config['to_grp_two_amount'] + 0.00) )
					{
						$grptojoin = intval($board_config['donate_to_grp_two']);
					}
				
					if($grptojoin > 0)
					{
					   	$sql = "SELECT * 
					   		FROM " . USER_GROUP_TABLE . " 
					   		WHERE group_id = " . $grptojoin . " 
					   			AND user_id = " . $user_id; 			
				   		// query database 
				   		$need_to_add = 1;
				   		if ( ($result = $db->sql_query($sql)) ) 
				   		{ 
						   	if ( $row = $db->sql_fetchrow($result) ) 
						   	{
	 							if($row['user_pending'] == 0)
	 							{
		 					   		$need_to_add = 0;
	 							}
	 							if($row['user_pending'] != 0)
	 							{
		 					   		$need_to_add = 2; //need update
	 							}
						   	}
				   		}
				   	 
				  	 	if($need_to_add == 1)
				  	 	{
							//add to the donor group
							$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending) 
								VALUES ($user_id, $grptojoin, 0)";
							if( !($result = $db->sql_query($sql)) )
							{
								//do nothing
							}			
				   		} //end add to the donor group
				   	
				   		if($need_to_add == 2)
				   		{
							//update the donor group
							$sql = "UPDATE " . USER_GROUP_TABLE . " 
								SET user_pending = 0 
								WHERE group_id = " . $grptojoin . " 
									AND user_id = " . $user_id;
							if( !($result = $db->sql_query($sql)) )
							{
									//do nothing
							}			
						} //end update the donor group
					}
				
					if(intval($board_config['donor_rank_id']) > 0 && $anonymous != 1)
					{
						$sql = "UPDATE " . USERS_TABLE . " 
							SET user_rank = " . intval($board_config['donor_rank_id']) . " 
							WHERE user_id = " . $user_id;
						if ( !($result = $db->sql_query($sql)) )
						{
							//do nothing
						}
					}
				}
				
				if($anonymous == 1)
				{
					$user_id = ANONYMOUS;
				}
				
				$sql = "INSERT INTO " . ACCT_HIST_TABLE . " (user_id, lw_post_id, lw_money, lw_plus_minus, MNY_CURRENCY, lw_date, comment, lw_site, status, txn_id) 
					VALUES (" . $user_id . ", 0, " . ($payment_amount + 0.00) . ", -1, '" . str_replace("\'", "''", $payment_currency) . "', " . time() . ", 'Donation from " . str_replace("\'", "''", $payer_email) . ", Thank you!', '$table_prefix', '" . str_replace("\'", "''", $payment_status) . "', '" . str_replace("\'", "''", $txn_id) . "')";
				if ( !($result = $db->sql_query($sql)) )
				{
					//do nothing
				}	
				@fclose($fp);
				exit;
			}
			
			if($anonymous == 1)
			{
				$user_id = ANONYMOUS;
			}
			
			$sql = "INSERT INTO " . ACCT_HIST_TABLE . " (user_id, lw_post_id, lw_money, lw_plus_minus, MNY_CURRENCY, lw_date, comment, lw_site, status, txn_id) 
				VALUES (" . $user_id . ", 0, " . ($payment_amount + 0.00) . ", -1, '" . str_replace("\'", "''", $payment_currency) . "', " . time() . ", 'for dondation by: " . str_replace("\'", "''", $payer_email) . ".', '$table_prefix', '" . str_replace("\'", "''", $payment_status) . "', '" . str_replace("\'", "''", $txn_id) . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				//do nothing
			}	
		}
		else if (strcmp ($res, "INVALID") == 0) 
		{
			// log for manual investigation
			$sql = "INSERT INTO " . ACCT_HIST_TABLE . " (user_id, lw_post_id, lw_money, lw_plus_minus, MNY_CURRENCY, lw_date, comment, lw_site, status, txn_id) 
				VALUES (ANONYMOUS, 0, " . ($payment_amount + 0.00) . ", -1, '" . str_replace("\'", "''", $payment_currency) . "', " . time() . ", 'for donation by: " . str_replace("\'", "''", $payer_email) . " to: " . str_replace("\'", "''", $receiver_email) . ", INVALID', '$table_prefix', '" . str_replace("\'", "''", $payment_status) . "', '" . str_replace("\'", "''", $txn_id) . "')";
			if ( !($result = $db->sql_query($sql)) )
			{
				//do nothing
			}
		}
	}
	@fclose($fp);
}

$message = $lang['LW_DONATE_DONE'] . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

message_die(GENERAL_MESSAGE, $message);

exit;

?>