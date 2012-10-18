Highchart library for Codeigniter
=================================

What is Highcharts?
-------------------
Highcharts is a charting library written in pure JavaScript, offering an easy way of adding interactive charts to your web site or web application. Highcharts currently supports line, spline, area, areaspline, column, bar, pie and scatter chart types.

About this library
------------------
Note: This library is still in early development stage. Any bug report and/or ideas/improvements are welcome.

Complete documentation will be available soon.

Features:

* generate charts from Active Record result
* use template(s) from config file(s)
* share global options for your charts
* generate multiples charts in one shoot
* output embedable js/html, json string or array
* magic set/unset/push methods for all first level highcharts options object.


Magic methods
------------------

All first level highcharts options can be "set/push/unset", so if the API is improved, new methods will be available.
Methods parameters should be arrays or object

**ie :**

`$this->highcharts->set_global(array('useUTC' => false))`


Native methods
------------------

* set_title('title', 'subtitle')
* set_axis_titles('x_title_text', 'y_title_text')
* render_to('div_id') (without '#')
* set_type('bar or area or…')
* set_dimensions('width', 'height')
* set_serie((array)options, 'serie_name')
* set_serie_options((array)options, 'serie_name')
* push_serie_data('value', 'serie_name')
* push_categorie('value', 'axis'(x or y))
* from_result((array)options)
* add()
* get((bool))
* get_array((bool))
* render()
* clear((bool))

take a look to the source code for more informations


TODO
------------------

* doc
* implement choice of jquery or mootools for domready
* generate result form html table lib ?
* more shorcut methods



Highcharts infos
------------------
**Highcharts - Interactive JavaScript charts for your web pages:**

[download highcharts](http://www.highcharts.com)

[API reference](http://www.highcharts.com/ref)


