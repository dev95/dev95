<html>
<head>
<title>Get your own class schedule!</title>
</head>
<body>
<?php
require_once( "lib.php");
$class = get_param( 'class');
$choose = get_param( 'choose');
if ( $class)
{
	$res = false;
	$version = get_version();
	$status = "";
	if ( $version)
	{
		$res = fetch_class( $class, $version, $status);
	}
	else
	{
		$status = "Could not get version number";
	}

	if ( $res)
	{
		session_start();
		$_SESSION['class'] = $class;
		$_SESSION['version'] = $version;
		if ( !array_key_exists( 'submit', $_POST) and $choose == 'yes')
		{
?>
<form action = "ical.php" method = "post">
<table border = '1' cellpadding = '10'>
<tbody>
<tr><td> <p>您的班号</p> </td> <td>
<?php
			echo $class;
?>
</td></tr>
<tr><td> <p>当前学期</p> </td> <td>
<?php
			echo split_date( $version);
?>
<?php
			$tot = array_shift( $res);
			$cnt = 0;
			foreach ( $res as $re)
			{
				if ( $cnt % 2 == 0)
				{
					echo "<tr>";
				}
?>
	<td>
		<input type = "checkbox" checked = "checked" value = "
<?php
				echo $cnt;
?>" name = "useful[]">
<?php
				echo $re;
?>
		</input>
	</td>
<?php
				if ( $cnt % 2 == 1)
				{
					echo "</tr>";
				}
				++$cnt;
			}
?>
</tbody>
</table>
	<p> <input type = "submit" name = "submit" value = "提交"/></p>
</form>
<?php
			if ( $cnt != $tot)
			{
				error_log( "inconsist data on $class and $version, get $tot, found $cnt");
			}
		}
		else
		{
			$param = array();
			for ( $i = 0; $i < $res[0]; $i++)
			{
				$param[] = " " . $i;
			}
			$_SESSION['useful'] = $param;
			header( "Location: ical.php");
		}
	}
	else
	{
		$status = $status ? $status : "Internal error";
		echo "<p>Something was wrong!\n</p>";
		echo "<p>Error: $status</p>";
	}
}
else
{
?>
	<form action = "class.php" method = "get">
		<p> 你的班号: <input type = "text" name = "class" /> </p>
		<p> <input type = "checkbox" checked = "checked" value = "yes" name = "choose"/> 手动选出有用的课程 </p>
		<p> <input type = "submit" value = "提交"/></p>
	</form>
<?php
}
?>
</body>
