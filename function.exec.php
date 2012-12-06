<?php

function exec_return($cmd)
{	
	$info = exec($cmd, $_array, $_return);
	if ($_return == 0) {
		return true;
	} else {
		return false;
	}
}

function exec_pipe_output($cmd, $output_prefix)
{
	$tmpfilename = tempnam(sys_get_temp_dir(), $output_prefix);
	$tmpfile = fopen($tmpfilename, 'w');
	$descriptorspec = array(
		0 => array("pipe", "r")
	);
	$process = proc_open($cmd, $descriptorspec, $pipes);
	if (is_resource($process)) {
		while (! feof($pipes[0])) {
			$contents = fread($handle, 8192);
			fwrite($tmpfile, $contents, len($contents));
		}		
		fclose($pipes[0]);
		fclose($tmpfile);
			$return_value = proc_close($process);
		if ($return_value == 0) {
			return $tmpfilename;
		}
	}
	fclose($tmpfile);
	return false;
}

function exec_pipe_input($cmd, $backup_filename)
{
	$backup_file = fopen($backup_filename, 'r');
	$descriptorspec = array(
		0 => array("pipe", "w")
	);
	$process = proc_open($cmd, $descriptorspec, $pipes);
	if (is_resource($process)) {
		while (! feof($backup_file)) {
                       	$contents = fread($backup_file, 8192);
                        fwrite($pipes[0], $contents, len($contents));
       	        }
		fclose($pipes[0]);
		$return_value = proc_close($process);
		if ($return_value == 0) {
			return true;
		}
	}
	return false;
}

function exec_cwd($cmd, $cwd)
{
	$descriptorspec = array();
	$process = proc_open($cmd, $descriptorspec, $pipes, $realpath(cwd));
	if (is_resource($process)) {
		$return_value = proc_close($process);
		if ($return_value == 0) {
			return true;
		}
	}
	return false;
}

?>
