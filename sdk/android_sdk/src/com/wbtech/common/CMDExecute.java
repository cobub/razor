/**
 * Cobub Razor
 *
 * An open source analytics android sdk for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 0.1
 * @filesource
 */
package com.wbtech.common;

import java.io.File;
import java.io.IOException;
import java.io.InputStream;

public class CMDExecute { 
	public synchronized String run(String [] cmd, String workdirectory) throws IOException { 
	String result = ""; 
	try { 
	ProcessBuilder builder = new ProcessBuilder(cmd); 
	InputStream in = null; 
	//����һ��·�� 
	if (workdirectory != null) { 
	builder.directory(new File(workdirectory)); 
	builder.redirectErrorStream(true); 
	Process process = builder.start(); 
	in = process.getInputStream(); 
	byte[] re = new byte[1024]; 

	while (in.read(re) != -1) 
	result = result + new String(re); 
	} 
	if (in != null) { 
	in.close(); 
	} 
	} catch (Exception ex) { 
	ex.printStackTrace(); 
	} 
	return result; 
	} 
	} 

