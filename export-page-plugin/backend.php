<?php
add_action('wp_ajax_make_download', 'make_download');
add_action('wp_ajax_nopriv_make_download', 'make_download');
function make_download(){
	//print_r($_POST);
	ini_set('max_execution_time', '0'); // for infinite time of execution 
	$temp = plugin_dir_path( __FILE__ ).'temp/';
	foreach(glob($temp.'*.*') as $v){
		unlink($v);
	}
	if (!file_exists($temp)) {		
		mkdir($temp.$image, 0777, true);		
	}	
	$post_name = get_the_title($_POST["postid"]);
	$image_folder_name = $_POST['images_folder_name'];    
	$html_file_name = $_POST['html_file_name'];    
	$css_file_name = $_POST['css_file_name'];    
	$clean_js_code = $_POST['clean_js_code'];    
	$disable_url = $_POST['disable_url'];    
	$clean_links = $_POST['clean_links'];   
	$clean_srcset_code = $_POST['clean_srcset_code'];   
	$clean_form_code = $_POST['clean_form_code'];   
	$clean_domain = $_POST['clean_domain'];   
	$src = true;
	$js = true;
	$cssname = isset( $css_file_name ) ? $css_file_name : 'style';
	$jscript = 'js';
	$htmlname = isset( $html_file_name ) ? $html_file_name : 'index';
	$image = isset( $image_folder_name ) ? $image_folder_name : 'images';
	$url = $_POST['url'];
	//$url = 'http://localhost/testing-wp/wp-content/plugins/export-page-plugin/test.html';
		//$url = 'https://berlin.wpestatetheme.org/properties/stunning-villa-to-rent/';
	if (!file_exists($temp.$image)) {		
		mkdir($temp.$image, 0777, true);		
	}		
	 	
	 $html = file_get_contents($url); 
	
	 $html2 = $html; 
	 $html2 = preg_replace('/<script(.*)>/','<script notremove="1"$1>', $html2);
	 $html2 = preg_replace('/><script(.*)>/','><script notremove="1"$1>', $html2);
	 
	 $html2 = preg_replace_callback(
        '/<article\b[^>]*>[\s\S]*?(<\/article>)/is',
        function ($matches) {
			//print_r($matches);
           // return str_replace('script', 'script22',$matches[0]);
            return preg_replace('/<script notremove="1"(.*)>/','<script notremove="0"$1>', $matches[0]);
        },
        $html2
     );
	//die($html2);
	 
	 
	 
	 
	$doc = new DOMDocument();
	$internalErrors = libxml_use_internal_errors(true);
	$doc->loadHTML($html);
	libxml_use_internal_errors($internalErrors);
	//die();
//images parse
	$images = $doc->getElementsByTagName('img');
	foreach($images as $img){
		$src = $img->getAttribute('src');
		if($src){
			file_put_contents($temp.$image.'/'.(strtok(basename($src), "?")), file_get_contents($src));
		}
	}
//css parse	
	$csscode = '';
	$links = $doc->getElementsByTagName('link');
	foreach($links as $link){
		$css = $link->getAttribute('href');
		$parse_url = parse_url($css);		
		$output = explode("/",$parse_url["path"]);
		$max = max(array_keys($output));		
		$path_info = pathinfo($output[$max]);
        $extension = $path_info['extension']; 
		if($extension == 'css'){
			$csscode.= file_get_contents($css);
		}
	}
 	file_put_contents($temp.$cssname.'.css', $csscode);
 //js parse	
	$jshead = '';
	$scripts = $doc->getElementsByTagName('script');
	foreach($scripts as $script){
		$jsss = $script->getAttribute('src');
		if($jsss != ''){
			file_put_contents($temp.(strtok(basename($jsss), "?")), file_get_contents($jsss));
			$jshead.= '<script type="text/javascript" src="'.basename($jsss).'"></script>';
		}
	} 

//store to folder
	//$doc->save($temp.$htmlname.".html");	
	   
	if($clean_form_code)
	$html2 = preg_replace('/<form(.*)action="([^"]*)"(.*)>/','<form$action="#"$3>',$html2); //form
	if($clean_srcset_code)
	$html2 = preg_replace('/<img(.*)srcset="([^"]*)"(.*)>/','<img$srcset="#"$3>',$html2); // img - srcset
	if($clean_links)
	$html2 = preg_replace('/<a(.*)href="([^"]*)"(.*)>/','<a$1href="#"$3>',$html2); // a
	//$articles = preg_match_all('/<articles\b[^>]*>[\s\S]*?</articles>/is', "", $html2); // script
	if($clean_js_code)
	$html2 = preg_replace('/<script(.*?)notremove="1"(.*?)>[\s\S]*?(<\/script>)/', "", $html2);
	//$html2 = preg_replace('/(<(script)\b[^>]*>).*?(<\/\2>)/is', "", $html2); // script
	if($clean_domain)
	$html2 = preg_replace('/(<(head)\b[^>]*>).*?(<\/\2>)/is', "<head><link rel='stylesheet' href='style.css' type='text/css' media='all' />$jshead</head>", $html2);
	//$html2 = preg_replace('/(<(body)\b[^>]*>).*?(<\/\2>)/is', $jshead, $html2);
	//$html2 = preg_replace('#<body(.*?)</body>#is', '<body$1'.$jshead.'</body>', $html2);
	//die();
	file_put_contents($temp.$htmlname.".html", $html2);
	//echo $html = file_get_contents($temp.$htmlname.".html");
	die();
}




add_action( 'save_post', 'export_page_plugin_fields_save' );
function export_page_plugin_fields_save( $post_id ){    
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;    
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;   
    if( !current_user_can( 'edit_post' ) ) return; 
    if( isset( $_POST['images_folder_name'] ) )
        update_post_meta( $post_id, 'images_folder_name', esc_attr( $_POST['images_folder_name'] ) );    
	if( isset( $_POST['html_file_name'] ) )
        update_post_meta( $post_id, 'html_file_name', esc_attr( $_POST['html_file_name'] ) );   
	if( isset( $_POST['css_file_name'] ) )
        update_post_meta( $post_id, 'css_file_name', esc_attr( $_POST['css_file_name'] ) );   
    $clean_js_code = isset( $_POST['clean_js_code'] ) && $_POST['clean_js_code'] ? 'on' : 'off';
    update_post_meta( $post_id, 'clean_js_code', $chk );
	$disable_url = isset( $_POST['disable_url'] ) && $_POST['disable_url'] ? 'on' : 'off';
    update_post_meta( $post_id, 'disable_url', $chk );
	$clean_links = isset( $_POST['clean_links'] ) && $_POST['clean_links'] ? 'on' : 'off';
	$clean_srcset_code = isset( $_POST['clean_srcset_code'] ) && $_POST['clean_srcset_code'] ? 'on' : 'off';
    update_post_meta( $post_id, 'clean_srcset_code', $chk );
}

function myinit(){   
	if(isset($_GET['adtdownload'])){
		error_reporting(0);
		// Get real path for our folder
		$rootPath = realpath(plugin_dir_path( __FILE__ ).'temp');
		$zipname = 'file.zip';
		// Initialize archive object
		$zip = new ZipArchive();
		$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file)
		{
			// Skip directories (they would be added automatically)
			if (!$file->isDir())
			{
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
		}

		// Zip archive will be created only after closing object
		$zip->close();	
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);	
die();		
	}
}
add_action('init','myinit');
?>