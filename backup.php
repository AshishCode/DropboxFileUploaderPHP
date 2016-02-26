<?php
	//For debugging
	error_reporting(E_ALL);
	ini_set("display_errors", "On");

	require_once "dropbox/lib/Dropbox/autoload.php";

	//setting the default timezone
	date_default_timezone_set('Asia/Kolkata');

	use \Dropbox as dbx;

	$dropbox_config = array(
	    'key'    => 'fgidjt5uf45ilb1',
	    'secret' => 'vn3fvk9ygmb3r6x'
	);

	$appInfo = dbx\AppInfo::loadFromJson($dropbox_config);
	$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

	$accessToken = "sdGUA9ORi2gAAAAAAAABMX5Q_dvehWsPNHCVzliGt1P7flMaqUXFCKil0u6hbd6T";

	$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");

	try {
		//directory traverser
		// $dir = new DirectoryIterator('/home/ashish/Desktop/backups/daily/mobilebackend';
		
		// foreach ($dir as $fileinfo) {
		//     if (!$fileinfo->isDot()) {
		//         var_dump($fileinfo->getFilename());
		//     }
		// }

		$iterator = new DirectoryIterator(dirname('/home/ashish/Desktop/backups/daily/mobilebackend/*'));
		foreach ( $iterator as $fileinfo ) {
			if(!$fileinfo->isDot())
	    		
	    		$fileName = $fileinfo->current()->getFilename();
	    		$filePath = $fileinfo->current()->getPathName(); // would return object(DirectoryIterator)
	    		//Uploading the file
				$f = fopen($filePath, "rb");
				$result = $dbxClient->uploadFile("/" . $fileName, dbx\WriteMode::add(), $f);
				fclose($f);
				echo json_encode($result);
		}

		// $it = new FilesystemIterator('/home/ashish/Desktop/backups/daily/mobilebackend');
		// foreach ($it as $fileinfo) {
		//   echo $fileinfo->getFilename() . "\n";
		// }
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	// $dir = "/home/ashish/Desktop/backups/*";
	// foreach(glob($dir) as $file)
	// {
	//     if(!is_dir($file)) { echo basename($file)."\n";}
	// }



	// Uploading the file
	// $f = fopen("composer.json", "rb");
	// $result = $dbxClient->uploadFile("/working-draft.txt", dbx\WriteMode::add(), $f);
	// fclose($f);
	// echo json_encode($result);

?>