<?php

header ("Content-type: text/xml");	

error_reporting(E_ALL);/*all error*/
/*error_reporting(0);*//*deactived all error*/
//error_reporting(E_ERROR);/*deactive warning only error*/
ini_set('display_errors', '1');

require_once('php/db_connect.php');
require_once('php/utilities.php');
/*require_once('underscore.php');*/

@ $mysqli = conect_db();
@ session_start();

$respond = array();

if (mysqli_connect_errno()) {
	$respond['status'] = 'error';
	$respond['msg'] = 'imposible conect to DB';
	echo json_encode($respond);	
} else {
	$urlBase = 	$_SERVER['HTTP_HOST'];/*guardamos la url del local host*/
	
	require_once('php/classes/bookmarks.php');			
	$bookmark = new Bookmarks($mysqli);
	$infoBookmarks = $bookmark->getAll();
	
		
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	
	echo "<url>\n
				<loc>http://".$urlBase."</loc>\n
				<changefreq>daily</changefreq>\n
				<priority>0.8</priority>\n
		  </url>\n";
		  
	echo "<url>\n
				<loc>http://".$urlBase."/#!/faq</loc>\n
				<changefreq>weekly</changefreq>\n
				<priority>0.8</priority>\n
		  </url>\n";

	foreach ($infoBookmarks['data'] as $key => $value) {
		echo "<url>\n
					<loc>http://".$urlBase."/#!/detail?id=".$infoBookmarks['data'][$key]['id']."</loc>\n
					<changefreq>weekly</changefreq>\n					
			  </url>\n";
	}	
	
	echo "</urlset>";
	
	
}

?>
