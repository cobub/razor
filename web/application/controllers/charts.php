<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class Charts extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		
		$data['home'] = strtolower(__CLASS__).'/';
		$this->load->vars($data);
		$this->load->model('common');
		$this->common->loadHeader();
	}
	
	
	/**
	 * index function.
	 * Very basic example: juste draw some data
	 */
	function index()
	{
		// simple highcharts example
		$this->load->library('highcharts');
		
		// some data series
		$serie['data'] = array(20, 45, 60, 22, 6, 36);
		
		$data['charts'] = $this->highcharts->set_serie($serie)->render();
		
		$this->load->view('charts', $data);
		
	}
	
	
	/**
	 * categories function.
	 * Lets go for a real world example
	 */
	function categories()
	{		
		
		$graph_data = $this->_data();
			
		$this->load->library('highcharts');
	
		$this->highcharts->set_type('column'); // drauwing type
		$this->highcharts->set_title('INTERNET WORLD USERS BY LANGUAGE', 'Top 5 Languages in 2010'); // set chart title: title, subtitle(optional)
		$this->highcharts->set_axis_titles('language', 'population'); // axis titles: x axis,  y axis
		
		$this->highcharts->set_xAxis($graph_data['axis']); // pushing categories for x axis labels
		$this->highcharts->set_serie($graph_data['users']); // the first serie
		$this->highcharts->set_serie($graph_data['popul']); // second serie
		
		// we can user credits option to make a link to the source article. 
		// it's possible to pass an object instead of array (but object will be converted to array by the lib)
// 		$credits->href = 'http://www.internetworldstats.com/stats7.htm';
// 		$credits->text = "Article on Internet Wold Stats";
// 		$this->highcharts->set_credits($credits);
		
		$this->highcharts->render_to('my_div'); // choose a specific div to render to graph
		
		$data['charts'] = $this->highcharts->render(); // we render js and div in same time
		$this->load->view('charts', $data);
	}
	
	/**
	 * template function.
	 * Load basic graph structure form template located in config file
	 */
	function template()
	{
		$graph_data = $this->_data();

		$this->load->library('highcharts');
		$this->highcharts
			->initialize('chart_template') // load template	
			->push_xAxis($graph_data['axis']) // we use push to not override template config
			->set_serie($graph_data['users'])
			->set_serie($graph_data['popul'], 'Another description'); // ovverride serie name 
		
		// we want to display the second serie as sline. First parameter is the serie name
		$this->highcharts->set_serie_options(array('type' => 'spline'), 'Another description');
		
		$data['charts'] = $this->highcharts->render();
		$this->load->view('charts', $data);
	}
	
	/**
	 * active_record function.
	 * Example by passing data from AR result() (not result_array())
	 * 
	 * @access public
	 * @return void
	 */
	function active_record()
	{
		$result = $this->_ar_data();
		
		// set data for conversion
		$dat1['x_labels'] 	= 'contries'; // optionnal, set axis categories from result row
		$dat1['series'] 	= array('users', 'population'); // set values to create series, values are result rows
		$dat1['data']		= $result;
		
		// just made some changes to display only one serie with custom name
		$dat2 = $dat1;
		$dat2['series'] = array('custom name' => 'users');
		
		$this->load->library('highcharts');
		
		// displaying muli graphs
		$this->highcharts->from_result($dat1)->add(); // first graph: add() register the graph
		$this->highcharts
			->initialize('chart_template')
			->set_dimensions('', 200) // dimension: width, height
			->from_result($dat2)
			->add(); // second graph
		
		$data['charts'] = $this->highcharts->render();
		$this->load->view('charts', $data);
	}
	
	/**
	 * data_get function.
	* Output data as array on json string
	 */
	function data_get()
	{
		$this->load->library('highcharts');
		
		// some data series
		$serie['data'] = array(20, 45, 60, 22, 6, 36);
		$this->highcharts->set_serie($serie);
		
		$data['array']  = $this->highcharts->get_array();
		$data['json']   = $this->highcharts->get(); // false to keep data (dont clear current options)
		$this->load->view('charts', $data);

	}
	
	/**
	 * pie function.
	 * Draw a Pie, and run javascript callback functions
	 * 
	 * @access public
	 * @return void
	 */
	function pie()
	{
		$this->load->library('highcharts');
		$serie['data']	= array(
			array('value one', 20), 
			array('value two', 45), 
			array('other value', 60)
		);
		$callback = "function() { return '<b>'+ this.point.name +'</b>: '+ this.y +' %'}";
		
		$tool->formatter = $callback;
		$plot->pie->dataLabels->formatter = $callback;
		
		$this->highcharts
			->set_type('pie')
			->set_serie($serie)
			->set_tooltip($tool)
			->set_plotOptions($plot);
		
		$data['charts'] = $this->highcharts->render();
		$this->load->view('charts', $data);
	}
	
	
	// HELPERS FUNCTIONS
	/**
	 * _data function.
	 * data for examples
	 */
	function _data()
	{
		$data['users']['data'] = array(536564837, 444948013, 153309074, 99143700, 82548200);
		$data['users']['name'] = 'Users by Language';
		$data['popul']['data'] = array(1277528133, 1365524982, 420469703, 126804433, 250372925);
		$data['popul']['name'] = 'World Population';
		$data['axis']['categories'] = array('English', 'Chinese', 'Spanish', 'Japanese', 'Portuguese');
		
		return $data;
	}
	
	/**
	 * _ar_data function.
	 * simulate Active Record result
	 */
	function _ar_data()
	{
		$data = $this->_data();
		foreach ($data['users']['data'] as $key => $val)
		{
			$output[] = (object)array(
				'users' 		=> $val,
				'population'	=> $data['popul']['data'][$key],
				'contries'		=> $data['axis']['categories'][$key]
			);
		}
		return $output;
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */