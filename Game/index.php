<?php
session_start();

$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$filename = $parts[count($parts) - 1];

if (isset($_POST['newGame']))
{
	header( "Location: $filename" ) ;
}
if (isset($_POST['resetScores']))
{
	header( "Location: $filename" ) ;
}

if (isset($_POST['reset']))
{
	session_destroy();
	header( "Location: $filename" ) ;
}

if (!isset($_SESSION['score']))
{
	$_SESSION['score'] = array();
}
if (!isset($_SESSION['total']))
{
	$_SESSION['total'] = '';
}

if (!isset($_SESSION['roll']))
{
	$_SESSION['roll'] = 0;
}

if (!isset($_SESSION['die']))
{
	$_SESSION['die'][1] = 1;
	$_SESSION['die'][2] = 1;
	$_SESSION['die'][3] = 1;
	$_SESSION['die'][4] = 1;
	$_SESSION['die'][5] = 1;
}

if (!isset($_SESSION['die']))
{
	$_SESSION['die'] = array();
}

if (!isset($_SESSION['selection']))
{
	$_SESSION['selection'] = '0';
}

if (!isset($_SESSION['hold']))
{
	$_SESSION['hold'] = array();
}

if (isset($_POST['hold']))
{
	$dieToHold = $_POST['hold'];
	$_SESSION['hold'][$dieToHold] = true;
}

if (isset($_POST['unhold']))
{
	$dieToHold = $_POST['unhold'];
	$_SESSION['hold'][$dieToHold] = false;
}

if (isset($_POST['roll']) AND $_SESSION['roll'] < 3)
{
	$_SESSION['roll']++;
	for ($i=1;$i<6;$i++)
	{
		if (isset($_SESSION['hold'][$i]))
		{
			if (!$_SESSION['hold'][$i])
			{
				$_SESSION['die'][$i] = rand(1, 6);
			}
		}
		else
		{
			$_SESSION['die'][$i] = rand(1, 6);
		}
	}
}
elseif (isset($_POST['roll']) AND $_SESSION['roll'] = 3)
{
	//echo this in the body not here
	echo 'you cant roll anymore';
}

