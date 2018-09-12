<?php
/**********************************************************************************************/

/************************************************/ 
/************* GLOBAL CONFIGURATION *************/ 
/************************************************/ 

/************** DB CONFIGURATION*****************/

define("DB_SYSTEM", "mysql");
define("DB_HOST", "localhost");
define("DB_NAME", "blog");
define("DB_USER", "root");
define("DB_PWD", "");


/************* FROM CONFIGURATION****************/
define("MIN_INPUT_LENGTH", 2);
define("MAX_INPUT_LENGTH", 255);


/********** IMAGE UPLOAD CONFIGURATION **********/
define("IMAGE_MAX_WIDTH", 800);
define("IMAGE_MAX_HEIGHT", 800);
define("IMAGE_MAX_SIZE", 128*1024);
define("IMAGE_UPLOAD_PATH", "uploaded_images/");
define("IMAGE_ALLOWED_MIMETYPES", array("image/jpg", "image/jpeg", "image/gif", "image/png"));


/*********** STANDARD PATH CONFIGURATION ***********/
define("AVATAR_DUMMY_PATH", "css/images/avatar_dummy.png");


/***************** DEBUGGING ********************/
define("DEBUG", false);
define("DEBUG_F", false);
define("DEBUG_DB", false);



/**********************************************************************************************/
?>