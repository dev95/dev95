<?php

$bin_path = dirname( __FILE__) . "/cgi";

function find_param( $key)
{
	$ret = get_param( $key);
	if ( !$ret)
	{
		$ret = post_param( $key);
		if ( !$ret)
		{
			$ret = session_param( $key);
		}
	}

	return $ret;
}

function get_param( $key)
{
	global $_GET;
	return array_key_exists( $key, $_GET) ? $_GET[$key] : false;
}

function post_param( $key)
{
	global $_POST;
	return array_key_exists( $key, $_POST) ? $_POST[$key] : false;
}

function session_param( $key)
{
	global $_SESSION;
	return array_key_exists( $key, $_SESSION) ? $_SESSION[$key] : false;
}

function split_date( $day)
{
	$cur = explode( "-", $day);
	$cur[1] = $cur[1] < 6 ? "春" : "秋";
	return "$cur[0]年$cur[1]";
}

function get_version()
{
	global $bin_path;
	chdir( $bin_path);

	exec( "perl sql f 0", $version, $ret);
	if ( $ret != 0)
	{
		return false;
	}

	return trim( $version[0]);
}

function fetch_class( $class_num, &$status)
{
	global $bin_path;
	chdir( $bin_path);

	$version = get_version();
	if ( ! $version)
	{
		return false;
	}

	exec( "perl sql g $version $class_num", $res, $ret);
	if ( $ret != 0)
	{
		$status = "Get old record error!";
		return false;
	}

	$flag = trim( $res[0]);
	if ( $flag == 'NULL')
	{
		exec( "perl class $class_num | perl sql t $version", $res, $ret);
		if ( $ret != 0)
		{
			$status = "Store record error!";
			return false;
		}
		$flag = $res[0] ? $res[0] : 0;
		exec( "perl sql p $version $class_num $flag", $res, $ret);
		if ( $ret != 0)
		{
			$status = "Store record error!";
			return false;
		}
	}

	if ( $flag != 0)
	{
		$status = "Wrong class number! $flag";
		return false;
	}

	$res = array();
	exec( "perl sql c $version $class_num", $res, $ret);
	if ( $ret != 0)
	{
		$status = "Get courses error!";
		return false;
	}

	return $res;
}

function gen_ics( $class_num, $version, $param)
{
	global $bin_path;
	chdir( $bin_path);

	$version = get_version();
	$res = false;
	if ( $version)
	{
		$ret = 0;
		error_log( "perl sql s $version $class_num | perl ical $param", $res, $ret);
		exec( "perl sql s $version $class_num | perl ical $param", $res, $ret);
		if ( $ret != 0)
		{
			$res = false;
		}
	}

	$ret = "";
	foreach ( $res as $re)
	{
		$ret .= $re;
		$ret .= "\n";
	}
	return $ret;
}
