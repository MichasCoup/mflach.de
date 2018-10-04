<?php
/**********************************************************************************************/

/************************************************/ 
/************* GLOBAL CONFIGURATION *************/ 
/************************************************/ 

/************** DB CONFIGURATION*****************/

define("DB_SYSTEM", "mysql");
define("DB_HOST", "server1.febas.net");
define("DB_NAME", "mflach");
define("DB_USER", "k8695-1");
define("DB_PWD", "Ka6C0bE3gL");


/************* FROM CONFIGURATION****************/
define("MIN_INPUT_LENGTH", 2);
define("MAX_INPUT_LENGTH", 255);


/********** IMAGE UPLOAD CONFIGURATION **********/
define("IMAGE_MAX_WIDTH", 800);
define("IMAGE_MAX_HEIGHT", 800);
define("IMAGE_MAX_SIZE", 128*1024);
define("IMAGE_UPLOAD_PATH", "uploaded_images/");
define("IMAGE_ALLOWED_MIMETYPES", array("image/jpg", "image/jpeg", "image/gif", "image/png"));



/***************** DEBUGGING ********************/
define("DEBUG", true);
define("DEBUG_F", true);
define("DEBUG_DB", true);



/**********************************************************************************************/
?>