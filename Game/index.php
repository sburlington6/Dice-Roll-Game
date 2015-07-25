<?php
session_start();

$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$filename = $parts[count($parts) - 1];

$type = (isset($_POST['type']) ? $_POST['type'] : '');

$types = array(
'1',
'2',
'3',
'4',
'5',
'6',
'3kind',
'4kind',
'fullHouse',
'sStraight',
'lStraight',
'yahtzee',
'chance'
);

$names = array(
"1's",
"2's",
"3's",
"4's",
"5's",
"6's",
"3 of a Kind",
"4 of a Kind",
"Full House",
"Small Straight",
"Large Straight",
"Yahtzee",
"Chance",
);

$descriptions = array(
"Add up all of the 1's",
"Add up all of the 2's",
"Add up all of the 3's",
"Add up all of the 4's",
"Add up all of the 5's",
"Add up all of the 6's",
"Total of All Dice When You Have at Least Three of One Number",
"Total of All Dice When You Have at Least Four of One Number",
"Gives You 25 Points When You Have Three of One Number And Two of Another Number (or Five of One number)",
"Gives You 30 Points When You Have a Sequence of Four Numbers ex. 2,3,4,5",
"Gives You 40 Points When You Have a Sequence of Five Numbers ex. 2,3,4,5,6",
"Gives You 50 Points When You Have Five of a Kind",
"Adds the Total of All Dice"
);

if (isset($_POST['newGame']))
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

function score($type)
{
	
}

if ($_SESSION['roll'] > 0 AND isset($_POST['select']))
{
	score($type);
}

if ($_SESSION['roll'] > 0 AND isset($_POST['select']))
{
	
	//score 1-6
	for ($j=1;$j<=5;$j++)
	{
		if ($_SESSION['die'][$j] == $type)
		{
			$score = isset($_SESSION['score'][$type]) ? $_SESSION['score'][$type] : "0";
			$_SESSION['score'][$type] = $score + $type;
		}
	}
	
	if (!isset($_SESSION['score'][$type]))
	{
		$_SESSION['score'][$type] = 0;
	}
	
	rollReset();
	
	//score 3kind
	if ($type == '3kind')
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
		rollReset();
	}
	elseif ($type == '4kind')
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
		rollReset();
	}
	elseif ($type == 'fullHouse')
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
		rollReset();
	}
	elseif ($type == 'sStraight')
	{
		$die=array();
		for ($j=1;$j<=5;$j++)
		{
			$die[$j] = $_SESSION['die'][$j];
		}
		if ((in_array(1,$die) AND in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die)) OR (in_array(2,$die) AND in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die)) OR (in_array(3,$die) AND in_array(4,$die) AND in_array(5,$die) AND in_array(6,$die)))
		{
			$_SESSION['score']['sStraight'] = 30;
		}
		else
		{
			$_SESSION['score']['sStraight'] = 0;
		}
		rollReset();
	}
	elseif ($type == 'lStraight')
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
		rollReset();
	}
	elseif ($type == 'yahtzee')
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
		rollReset();
	}
	elseif ($type == 'chance')
	{
		$score = 0;
		for ($j=1;$j<=5;$j++)
		{
			$score = $score + $_SESSION['die'][$j];
		}
		$_SESSION['score']['chance'] = $score;
		
		rollReset();
	}
}

//get the total score
$totalScore = 0;
foreach($_SESSION['score'] as $type)
{
	$totalScore = $totalScore + $type;
}

$topScore =  0;
for ($j=1;$j<=6;$j++)
{
	if (isset($_SESSION['score'][$j]))
	{
		$topScore = $topScore + $_SESSION['score'][$j];
	}
}
if (!isset($_SESSION['score']['bonus']) AND $topScore > 63)
{
	$_SESSION['score']['bonus'] = 35;
}

function rollReset(){
	global $filename;
	$_SESSION['selection'] = '1';
	unset($_SESSION['roll']);
	unset($_SESSION['hold']);
	unset($_SESSION['die']);
	unset($_SESSION['selection']);
	header( "Location: $filename" ) ;
}
?>


<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Dice Roll Game">
		<meta name="author" content="Richard Bird">
		<title>Yahtzee</title>
		<link href="yahtzee.css" rel="stylesheet" type="text/css" />
		
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
	</head>
	<body>
		<div id="wrapper">
			<div id="left">
				<div id="game" class="center">
					
					<h2>Roll <?php echo $_SESSION['roll']; ?></h2>
					<table id="dice">
						<tr>
						<?php
							for ($i=1;$i<=5;$i++)
							{
								?>
								<td class="diceWrapper">
									<image class="dice" src="images/dice/<?php echo $_SESSION['die'][$i]; ?>.png" alt="dice">
								</td>
								<?php
							}
							?>
						</tr>
						<tr>
						<?php
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
							?>
						</tr>
					</table>
					<?php
					if ($_SESSION['roll'] < 3)
					{
						echo '<form  class="inline" action="'.$filename.'" method="post">';
							echo '<input type="submit" value="Roll" name="roll"/>';
						echo '</form>';
					}
					?>
					<form class="inline" action="<?php echo $filename; ?>" method="post">
						<input type="submit" value="NEW GAME" name="reset"/>
					</form>
				</div>
			
				<div id="debug">
					_SESSION
					<pre>
					<?php var_dump($_SESSION);?>
					</pre>
					_POST
					<pre>
					<?php var_dump($_POST);?>
					</pre>
				</div>
			</div>
			<div id="right">
				<div id="scoreCard">
					<table>
						<tr>
							<th colspan="3">Score Card</th>
						</tr>
				<?php
				
				for ($i=0;$i<count($types);$i++)
				{
					
				echo 		'<tr>';
								if ($_SESSION['selection'] != '1' AND !isset($_SESSION['score'][$types[$i]]) AND $_SESSION['roll'] >0)
								{
				echo 			'<td class="select center">';
				echo 				'<form action="'.$filename.'" method="post">';
				echo 					'<input type="hidden" value="'.$types[$i].'" name="type"/>';
				echo 					'<input type="submit" value="'.$names[$i].'" name="select"/>';
				echo 				'</form>';
				
				echo 			'</td>';
								}
								else
								{
									
				echo 			'<td class="center">'.$names[$i].'</td>';
								}
				echo 			'<td class="how">'.$descriptions[$i].'</td>';
				echo 			'<td class="player">';
									if (isset($_SESSION['score'][$types[$i]]))
									{
										echo $_SESSION['score'][$types[$i]];
									}
				echo 			'</td>';
				echo 		'</tr>';
				}
				
				?>
						<tr class ="score">
							<td class="type">Total Top</td>
							<td class="how">The total Score of 1's - 6's</td>
							<td class="player">
								<?php echo $topScore;?>
							</td>
						</tr>
				
						<tr class ="score">
							<td class="type">Bonus</td>
							<td class="how">If the total Score of 1's - 6's is over 63 Add 35 Points</td>
							<td class="player">
				<?php
									if (isset($_SESSION['score']['bonus']))
									{
										echo $_SESSION['score']['bonus'];
									}
				echo 			'</td>';
				echo 		'</tr>';
				?>
						<tr class="score">
							<td colspan="2" style="text-align: right;">Total Score</td>
							<td class="player">
								<?php echo $totalScore;?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>