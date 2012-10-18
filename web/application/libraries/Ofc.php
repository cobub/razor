<?php

// Pretty print some JSON
function json_format($json)
{
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;

/*
 commented out by monk.e.boy 22nd May '08
 because my web server is PHP4, and
 json_* are PHP5 functions...

    $json_obj = json_decode($json);

    if($json_obj === false)
        return false;

    $json = json_encode($json_obj);
*/
    $len = strlen($json);

    for($c = 0; $c < $len; $c++)
    {
        $char = $json[$c];
        switch($char)
        {
            case '{':
            case '[':
                if(!$in_string)
                {
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
                    $indent_level++;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '}':
            case ']':
                if(!$in_string)
                {
                    $indent_level--;
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ',':
                if(!$in_string)
                {
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ':':
                if(!$in_string)
                {
                    $new_json .= ": ";
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '"':
                if($c > 0 && $json[$c-1] != '\\')
                {
                    $in_string = !$in_string;
                }
            default:
                $new_json .= $char;
                break;
        }
    }

    return $new_json;
}

/**
 * Set the title of a chart, make one of these and pass it into
 * open_flash_chart set_title
 */
class title
{
    function title( $text='' )
    {
        $this->text = $text;
    }

    /**
     * A css string. Can optionally contain:
     * - font-size
     * - font-family
     * - font-weight
     * - color
     * - background-color
     * - text-align
     * - margin
     * - margin-left
     * - margin-right
     * - margin-top
     * - margin-bottom
     * - padding
     * - padding-left
     * - padding-right
     * - padding-top
     * - padding-bottom
     * just like the css we use all the time :-)
     */
    function set_style( $css )
    {
        $this->style = $css;
        //"{font-size: 20px; color:#0000ff; font-family: Verdana; text-align: center;}";
    }
}

class y_axis_base
{
    function y_axis_base(){}

    /**
     * @param $s as integer, thickness of the Y axis line
     */
    function set_stroke( $s )
    {
        $this->stroke = $s;
    }

    /**
     * @param $val as integer. The length of the ticks in pixels
     */
    function set_tick_length( $val )
    {
        $tmp = 'tick-length';
        $this->$tmp = $val;
    }

    function set_colours( $colour, $grid_colour )
    {
        $this->set_colour( $colour );
        $this->set_grid_colour( $grid_colour );
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_grid_colour( $colour )
    {
        $tmp = 'grid-colour';
        $this->$tmp = $colour;
    }

    /**
     * Set min and max values, also (optionally) set the steps value.
     * You can reverse the chart by setting min larger than max, e.g. min = 10
     * and max = 0.
     *
     * @param $min as integer
     * @param $max as integer
     * @param $steps as integer.
     */
    function set_range( $min, $max, $steps=1 )
    {
        $this->min = $min;
        $this->max = $max;
        $this->set_steps( $steps );
    }

    /**
     * Sugar for set_range
     */
    function range( $min, $max, $steps=1 )
    {
        $this->set_range( $min, $max, $steps );
        return $this;
    }

    /**
     * @param $off as Boolean. If true the Y axis is nudged up half a step.
     */
    function set_offset( $off )
    {
        $this->offset = $off?1:0;
    }

    /**
     * @param $y_axis_labels as an y_axis_labels object
     * Use this to customize the labels (colour, font, etc...)
     */
    function set_labels( $y_axis_labels )
    {
        $this->labels = $y_axis_labels;
    }

    /**
     * Pass in some text for each label. This can contain magic variables "#val#" which
     * will get replaced with the value for that Y axis label. Useful for:
     * - "£#val#"
     * - "#val#%"
     * - "#val# million"
     *
     * @param $text as string.
     */
    function set_label_text( $text )
    {
        $tmp = new y_axis_labels();
        $tmp->set_text( $text );
        $this->labels = $tmp;
    }

    /**
     * @param $steps as integer.
     *
     * Only show every $steps label, e.g. every 10th
     */
    function set_steps( $steps )
    {
        $this->steps = $steps;
    }

    /**
     * Make the labels show vertical
     */
    function set_vertical()
    {
        $this->rotate = "vertical";
    }
}

class y_axis extends y_axis_base
{
    function y_axis(){}

    /**
     * @param $colour as string. The grid are the lines inside the chart.
     * HEX colour, e.g. '#ff0000'
     */
    function set_grid_colour( $colour )
    {
        $tmp = 'grid-colour';
        $this->$tmp = $colour;
    }

}

class y_axis_right extends y_axis_base
{
    function y_axis_right(){}
}

class y_axis_labels
{
    function y_axis_labels(){}

    /**
     * @param $steps which labels are generated
     */
    function set_steps( $steps )
    {
        $this->steps = $steps;
    }

    /**
     *
     * @param $labels as an array of [y_axis_label or string]
     */
    function set_labels( $labels )
    {
        $this->labels = $labels;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    /**
     * font size in pixels
     */
    function set_size( $size )
    {
        $this->size = $size;
    }

    /**
     * rotate labels
     */
    function set_vertical()
    {
        $this->rotate = 270;
    }

    function rotate( $angle )
    {
        $this->rotate = $angle;
    }

    /**
     * @param $text default text that all labels inherit
     */
    function set_text( $text )
    {
        $this->text = $text;
    }
}



class y_legend
{
    function y_legend( $text='' )
    {
        $this->text = $text;
    }

    function set_style( $css )
    {
        $this->style = $css;
        //"{font-size: 20px; color:#0000ff; font-family: Verdana; text-align: center;}";
    }
}

class x_axis
{
    function x_axis(){}

    /**
     * @param $stroke as integer, with of the line and ticks
     */
    function set_stroke( $stroke )
    {
        $this->stroke = $stroke;
    }

    function stroke( $stroke )
    {
        $this->set_stroke( $stroke );
        return $this;
    }

    /**
     *@param $colour as string HEX colour
     *@param $grid_colour as string HEX colour
     */
    function set_colours( $colour, $grid_colour )
    {
        $this->set_colour( $colour );
        $this->set_grid_colour( $grid_colour );
    }

    /**
     *@param $colour as string HEX colour
     */
    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function colour( $colour )
    {
        $this->set_colour($colour);
        return $this;
    }

    function set_tick_height( $height )
    {
        $tmp = 'tick-height';
        $this->$tmp             = $height;
    }

    function tick_height( $height )
    {
        $this->set_tick_height($height);
        return $this;
    }

    function set_grid_colour( $colour )
    {
        $tmp = 'grid-colour';
        $this->$tmp = $colour;
    }

    function grid_colour( $colour )
    {
        $this->set_grid_colour($colour);
        return $this;
    }

    /**
     * @param $o is a boolean. If true, the X axis start half a step in
     * This defaults to True
     */
    function set_offset( $o )
    {
        $this->offset = $o?true:false;
    }

    function offset( $o )
    {
        $this->set_offset($o);
        return $this;
    }

    /**
     * @param $steps as integer. Which grid lines and ticks are visible.
     */
    function set_steps( $steps )
    {
        $this->steps = $steps;
    }

    function steps( $steps )
    {
        $this->set_steps($steps);
        return $this;
    }

    /**
     * @param $val as an integer, the height in pixels of the 3D bar. Mostly
     * used for the 3D bar chart.
     */
    function set_3d( $val )
    {
        $tmp = '3d';
        $this->$tmp             = $val;
    }

    /**
     * @param $x_axis_labels as an x_axis_labels object
     * Use this to customize the labels (colour, font, etc...)
     */
    function set_labels( $x_axis_labels )
    {
        //$this->labels = $v;
        $this->labels = $x_axis_labels;
    }

    /**
     * Sugar syntax: helper function to make the examples simpler.
     * @param $a is an array of labels
     */
    function set_labels_from_array( $a )
    {
        $x_axis_labels = new x_axis_labels();
        $x_axis_labels->set_labels( $a );
        $this->labels = $x_axis_labels;

        if( isset( $this->steps ) )
            $x_axis_labels->set_steps( $this->steps );
    }

    /**
     * min and max.
     */
    function set_range( $min, $max )
    {
        $this->min = $min;
        $this->max = $max;
    }
}
class pie_value
{
    function pie_value( $value, $label )
    {
        $this->value = $value;
        $this->label = $label;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_label( $label, $label_colour, $font_size )
    {
        $this->label = $label;

        $tmp = 'label-colour';
        $this->$tmp = $label_colour;

        $tmp = 'font-size';
        $this->$tmp = $font_size;

    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }

    function on_click( $event )
    {
        $tmp = 'on-click';
        $this->$tmp = $event;
    }


    /**
     * An object that inherits from base_pie_animation
     */
    function add_animation( $animation )
    {
        if( !isset( $this->animate ) )
            $this->animate = array();

        $this->animate[] = $animation;

        return $this;
    }
}

class base_pie_animation{}

/**
 * fade the pie slice from $alpha (pie set_alpha) to 100% opaque.
 */
class pie_fade extends base_pie_animation
{
    function pie_fade()
    {
        $this->type="fade";
    }
}

/**
 * Bounce the pie slice out a little
 */
class pie_bounce extends base_pie_animation
{
    /**
     * @param $distance as integer, distance to bounce in pixels
     */
    function pie_bounce( $distance )
    {
        $this->type="bounce";
        $this->distance = $distance;
    }
}

/**
 * Make a pie chart and fill it with pie slices
 */
class pie
{
    function pie()
    {
        $this->type             = 'pie';
    }

    function set_colours( $colours )
    {
        $this->colours = $colours;
    }

    /**
     * Sugar wrapped around set_colours
     */
    function colours( $colours )
    {
        $this->set_colours( $colours );
        return $this;
    }

    /**
     * @param $alpha as float (0-1) 0.75 = 3/4 visible
     */
    function set_alpha( $alpha )
    {
        $this->alpha = $alpha;
    }

    /**
     *sugar wrapped set_alpha
     **/
    function alpha( $alpha )
    {
        $this->set_alpha( $alpha );
        return $this;
    }

    /**
     * @param $v as array containing one of
     *  - null
     *  - real or integer number
     *  - a pie_value object
     */
    function set_values( $v )
    {
        $this->values = $v;
    }

    /**
     * sugar for set_values
     */
    function values( $v )
    {
        $this->set_values( $v );
        return $this;
    }

    /**
     * HACK to keep old code working.
     */
    function set_animate( $bool )
    {
        if( $bool )
            $this->add_animation( new pie_fade() );

    }

    /**
     * An object that inherits from base_pie_animation
     */
    function add_animation( $animation )
    {
        if( !isset( $this->animate ) )
            $this->animate = array();

        $this->animate[] = $animation;

        return $this;
    }

    /**
     * @param $angle as real number
     */
    function set_start_angle( $angle )
    {
        $tmp = 'start-angle';
        $this->$tmp = $angle;
    }

    /**
     * sugar for set_start_angle
     */
    function start_angle($angle)
    {
        $this->set_start_angle( $angle );
        return $this;
    }

    /**
     * @param $tip as string. The tooltip text. May contain magic varibles
     */
    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }

    /**
     * sugar for set_tooltip
     */
    function tooltip( $tip )
    {
        $this->set_tooltip( $tip );
        return $this;
    }

    function set_gradient_fill()
    {
        $tmp = 'gradient-fill';
        $this->$tmp = true;
    }

    function gradient_fill()
    {
        $this->set_gradient_fill();
        return $this;
    }

    /**
     * By default each label is the same colour as the slice,
     * but you can ovveride that behaviour using this method.
     *
     * @param $label_colour as string HEX colour;
     */
    function set_label_colour( $label_colour )
    {
        $tmp = 'label-colour';
        $this->$tmp = $label_colour;
    }

    function label_colour( $label_colour )
    {
        $this->set_label_colour( $label_colour );
        return $this;
    }

    /**
     * Turn off the labels
     */
    function set_no_labels()
    {
        $tmp = 'no-labels';
        $this->$tmp = true;
    }

    function on_click( $event )
    {
        $tmp = 'on-click';
        $this->$tmp = $event;
    }

    /**
     * Fix the radius of the pie chart. Take a look at the magic variable #radius#
     * for helping figure out what radius to set it to.
     *
     * @param $radius as number
     */
    function radius( $radius )
    {
        $this->radius = $radius;
        return $this;
    }
}
/* this is a base class */

class bar_base
{
    function bar_base(){}

    /**
     * @param $text as string the key text
     * @param $size as integer, size in pixels
     */
    function set_key( $text, $size )
    {
        $this->text = $text;
        $tmp = 'font-size';
        $this->$tmp = $size;
    }

    /**
     * syntatical sugar.
     */
    function key( $text, $size )
    {
        $this->set_key( $text, $size );
    }

    /**
     * @param $v as an array, a mix of:
     *  - a bar_value class. You can use this to customise the paramters of each bar.
     *  - integer. This is the Y position of the top of the bar.
     */
    function set_values( $v )
    {
        $this->values = $v;
    }

    /**
     * see set_values
     */
    function append_value( $v )
    {
        $this->values[] = $v;
    }

    /**
     * @param $colour as string, a HEX colour, e.g. '#ff0000' red
     */
    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    /**
     *syntatical sugar
     */
    function colour( $colour )
    {
        $this->set_colour( $colour );
    }

    /**
     * @param $alpha as real number (range 0 to 1), e.g. 0.5 is half transparent
     */
    function set_alpha( $alpha )
    {
        $this->alpha = $alpha;
    }

    /**
     * @param $tip as string, the tip to show. May contain various magic variables.
     */
    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }

    /**
     *@param $on_show as line_on_show object
     */
    function set_on_show($on_show)
    {
        $this->{'on-show'} = $on_show;
    }

    function set_on_click( $text )
    {
        $tmp = 'on-click';
        $this->$tmp = $text;
    }
}

class bar_on_show
{
    /**
     *@param $type as string. Can be any one of:
     * - 'pop-up'
     * - 'drop'
     * - 'fade-in'
     * - 'grow-up'
     * - 'grow-down'
     * - 'pop'
     *
     * @param $cascade as float. Cascade in seconds
     * @param $delay as float. Delay before animation starts in seconds.
     */
    function __construct($type, $cascade, $delay)
    {
        $this->type = $type;
        $this->cascade = (float)$cascade;
        $this->delay = (float)$delay;
    }
}

class bar_value
{
    /**
     * @param $top as integer. The Y value of the top of the bar
     * @param OPTIONAL $bottom as integer. The Y value of the bottom of the bar, defaults to Y min.
     */
    function bar_value( $top, $bottom=null )
    {
        $this->top = $top;

        if( isset( $bottom ) )
            $this->bottom = $bottom;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }
}

class bar extends bar_base
{
    function bar()
    {
        $this->type      = "bar";
        parent::bar_base();
    }
}

class bar_glass extends bar_base
{
    function bar_glass()
    {
        $this->type      = "bar_glass";
        parent::bar_base();
    }
}

class bar_cylinder extends bar_base
{
    function bar_cylinder()
    {
        $this->type      = "bar_cylinder";
        parent::bar_base();
    }
}

class bar_cylinder_outline extends bar_base
{
    function bar_cylinder_outline()
    {
        $this->type      = "bar_cylinder_outline";
        parent::bar_base();
    }
}

class bar_rounded_glass extends bar_base
{
    function bar_rounded_glass()
    {
        $this->type      = "bar_round_glass";
        parent::bar_base();
    }
}

class bar_round extends bar_base
{
    function bar_round()
    {
        $this->type      = "bar_round";
        parent::bar_base();
    }
}

class bar_dome extends bar_base
{
    function bar_dome()
    {
        $this->type      = "bar_dome";
        parent::bar_base();
    }
}

class bar_round3d extends bar_base
{
    function bar_round3d()
    {
        $this->type      = "bar_round3d";
        parent::bar_base();
    }
}

class bar_3d extends bar_base
{
    function bar_3d()
    {
        $this->type      = "bar_3d";
        parent::bar_base();
    }
}
class bar_filled_value extends bar_value
{
    function bar_filled_value( $top, $bottom=null )
    {
        parent::bar_value( $top, $bottom );
    }

    function set_outline_colour( $outline_colour )
    {
        $tmp = 'outline-colour';
        $this->$tmp = $outline_colour;
    }
}

class bar_filled extends bar_base
{
    function bar_filled( $colour=null, $outline_colour=null )
    {
        $this->type      = "bar_filled";
        parent::bar_base();

        if( isset( $colour ) )
            $this->set_colour( $colour );

        if( isset( $outline_colour ) )
            $this->set_outline_colour( $outline_colour );
    }

    function set_outline_colour( $outline_colour )
    {
        $tmp = 'outline-colour';
        $this->$tmp = $outline_colour;
    }
}

class bar_stack extends bar_base
{
    function bar_stack()
    {
        $this->type      = "bar_stack";
        parent::bar_base();
    }

    function append_stack( $v )
    {
        $this->append_value( $v );
    }

    // an array of HEX colours strings
    // e.g. array( '#ff0000', '#00ff00' );
    function set_colours( $colours )
    {
        $this->colours = $colours;
    }

    // an array of bar_stack_value
    function set_keys( $keys )
    {
        $this->keys = $keys;
    }
}

class bar_stack_value
{
    function bar_stack_value( $val, $colour )
    {
        $this->val = $val;
        $this->colour = $colour;
    }
}

class bar_stack_key
{
    function bar_stack_key( $colour, $text, $font_size )
    {
        $this->colour = $colour;
        $this->text = $text;
        $tmp = 'font-size';
        $this->$tmp = $font_size;
    }
}
class hbar_value
{
    function hbar_value( $left, $right=null )
    {
        if( isset( $right ) )
        {
            $this->left = $left;
            $this->right = $right;
        }
        else
            $this->right = $left;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }
}

class hbar
{
    function hbar( $colour )
    {
        $this->type      = "hbar";
        $this->values    = array();
        $this->set_colour( $colour );
    }

    function append_value( $v )
    {
        $this->values[] = $v;
    }

    function set_values( $v )
    {
        foreach( $v as $val )
            $this->append_value( new hbar_value( $val ) );
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_key( $text, $size )
    {
        $this->text = $text;
        $tmp = 'font-size';
        $this->$tmp = $size;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }
}


class line_base
{
    function line_base()
    {
        $this->type      = "line";
        $this->text      = "Page views";
        $tmp = 'font-size';
        $this->$tmp = 10;

        $this->values    = array();
    }

    function set_values( $v )
    {
        $this->values = $v;
    }

    /**
     * Append a value to the line.
     *
     * @param mixed $v
     */
    function append_value($v)
    {
        $this->values[] = $v;
    }

    function set_width( $width )
    {
        $this->width = $width;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_dot_size( $size )
    {
        $tmp = 'dot-size';
        $this->$tmp = $size;
    }

    function set_halo_size( $size )
    {
        $tmp = 'halo-size';
        $this->$tmp = $size;
    }

    function set_key( $text, $font_size )
    {
        $this->text      = $text;
        $tmp = 'font-size';
        $this->$tmp = $font_size;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }

    function set_on_click( $text )
    {
        $tmp = 'on-click';
        $this->$tmp = $text;
    }

    function loop()
    {
        $this->loop = true;
    }

    function line_style( $s )
    {
        $tmp = "line-style";
        $this->$tmp = $s;
    }

        /**
     * Sets the text for the line.
     *
     * @param string $text
     */
    function set_text($text)
    {
        $this->text = $text;
    }


}
class line_on_show
{
    /**
     *@param $type as string. Can be any one of:
     * - 'pop-up'
     * - 'explode'
     * - 'mid-slide'
     * - 'drop'
     * - 'fade-in'
     * - 'shrink-in'
     *
     * @param $cascade as float. Cascade in seconds
     * @param $delay as float. Delay before animation starts in seconds.
     */
    function __construct($type, $cascade, $delay)
    {
        $this->type = $type;
        $this->cascade = (float)$cascade;
        $this->delay = (float)$delay;
    }
}

class line
{
    function line()
    {
        $this->type      = "line";
        $this->values    = array();
    }

    /**
     * Set the default dot that all the real
     * dots inherit their properties from. If you set the
     * default dot to be red, all values in your chart that
     * do not specify a colour will be red. Same for all the
     * other attributes such as tooltip, on-click, size etc...
     *
     * @param $style as any class that inherits base_dot
     */
    function set_default_dot_style( $style )
    {
        $tmp = 'dot-style';
        $this->$tmp = $style;
    }

    /**
     * @param $v as array, can contain any combination of:
     *  - integer, Y position of the point
     *  - any class that inherits from dot_base
     *  - <b>null</b>
     */
    function set_values( $v )
    {
        $this->values = $v;
    }

    /**
     * Append a value to the line.
     *
     * @param mixed $v
     */
    function append_value($v)
    {
        $this->values[] = $v;
    }

    function set_width( $width )
    {
        $this->width = $width;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    /**
     * sytnatical sugar for set_colour
     */
    function colour( $colour )
    {
        $this->set_colour( $colour );
        return $this;
    }

    function set_halo_size( $size )
    {
        $tmp = 'halo-size';
        $this->$tmp = $size;
    }

    function set_key( $text, $font_size )
    {
        $this->text      = $text;
        $tmp = 'font-size';
        $this->$tmp = $font_size;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }

    /**
     * @param $text as string. A javascript function name as a string. The chart will
     * try to call this function, it will pass the chart id as the only parameter into
     * this function. E.g:
     *
     */
    function set_on_click( $text )
    {
        $tmp = 'on-click';
        $this->$tmp = $text;
    }

    function loop()
    {
        $this->loop = true;
    }

    function line_style( $s )
    {
        $tmp = "line-style";
        $this->$tmp = $s;
    }

        /**
     * Sets the text for the line.
     *
     * @param string $text
     */
    function set_text($text)
    {
        $this->text = $text;
    }

    function attach_to_right_y_axis()
    {
        $this->axis = 'right';
    }

    /**
     *@param $on_show as line_on_show object
     */
    function set_on_show($on_show)
    {
        $this->{'on-show'} = $on_show;
    }

    function on_show($on_show)
    {
        $this->set_on_show($on_show);
        return $this;
    }
}
class candle_value
{
    /**
     *
     */
    function candle_value( $high, $open, $close, $low )
    {
        $this->high = $high;
        $this->top = $open;
        $this->bottom = $close;
        $this->low = $low;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_tooltip( $tip )
    {
        $this->tip = $tip;
    }
}

class candle extends bar_base
{
    function candle($colour)
    {
        $this->type      = "candle";
        parent::bar_base();

        $this->set_colour( $colour );
    }
}


/**
 * inherits from line
 */
class area extends line
{
    function area()
    {
        $this->type      = "area";
    }

    /**
     * the fill colour
     */
    function set_fill_colour( $colour )
    {
        $this->fill = $colour;
    }

    /**
     * sugar: see set_fill_colour
     */
    function fill_colour( $colour )
    {
        $this->set_fill_colour( $colour );
        return $this;
    }

    function set_fill_alpha( $alpha )
    {
        $tmp = "fill-alpha";
        $this->$tmp = $alpha;
    }

    function set_loop()
    {
        $this->loop = true;
    }
}


class x_legend
{
    function x_legend( $text='' )
    {
        $this->text = $text;
    }

    function set_style( $css )
    {
        $this->style = $css;
        //"{font-size: 20px; color:#0000ff; font-family: Verdana; text-align: center;}";
    }
}


class bar_sketch extends bar_base
{
    /**
     * @param $colour as string, HEX colour e.g. '#00ff00'
     * @param $outline_colour as string, HEX colour e.g. '#ff0000'
     * @param $fun_factor as integer, range 0 to 10. 0,1 and 2 are pretty boring.
     * 4 to 6 is a bit fun, 7 and above is lots of fun.
     */
    function bar_sketch( $colour, $outline_colour, $fun_factor )
    {
        $this->type      = "bar_sketch";
        parent::bar_base();

        $this->set_colour( $colour );
        $this->set_outline_colour( $outline_colour );
        $this->offset = $fun_factor;
    }

    function set_outline_colour( $outline_colour )
    {
        $tmp = 'outline-colour';
        $this->$tmp = $outline_colour;
    }
}

class scatter_value
{
    function scatter_value( $x, $y, $dot_size=-1 )
    {
        $this->x = $x;
        $this->y = $y;

        if( $dot_size > 0 )
        {
            $tmp = 'dot-size';
            $this->$tmp = $dot_size;
        }
    }
}

class scatter
{
    function scatter( $colour )
    {
        $this->type      = "scatter";
        $this->set_colour( $colour );
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_default_dot_style( $style )
    {
        $tmp = 'dot-style';
        $this->$tmp = $style;
    }

    /**
     * @param $v as array, can contain any combination of:
     *  - integer, Y position of the point
     *  - any class that inherits from scatter_value
     *  - <b>null</b>
     */
    function set_values( $values )
    {
        $this->values = $values;
    }
}

class scatter_line
{
    function scatter_line( $colour, $width  )
    {
        $this->type      = "scatter_line";
        $this->set_colour( $colour );
        $this->set_width( $width );
    }

    function set_default_dot_style( $style )
    {
        $tmp = 'dot-style';
        $this->$tmp = $style;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_width( $width )
    {
        $this->width = $width;
    }

    function set_values( $values )
    {
        $this->values = $values;
    }

    function set_step_horizontal()
    {
        $this->stepgraph = 'horizontal';
    }

    function set_step_vertical()
    {
        $this->stepgraph = 'vertical';
    }

    function set_key( $text, $font_size )
    {
        $this->text      = $text;
        $tmp = 'font-size';
        $this->$tmp = $font_size;
    }
}
class x_axis_labels
{
    function x_axis_labels(){}

    /**
     * @param $steps which labels are generated
     */
    function set_steps( $steps )
    {
        $this->steps = $steps;
    }

    /**
     * @param $steps as integer which labels are visible
     */
    function visible_steps( $steps )
    {
        $this->{"visible-steps"} = $steps;
        return $this;
    }

    /**
     *
     * @param $labels as an array of [x_axis_label or string]
     */
    function set_labels( $labels )
    {
        $this->labels = $labels;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    /**
     * font size in pixels
     */
    function set_size( $size )
    {
        $this->size = $size;
    }

    /**
     * rotate labels
     */
    function set_vertical()
    {
        $this->rotate = 270;
    }

    /**
     * @param @angle as real. The angle of the text.
     */
    function rotate( $angle )
    {
        $this->rotate = $angle;
    }

    /**
     * @param $text as string. Replace and magic variables with actual x axis position.
     */
    function text( $text )
    {
        $this->text = $text;
    }
}
/**
 * x_axis_label see x_axis_labels
 */
class x_axis_label
{
    function x_axis_label( $text, $colour, $size, $rotate )
    {
        $this->set_text( $text );
        $this->set_colour( $colour );
        $this->set_size( $size );
        $this->set_rotate( $rotate );
    }

    function set_text( $text )
    {
        $this->text = $text;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_size( $size )
    {
        $this->size = $size;
    }

    function set_rotate( $rotate )
    {
        $this->rotate = $rotate;
    }

    function set_vertical()
    {
        $this->rotate = "vertical";
    }

    function set_visible()
    {
        $this->visible = true;
    }
}

class tooltip
{
    function tooltip(){}

    /**
     * @param $shadow as boolean. Enable drop shadow.
     */
    function set_shadow( $shadow )
    {
        $this->shadow = $shadow;
    }

    /**
     * @param $stroke as integer, border width in pixels (e.g. 5 )
     */
    function set_stroke( $stroke )
    {
        $this->stroke = $stroke;
    }

    /**
     * @param $colour as string, HEX colour e.g. '#0000ff'
     */
    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    /**
     * @param $bg as string, HEX colour e.g. '#0000ff'
     */
    function set_background_colour( $bg )
    {
        $this->background = $bg;
    }

    /**
     * @param $style as string. A css style.
     */
    function set_title_style( $style )
    {
        $this->title = $style;
    }

    /**
     * @param $style as string. A css style.
     */
    function set_body_style( $style )
    {
        $this->body = $style;
    }

    function set_proximity()
    {
        $this->mouse = 1;
    }

    function set_hover()
    {
        $this->mouse = 2;
    }
}

class shape_point
{
    function shape_point( $x, $y )
    {
        $this->x = $x;
        $this->y = $y;
    }
}

class shape
{
    function shape( $colour )
    {
        $this->type     = "shape";
        $this->colour   = $colour;
        $this->values   = array();
    }

    function append_value( $p )
    {
        $this->values[] = $p;
    }
}

class radar_axis
{
    function radar_axis( $max )
    {
        $this->set_max( $max );
    }

    function set_max( $max )
    {
        $this->max = $max;
    }

    function set_steps( $steps )
    {
        $this->steps = $steps;
    }

    function set_stroke( $s )
    {
        $this->stroke = $s;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }

    function set_grid_colour( $colour )
    {
        $tmp = 'grid-colour';
        $this->$tmp = $colour;
    }

    function set_labels( $labels )
    {
        $this->labels = $labels;
    }

    function set_spoke_labels( $labels )
    {
        $tmp = 'spoke-labels';
        $this->$tmp = $labels;
    }
}


class radar_axis_labels
{
    // $labels : array
    function radar_axis_labels( $labels )
    {
        $this->labels = $labels;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }
}

class radar_spoke_labels
{
    // $labels : array
    function radar_spoke_labels( $labels )
    {
        $this->labels = $labels;
    }

    function set_colour( $colour )
    {
        $this->colour = $colour;
    }
}

class line_style
{
    function line_style($on, $off)
    {
        $this->style    = "dash";
        $this->on       = $on;
        $this->off      = $off;
    }
}
/**
 * A private class. All the other line-dots inherit from this.
 * Gives them all some common methods.
 */
class dot_base
{
    /**
     * @param $type string
     * @param $value integer
     */
    function dot_base($type, $value=null)
    {
        $this->type = $type;
        if( isset( $value ) )
            $this->value( $value );
    }

    /**
     * For line charts that only require a Y position
     * for each point.
     * @param $value as integer, the Y position
     */
    function value( $value )
    {
        $this->value = $value;
    }

    /**
     * For scatter charts that require an X and Y position for
     * each point.
     *
     * @param $x as integer
     * @param $y as integer
     */
    function position( $x, $y )
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param $colour is a string, HEX colour, e.g. '#FF0000' red
     */
    function colour($colour)
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * The tooltip for this dot.
     */
    function tooltip( $tip )
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * @param $size is an integer. Size of the dot.
     */
    function size($size)
    {
        $tmp = 'dot-size';
        $this->$tmp = $size;
        return $this;
    }

    /**
     * a private method
     */
    function type( $type )
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $size is an integer. The size of the hollow 'halo' around the dot that masks the line.
     */
    function halo_size( $size )
    {
        $tmp = 'halo-size';
        $this->$tmp = $size;
        return $this;
    }

    /**
     * @param $do as string. One of three options (examples):
     *  - "http://example.com" - browse to this URL
     *  - "https://example.com" - browse to this URL
     *  - "trace:message" - print this message in the FlashDevelop debug pane
     *  - all other strings will be called as Javascript functions, so a string "hello_world"
     *  will call the JS function "hello_world(index)". It passes in the index of the
     *  point.
     */
    function on_click( $do )
    {
        $tmp = 'on-click';
        $this->$tmp = $do;
    }
}

/**
 * Draw a hollow dot
 */
class hollow_dot extends dot_base
{
    function hollow_dot($value=null)
    {
        parent::dot_base( 'hollow-dot', $value );
    }
}

/**
 * Draw a star
 */
class star extends dot_base
{
    /**
     * The constructor, takes an optional $value
     */
    function star($value=null)
    {
        parent::dot_base( 'star', $value );
    }

    /**
     * @param $angle is an integer.
     */
    function rotation($angle)
    {
        $this->rotation = $angle;
        return $this;
    }

    /**
     * @param $is_hollow is a boolean.
     */
    function hollow($is_hollow)
    {
        $this->hollow = $is_hollow;
    }
}

/**
 * Draw a 'bow tie' shape.
 */
class bow extends dot_base
{
    /**
     * The constructor, takes an optional $value
     */
    function bow($value=null)
    {
        parent::dot_base( 'bow', $value );
    }

    /**
     * Rotate the anchor object.
     * @param $angle is an integer.
     */
    function rotation($angle)
    {
        $this->rotation = $angle;
        return $this;
    }
}

/**
 * An <i><b>n</b></i> sided shape.
 */
class anchor extends dot_base
{
    /**
     * The constructor, takes an optional $value
     */
    function anchor($value=null)
    {
        parent::dot_base( 'anchor', $value );
    }

    /**
     * Rotate the anchor object.
     * @param $angle is an integer.
     */
    function rotation($angle)
    {
        $this->rotation = $angle;
        return $this;
    }

    /**
     * @param $sides is an integer. Number of sides this shape has.
     */
    function sides($sides)
    {
        $this->sides = $sides;
        return $this;
    }
}

/**
 * A simple dot
 */
class dot extends dot_base
{
    /**
     * The constructor, takes an optional $value
     */
    function dot($value=null)
    {
        parent::dot_base( 'dot', $value );
    }
}

/**
 * A simple dot
 */
class solid_dot extends dot_base
{
    /**
     * The constructor, takes an optional $value
     */
    function solid_dot($value=null)
    {
        parent::dot_base( 'solid-dot', $value );
    }
}
class ofc_menu_item
{
    /**
     * @param $text as string. The menu item text.
     * @param $javascript_function_name as string. The javascript function name, the
     * js function takes one parameter, the chart ID. See ofc_menu_item_camera for
     * some example code.
     */
    function ofc_menu_item($text, $javascript_function_name)
    {
        $this->type = "text";
        $this->text = $text;
        $tmp = 'javascript-function';
        $this->$tmp = $javascript_function_name;
    }
}

class ofc_menu_item_camera
{
    /**
     * @param $text as string. The menu item text.
     * @param $javascript_function_name as string. The javascript function name, the
     * js function takes one parameter, the chart ID. So for example, our js function
     * could look like this:
     *
     * function save_image( chart_id )
     * {
     *     alert( chart_id );
     * }
     *
     * to make a menu item call this: ofc_menu_item_camera('Save chart', 'save_image');
     */
    function ofc_menu_item_camera($text, $javascript_function_name)
    {
        $this->type = "camera-icon";
        $this->text = $text;
        $tmp = 'javascript-function';
        $this->$tmp = $javascript_function_name;
    }
}

class ofc_menu
{
    function ofc_menu($colour, $outline_colour)
    {
        $this->colour = $colour;
        $this->outline_colour = $outline_colour;
    }

    function values($values)
    {
        $this->values = $values;
    }
}

class Ofc
{
    var $title = 'Title'; //Default title
	var $data = NULL;
	var $type = 'line'; //Default chart type*/
	

	
	function ofc()
	{
		//return $this->open_flash_chart();	
	}
	
	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}
	/**
	 * Generate the chart
	 *
	 * @access	public
	 * @param	mixed
	 * @return	string
	 */
	function generate()
	{
		$this->open_flash_chart();
		$this->set_title($this->title);
		
		return $this->toPrettyString();
	}
	
	
	
	function open_flash_chart()
    {
        //$this->title = new title( "Many data lines" );
        $this->elements = array();
    }

    function set_title( $t )
    {
        $this->title = $t;
    }

    function set_x_axis( $x )
    {
        $this->x_axis = $x;
    }

    function set_y_axis( $y )
    {
        $this->y_axis = $y;
    }

    function add_y_axis( $y )
    {
        $this->y_axis = $y;
    }

    function set_y_axis_right( $y )
    {
        $this->y_axis_right = $y;
    }

    function add_element( $e )
    {
        $this->elements[] = $e;
    }

    function set_x_legend( $x )
    {
        $this->x_legend = $x;
    }

    function set_y_legend( $y )
    {
        $this->y_legend = $y;
    }

    function set_bg_colour( $colour )
    {
        $this->bg_colour = $colour;
    }
    
    function set_inner_background($background)
    {
    	$this->inner_background = $background;
    }

    function set_radar_axis( $radar )
    {
        $this->radar_axis = $radar;
    }

    function set_tooltip( $tooltip )
    {
        $this->tooltip = $tooltip;
    }

    /**
     * This is a bit funky :(
     *
     * @param $num_decimals as integer. Truncate the decimals to $num_decimals, e.g. set it
     * to 5 and 3.333333333 will display as 3.33333. 2.0 will display as 2 (or 2.00000 - see below)
     * @param $is_fixed_num_decimals_forced as boolean. If true it will pad the decimals.
     * @param $is_decimal_separator_comma as boolean
     * @param $is_thousand_separator_disabled as boolean
     *
     * This needs a bit of love and attention
     */
    function set_number_format($num_decimals, $is_fixed_num_decimals_forced, $is_decimal_separator_comma, $is_thousand_separator_disabled )
    {
        $this->num_decimals = $num_decimals;
        $this->is_fixed_num_decimals_forced = $is_fixed_num_decimals_forced;
        $this->is_decimal_separator_comma = $is_decimal_separator_comma;
        $this->is_thousand_separator_disabled = $is_thousand_separator_disabled;
    }

    /**
     * This is experimental and will change as we make it work
     *
     * @param $m as ofc_menu
     */
    function set_menu($m)
    {
        $this->menu = $m;
    }

    function toString()
    {
        if (function_exists('json_encode'))
        {
            return json_encode($this);
        }
        else
        {
            $json = new Services_JSON();
            return $json->encode( $this );
        }
    }

    function toPrettyString()
    {
        return json_format( $this->toString() );
    }

    function title($text='') {
        return new title($text);
    }
	
	/*
	*	Chart elements
	*/
   	function y_axis(){
		return new y_axis();	
	}
	function x_axis(){
		return new x_axis();	
	}
   
   
   function bar() {
        return new bar();
    }

    /*
	*	Function for the pie class
	*/
	function pie() {
        return new pie();
    }
	
	function pie_fade() {
		return new pie_fade();	
	}
	
	function pie_value($value, $label=null){
		if(is_array($value)){
			return new pie_value($value);	
		}
		return new pie_value($value,$label);	
	}
	
	
	
	/*
	*	Function for the area class
	*/
	
	function area(){
		return new area();
	}
	

	/*
	*	Function for bar class	
	*/
	function bar_cylinder(){
		return new bar_cylinder();	
	}
	function bar_cylinder_outline(){
		return new bar_cylinder_outline();
	}
	function bar_glass()
	{
		return new bar_glass();	
	}
	function bar_filled($colour=null, $outline_colour=null){
		return new bar_filled($colour, $outline_colour);	
	}
	
	function bar_sketch(){
		return new bar_sketch();	
	}
	function bar_value($value = null){
		return new bar_value($value);	
	}
	
	/*
	*	Function for line class	
	*/
	function line(){
		return new line();
	}
	
	function anchor(){
		return new anchor();	
	}
	
	function bow(){
		return new bow();	
	}
	
	function dot(){
		return new dot();	
	}
	
	function hollow_dot(){
		return new hollow_dot();	
	}
	
	function solid_dot(){
		return new solid_dot();
	}
	
	function star(){
		return new star();
	}


}



//
// there is no PHP end tag so we don't mess the headers up!
//