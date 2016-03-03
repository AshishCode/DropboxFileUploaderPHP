<?php

	//For debugging
	error_reporting(E_ALL);
	ini_set("display_errors", "On");

	//including dropbox libraries
	require_once "dropbox/lib/Dropbox/autoload.php";

	//setting the default timezone
	date_default_timezone_set('Asia/Kolkata');

	use \Dropbox as dbx;

	try {

		//fetching config data
		$CONFIG_CONTENT = file_get_contents('config.json');
		$DROPBOX_CONFIG = json_decode($CONFIG_CONTENT, true);

		//accessing config
		$accessToken = $DROPBOX_CONFIG["ACCESS_TOKEN"];
		$AppDbDirectoryName = $DROPBOX_CONFIG["APP_DB"];
		$AuthDbDirectoryName = $DROPBOX_CONFIG["AUTH_DB"];

		//setting up dropbox
		$appInfo = dbx\AppInfo::loadFromJson($DROPBOX_CONFIG);
		$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");	
		$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");

		//iterating through the contents of a app database folder
		$iterator = new DirectoryIterator(dirname($AppDbDirectoryName));
		foreach ( $iterator as $fileinfo ) {

			//excluding the . and .. directories
			if(!$fileinfo->isDot()) {

				//fetching the file names and file paths
	    		$fileName = $fileinfo->current()->getFilename();
	    		$filePath = $fileinfo->current()->getPathName();

	    		//Uploading the files
				$f = fopen($filePath, "rb");
				$result = $dbxClient->uploadFile("/mobilebackend/" . $fileName, dbx\WriteMode::add(), $f);
				fclose($f);
				
				//removing the uploaded file
				unlink($filePath);

				//logging the upload result into the log file
				$logMessage = date("l jS \of F Y h:i:s A") . PHP_EOL . json_encode($result);
				file_put_contents('uploadLog.log', $logMessage.PHP_EOL , FILE_APPEND);

				echo "File :" . $fileName . " has been uploaded.";
			}
		}

		//iterating through the contents of auth database folder
		$iterator = new DirectoryIterator(dirname($AuthDbDirectoryName));
		foreach ( $iterator as $fileinfo ) {

			//excluding the . and .. directories
			if(!$fileinfo->isDot()) {

				//fetching the file names and file paths
	    		$fileName = $fileinfo->current()->getFilename();
	    		$filePath = $fileinfo->current()->getPathName();

	    		//Uploading the files
				$f = fopen($filePath, "rb");
				$result = $dbxClient->uploadFile("/mobilebackend_sentinel/" . $fileName, dbx\WriteMode::add(), $f);
				fclose($f);
				
				//removing the uploaded file
				unlink($filePath);

				//logging the upload result into the log file
				$logMessage = date("l jS \of F Y h:i:s A") . PHP_EOL . json_encode($result);
				file_put_contents('uploadLog.log', $logMessage.PHP_EOL , FILE_APPEND);

				echo "File :" . $fileName . " has been uploaded.";
			}
		}
			
	} catch (Exception $e) {
		//displaying the errors
		echo $e->getMessage();

		//logging the upload result into the log file
		$logMessage = date("l jS \of F Y h:i:s A") . PHP_EOL . json_encode($e->getMessage());
		file_put_contents('uploadLog.log', $logMessage.PHP_EOL , FILE_APPEND);
	}
?>