if ($_SESSION['roll'] > 0 AND isset($_POST['select']))
{
	for ($i=1;$i<=6;$i++)
	{
		if ($_POST['type'] == $i.'s')
		{
			for ($j=1;$j<=5;$j++)
			{
				if ($_SESSION['die'][$j] == $i)
				{
					$score = isset($_SESSION['score'][$i]) ? $_SESSION['score'][$i] : "0";
					$_SESSION['score'][$i] = $score + $i;
				}
			}
			
			if (!isset($_SESSION['score'][$i]))
			{
				$_SESSION['score'][$i] = 0;
			}
			
			$_SESSION['topScore'] = 0;
			for ($j=1;$j<=6;$j++)
			{
				if (isset($_SESSION['score'][$j]))
				{
					$_SESSION['topScore'] = $_SESSION['topScore'] + $_SESSION['score'][$j];
				}
			}
			if (!isset($_SESSION['score']['bonus']) AND $_SESSION['topScore'] > 63)
			{
				$_SESSION['score']['bonus'] = 35;
			}
			
			$_SESSION['selection'] = '1';
			unset($_SESSION['roll']);
			unset($_SESSION['hold']);
			unset($_SESSION['die']);
			unset($_SESSION['selection']);
			header( "Location: $filename" ) ;
		}
	}
	
	if ($_POST['type'] == '3kind')
	{
		$inThere = array(0,0,0,0,0,0,0);
		$score = 0;
		for ($j=1;$j<=5;$j++)
		{
			$inThere[$_SESSION['die'][$j]]++;
			$score = $score + $_SESSION['die'][$j];
		}
		if (in_array(3,$inThere) OR in_array(4,$inThere) OR in_array(5,$inThere))
		{
			$_SESSION['score']['3kind'] = $score;
		}
		else
		{
			$_SESSION['score']['3kind'] = 0;
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == '4kind')
	{
		$inThere = array(0,0,0,0,0,0,0);
		$score = 0;
		for ($j=1;$j<=5;$j++)
		{
			$inThere[$_SESSION['die'][$j]]++;
			$score = $score + $_SESSION['die'][$j];
		}
		if (in_array(4,$inThere) OR in_array(5,$inThere))
		{
			$_SESSION['score']['4kind'] = $score;
		}
		else
		{
			$_SESSION['score']['4kind'] = 0;
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == 'fullHouse')
	{
		$inThere = array(0,0,0,0,0,0,0);
		for ($j=1;$j<=5;$j++)
		{
			$inThere[$_SESSION['die'][$j]]++;
		}
		if ((in_array(3,$inThere) AND in_array(2,$inThere)) OR in_array(5,$inThere))
		{
			$_SESSION['score']['fullHouse'] = 25;
		}
		else
		{
			$_SESSION['score']['fullHouse'] = 0;
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == 'sStraight')
	{
		for ($j=1;$j<=5;$j++)
		{
			$die[] = $_SESSION['die'][$j];
		}
		if ((in_array(1,$die) AND in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die)) OR (in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die)) OR (in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die) AND in_array(6,$die)))
		{
			$_SESSION['score']['sStraight'] = 30;
		}
		else
		{
			$_SESSION['score']['sStraight'] = 0;
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == 'lStraight')
	{
		for ($j=1;$j<=5;$j++)
		{
			$die[] = $_SESSION['die'][$j];
		}
		if ((in_array(1,$die) AND in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die)) OR (in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die) AND in_array(6,$die)))
		{
			$_SESSION['score']['lStraight'] = 40;
		}
		else
		{
			$_SESSION['score']['lStraight'] = 0;
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == 'yahtzee')
	{
		$inThere = array(0,0,0,0,0,0,0);
		for ($j=1;$j<=5;$j++)
		{
			$inThere[$_SESSION['die'][$j]]++;
		}
		if (in_array(5,$inThere))
		{
			if ($_SESSION['score']['yahtzee'] >= 50)
			{
				$_SESSION['score']['yahtzee'] = $_SESSION['score']['yahtzee'] + 100;
			}
			else
			{
				$_SESSION['score']['yahtzee'] = 50;
			}
		}
		else
		{
			if ($_SESSION['score']['yahtzee'] >= 50)
			{
				$_SESSION['yahtzee']= "done";
			}
			else
			{
				$_SESSION['score']['yahtzee'] = 0;
			}
		}
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
	elseif ($_POST['type'] == 'chance')
	{
		$score = 0;
		for ($j=1;$j<=5;$j++)
		{
			$score = $score + $_SESSION['die'][$j];
		}
		$_SESSION['score']['chance'] = $score;
		
		$_SESSION['selection'] = '1';
		unset($_SESSION['roll']);
		unset($_SESSION['hold']);
		unset($_SESSION['die']);
		unset($_SESSION['selection']);
		header( "Location: $filename" ) ;
	}
}

//get the total score
$totalScore = 0;
foreach($_SESSION['score'] as $type)
{
	$totalScore = $totalScore + $type;
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Yahtzee</title>
		<link href="yahtzee.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="wrapper">
			<div id="game">
				<?php
				echo '<h2>Roll '.$_SESSION['roll'].'</h2>';
					echo '<table id="dice">';
						echo '<tr>';
							for ($i=1;$i<=5;$i++)
							{
								echo '<td class="dice">';
								if ($_SESSION['roll'] > 0)
								{
									echo '<image src="images/dice/'.$_SESSION['die'][$i].'.png" alt="dice" height="60" width="60">';
								}
								echo '</td>';
							}
						echo '</tr>';
						echo '<tr>';
							for ($i=1;$i<=5;$i++)
							{
								echo '<td class="hold">';
								if ($_SESSION['roll'] > 0)
								{
									if (isset($_SESSION['hold'][$i]) AND $_SESSION['hold'][$i])
									{
										echo '<form action="'.$filename.'" method="post">';
											echo '<input type="hidden" value="'.$i.'" name="unhold"/>';
											echo '<input type="submit" value="Unold" name="unholdsubmit"/>';
										echo '</form>';
									}
									else
									{
										echo '<form action="'.$filename.'" method="post">';
											echo '<input type="hidden" value="'.$i.'" name="hold"/>';
											echo '<input type="submit" value="Hold" name="holdsubmit"/>';
										echo '</form>';
									}
								}
								echo '</td>';
							}
						echo '</tr>';
					echo '</table>';
					
					if ($_SESSION['roll'] < 3)
					{
						echo '<form action="'.$filename.'" method="post">';
							echo '<input type="submit" value="Roll" name="roll"/>';
						echo '</form>';
					}
					echo '<form action="'.$filename.'" method="post">';
						echo '<input type="submit" value="NEW GAME" name="reset"/>';
					echo '</form>';
			echo '</div>';
			
			echo '<div id="scoreCard">';
			echo 	'<table>';
			echo 		'<tr>';
			echo 			'<th></th>';
			echo 			'<th></th>';
			echo 			'<th>How to Score</th>';
			echo 			'<th>Score</th>';
			echo 		'</tr>';
		for ($i=1;$i<=6;$i++)
		{
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score'][$i]))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="'.$i.'s" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">'.$i.'\'s</td>';
			echo 			'<td class="how">Add up all of the '.$i.'\'s</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score'][$i]))
								{
									echo $_SESSION['score'][$i];
								}
			echo 			'</td>';
			echo 		'</tr>';
		}
			
			echo 		'<tr class ="score">';
			echo 			'<td class="select"></td>';
			echo 			'<td class="type">Total Top</td>';
			echo 			'<td class="how">The total Score of 1\'s - 6\'s</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['topScore']))
								{
									echo $_SESSION['topScore'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr class ="score">';
			echo 			'<td class="select"></td>';
			echo 			'<td class="type">Bonus</td>';
			echo 			'<td class="how">If the total Score of 1\'s - 6\'s is over 63 Add 35 Points</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['bonus']))
								{
									echo $_SESSION['score']['bonus'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['3kind']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="3kind" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">3 of a Kind</td>';
			echo 			'<td class="how">Total of All Dice When You Have at Least Three of One Number</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['3kind']))
								{
									echo $_SESSION['score']['3kind'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['4kind']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="4kind" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">4 of a Kind</td>';
			echo 			'<td class="how">Total of All Dice When You Have at Least Four of One Number</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['4kind']))
								{
									echo $_SESSION['score']['4kind'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['fullHouse']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="fullHouse" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">Full House</td>';
			echo 			'<td class="how">Gives You 25 Points When You Have Three of One Number And Two of Another Number (or Five of One number) </td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['fullHouse']))
								{
									echo $_SESSION['score']['fullHouse'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['sStraight']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="sStraight" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">Small Straight</td>';
			echo 			'<td class="how">Gives You 30 Points When You Have a Sequence of Four Numbers ex. 2,3,4,5</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['sStraight']))
								{
									echo $_SESSION['score']['sStraight'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['lStraight']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="lStraight" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">Large Straight</td>';
			echo 			'<td class="how">Gives You 40 Points When You Have a Sequence of Five Numbers ex. 2,3,4,5,6</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['lStraight']))
								{
									echo $_SESSION['score']['lStraight'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['yahtzee']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="yahtzee" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">Yahtzee</td>';
			echo 			'<td class="how">Gives You 50 Points When You Have Five of a Kind</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['yahtzee']))
								{
									echo $_SESSION['score']['yahtzee'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr>';
			echo 			'<td class="select">';
							if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score']['chance']))
							{
			echo 				'<form action="'.$filename.'" method="post">';
			echo 					'<input type="hidden" value="chance" name="type"/>';
			echo 					'<input type="submit" value="Select" name="select"/>';
			echo 				'</form>';
							}
			echo 			'</td>';
			echo 			'<td class="type">Chance</td>';
			echo 			'<td class="how">Adds the Total of All Dice</td>';
			echo 			'<td class="player">';
								if (isset($_SESSION['score']['chance']))
								{
									echo $_SESSION['score']['chance'];
								}
			echo 			'</td>';
			echo 		'</tr>';
			
			echo 		'<tr class ="score">';
			echo 			'<td class="select"></td>';
			echo 			'<td class="type"></td>';
			echo 			'<td class="type" style="text-align: right;">Total Score</td>';
			echo 			'<td class="player">';
								if (isset($totalScore))
								{
									echo $totalScore;
								}
			echo 			'</td>';
			echo 		'</tr>';
			echo 	'</table>';
			echo '</div>';
			?>
		</div>
	</body>
</html>