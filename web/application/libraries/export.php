<?php
/**
* 导出Excel
*
* @package:     Jason
* @subpackage:  Excel
* @version:     1.0
*/
class Export
{
    /**
    * Excel 标题
    *
    * @type: Array
    */
    private $_titles            = array();
 
    /**
    * Excel 标题数目
    *
    * @type: int
    */
    private $_titles_count      = 0;
 
    /**
    * Excel 内容
    *
    * @type:  Array
    */
    private $_contents          = array();
 
    /**
    * Excel 内容数据
    *
    * @type:  Array
    */
    private $_contents_count    = 0;
 
    /**
    * Excel 文件名
    *
    * @type: string
    */
    private $_fileName  = '';
    private $_split     = "\t";
 
    private $_charset   = '';
 
    /**
    * 默认文件名
    *
    * @const :
    */
    const DEFAULT_FILE_NAME = 'ums.xls';
 
    /**
    * 构造函数..
    *
    * @param    string  param
    * @return   mixed   return
    */
    function __construct($fileName = null)
    {
        if ($fileName !== null)
        {
            $this->_fileName = $fileName;
        }
        else
        {
            $this->setFileName();
        }
    }
 
    /**
    * 设置生成文件名
    *
    * @param    string  param
    * @return   mixed   Jason_Excel_Export
    */
    public function setFileName($fileName = self::DEFAULT_FILE_NAME)
    {
        $this->_fileName = $fileName;
        $this->setSplite();
        return $this;
    }
 
    private function _getType()
    {
        return substr($this->_fileName, strrpos($this->_fileName, '.') + 1);
    }
 
    public function setSplite($split = null)
    {
        if ($split === null)
        {
            switch ($this->_getType())
            {
                case 'xls': $this->_split = "\t"; break;
                case 'csv': $this->_split = ","; break;
            }
        }
        else
            $this->_split = $split;
    }
 
    /**
    * 设置Excel标题
    *
    * @param    string  param
    * @return   mixed   Jason_Excel_Export
    */
    public function setTitle( &$title = array())
    {
        $this->_titles          = $title;
        $this->_titles_count    = count($title);
        return $this;
    }
 
    /**
    * 设置Excel内容
    *
    * @param    string  param
    * @return   mixed   Jason_Excel_Export
    */
    public function setContent(&$content = array())
    {
        $this->_contents          = $content;
        $this->_contents_count    = count($content);
        return $this;
    }
 
    /**
    * 向excel中添加一行内容
    */
    public function addRow($row = array())
    {
        $this->_contents[] = $row;
        $this->_contents_count++;        
        return $this;
       
    }
 
    /**
    * 向excel中添加多行内容
    */
    public function addRows($rows = array())
    {
        $this->_contents = array_merge($this->_contents, $rows);
        $this->_contents_count += count($rows);
        return $this;
    }
 
    /**
    * 数据编码转换
    */
    public function toCode($type = 'GB2312', $from = 'auto')
    {
        foreach ($this->_titles as $k => $title)
        {
            $this->_titles[$k] = mb_convert_encoding($title, $type, $from);
        }
 
        foreach ($this->_contents as $i => $contents)
        {
            $this->_contents[$i] = $this->_toCodeArr($contents);
        }
 
        return $this;
    }
 
    private function _toCodeArr(&$arr = array(), $type = 'GB2312', $from = 'auto')
    {
        foreach ($arr as $k => $val)
        {
            $arr[$k] = mb_convert_encoding($val, $type, $from);
        }
 
        return $arr;
    }
 
    public function charset($charset = '')
    {
        if ($charset == '')
            $this->_charset = '';
        else
        {
            $charset = strtoupper($charset);
            switch($charset)
            {
                case 'UTF-8' :
                case 'UTF8' :
                    $this->_charset = ';charset=UTF-8';
                  break;
 
                default:
                    $this->_charset = ';charset=' . $charset;
            }
        }
 
        return $this;
    }
 
    /**
    * 导出Excel
    *
    * @param    string  param
    * @return   mixed   return
    */
    public function export()
    {
        $header = '';
        $data   = array();
 
        $header = implode($this->_split, $this->_titles);
 
        for ( $i = 0; $i < $this->_contents_count; $i++ )
        {
            $line_arr   = array();
            foreach ( $this->_contents[$i] as $value )
            {            	
                if (!isset($value) || $value == "")
                {
                    $value = "0";
                   
                }
                else
                {
                	
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"';
                     
                }
 
                $line_arr[] = $value;
                
            }
 
            $data[] = implode($this->_split, $line_arr);
            
        }
 
        $data = implode("\n", $data);
        $data = str_replace("\r", "", $data);
        
        if ($data == "")
        {
            $data = "\n(0) Records Found!\n";
        }
        $data = mb_convert_encoding($data,"GB2312","utf-8");
 
        header("Content-type: application/vnd.ms-excel" . $this->_charset);
        header("Content-Disposition: attachment; filename=$this->_fileName");
        header("Pragma: no-cache");
        header("Expires: 0");
 
       echo $header . "\n" . $data;
    }
}
