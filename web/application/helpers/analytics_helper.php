<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------
/**
 * analytics_serie function.
 * 
 * @access public
 * @param mixed $object : object result from ga_api library
 * @return array
 */
function analytics_serie($object)
{
    $metrics = array_keys((array)$object['summary']->metrics);
    		
    list($year, $month, $day) = explode('-', $object['summary']->startDate);
    $timestamp = mktime(0, 0, 0, $month, ($day + 1), $year).'000';
    
    foreach ($metrics as $metric)
    {	
    	$data[$metric]['pointInterval'] = 24 * 3600 * 1000;		
    	$data[$metric]['pointStart'] = $timestamp;
    	$data[$metric]['name'] = $metric;
    	$data[$metric]['data'] = data_array($metric, $object);
    }
    return $data;
}

/**
 * data_array function.
 * 
 * @access public
 * @param mixed $string
 * @param mixed $array
 * @param mixed array &$result. (default: null)
 * @return array
 */
function data_array($string, $array, array &$result = null) 
{
    if (is_null($result)) $result = array();

	foreach ($array as $key => $value) 
	{
		if ($key != 'summary')
   		{
    	    if ($key == $string && !is_object($value)) 
    	    {
    	        $result[] = (float)$value;
    	    }
    	    else if (is_object($value)) 
    	    {
    	        data_array($string, $value, $result);
    	    }
    	}
    }
    return $result;
}
