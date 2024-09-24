<?php define( "_VALID_PHP", true);
require_once '../autoload.php';
header('Content-type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php 


//$products = [];
$page = 1;
$total_pages = 0;

$limit = 1000;

if(isset($_GET['sitemap'])){
	$result = Api::cache(false)->data(['page' => (int)$_GET['sitemap'], 'order'=>'oldest' ])->limit($limit)->get()->blogPosts();
	foreach($result['result'] as $row){
		print " <url>
				<loc>{$row['url']}</loc>
				</url>";
	}
	
} 
else {
	$result = Api::cache(false)->data(['page' => 1 ])->limit($limit)->get()->blogPosts();
	

	
	for($i=1; $i<=$result['pagination']['total_pages']; $i++){
		print " <url>
				<loc>{$core->site_url}{$_SERVER['REQUEST_URI']}?sitemap=$i</loc>
				
				</url>";
	}
	
}

?>
</urlset>