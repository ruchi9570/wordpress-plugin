<?php
function export_register_options_page() {
  add_options_page('Export Option', 'Export Option', 'manage_options', 'exportoption', 'export_options_page');   
}
add_action('admin_menu', 'export_register_options_page');

function export_options_page()
{
?>
  <div>
  <?php //screen_icon(); ?>  
  <h2>Features of Export Option Plugin</h2>
  <ul>
	<li>it make static html page using wordpress in less time.</li>
	<li>it is easy to use and flexible anyone can use it.</li>
	<li>it's functionity are very simple.</li>
	<li>there are very unique and interesting functionity.</li>
	<li>there are lots of options  for making user interective.</li>
	<li>it can download html with unique name.</li>
	<li>it can download html with js code in separate file with custumize name.</li>
	<li>it can download html folder in zip file with Clean js Code & Fields.</li>
	<li>it can download html with Clean srcset in image and Clean Links.</li>
	<li>it can download html with Disable URL into Form ActionClean domain name and Disable URL into Form Action.</li>
  </ul>
  </div>
<?php
} 

?> 