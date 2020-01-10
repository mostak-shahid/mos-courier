=== CMB Field Type: Sorter ===
Contributors: Tran Bang
Tags: wordpress, sorter,  layout, jqueryui
Requires at least: 3.6.1
Tested up to: 4.2.2
Stable tag: 4.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin gives you two CMB field types based on the Sorter script:


== Description ==
This plugin gives you two CMB field types based on the Sorter script:

<h4>Usage</h4>
<p><code>tb_sorter</code> - Select box with with typeahead-style search. Example:</p>
<pre>
	$cmb->add_field(array(
	    'name'    => 'Page Layout',
	    'id'      => $prefix . 'ingredients',
	    'desc'    => 'Select Page Layout',
	    'type'    => 'tb_sorter',
	    'options' => array(
	        'enabled'  => array(
	            'highlights' => 'Highlights',
	            'slider'     => 'Slider',
	            'staticpage' => 'Static Page',	            
	        ),
	        'disabled' => array(
	        	'services'   => 'Services'
	        )        
	    ),
	));
</pre>

<h4>Out put</h4>

<pre>
	get_post_meta(get_the_ID(), '_yourprefix_about_ingredients', true);
</pre>

IMPORTANT: The key pair of 'placebo' => 'placebo' IS NO LONGER REQUIRED.


== Installation ==
You can install this field type as you would a WordPress plugin:

1. Download the plugin
2. Place the plugin folder in your /wp-content/plugins/ directory
3. Activate the plugin in the Plugin dashboard
4. Alternatively, you can place the plugin folder in with your theme/plugin. After you call CMB:

Add another line to include the cmb-field-sorter.php file. Something like:
require_once 'cmb-field-type-sorter/cmb-field-sorter.php';

== Screenshots ==
1. admin panel
2. drag drop
3. output value