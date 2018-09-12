<?php

/***********************************************************************************************************/
			/**
			*
			* Entschärft und säubert einen String
			*
			* @param String $inputString - Der zu entschärfende und zu bereinigende String
			*
			* @return String - Der entschärfte und bereinigte String
			*
			*/
			function cleanString($inputString) {
if(DEBUG_F)		echo "<p class='debugCleanString'>Aufruf: cleanString($inputString)</p>";

				// trim() entfernt am Anfang und am Ende eines Strings alle 
				// sog. Whitespaces (Leerzeichen, Tabulatoren, Zeilenumbrüche)
				$inputString = trim( $inputString );

				// htmlspecialchars() entschärft HTML-Steuerzeichen wie < > & '' ""
				// und ersetzt sie durch &lt;, &gt;, &amp;, &apos; &quot;
				$inputString = htmlspecialchars( $inputString );

				return $inputString;
			}

/***********************************************************************************************************/
			/**
			*
			* Prüft einen String auf Leerstring, Mindest- und Maxmimallänge
			*
			* @param String $inputString - Der zu prüfende String
			* @param [Integer $minLength] - Die erforderliche Mindestlänge
			* @param [Integer $maxLength] - Die erlaubte Maximallänge
			*
			* @return String/NULL - Ein String bei Fehler, ansonsten NULL
			* 
			*/
			function checkInputString($inputString, $minLength=MIN_INPUT_LENGTH, $maxLength=MAX_INPUT_LENGTH) {
if(DEBUG_F)		echo "<p class='debugCheckInputString'><b>Aufruf:</b> checkInputString( $inputString  <b>[min: $minLength | max: $maxLength]</b>)</p>";

				$errorMessage = NULL;
				// Prüfen auf Leer-String
				if( $inputString === "" ) {
					$errorMessage = "Dies ist ein Pflichtfeld";
					// Prüfe auf Mindestlänge
				}elseif ( mb_strlen( $inputString ) < $minLength ) {
					$errorMessage = "Muss mindestens $minLength Zeichen lang sein!";
					// Prüfe auf Maximallänge
				}elseif ( mb_strlen( $inputString ) > $maxLength ) {
					$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";
				}
				return $errorMessage;
			}

/***********************************************************************************************************/
			/**
			*
			* Prüft eine Email-Adresse auf Leerstring und Validität
			*
			* @param String $inputString - Die zu prüfende Email-Adresse
			*
			* @return String/NULL - Ein String bei Fehler, ansonsten NULL
			*
			*/
			function checkEmail($inputString) {
if(DEBUG_F)		echo "<p class='debugCheckEmail'><b>Aufruf:</b> checkEmail( $inputString )</p>";

				$errorMessage = NULL;
				// Prüfen auf Leer-String
				if( $inputString === "" ) {
					$errorMessage = "Dies ist ein Pflichtfeld";
					// Email auf Validität prüfen
				} elseif ( !filter_var($inputString, FILTER_VALIDATE_EMAIL) ) {
					$errorMessage = "Dies ist keine gültige E-Mail!";
				}
				
				return $errorMessage;
			}

/***********************************************************************************************************/

			/**
			*
			* Speichert und prüft ein hochgeladenen Bild auf MIME-Type, Datei- und Bildgröße
			*
			* @param Array $uploadedImage - Das hochzuladende Bild aus $_FILES
			* @param [Int $maxWidth] - Die maximal erlaubte Bildbreite in Px
			* @param [Int $maxHeigth] - Die maximal erlaubte Bildhöhe in Px
			* @param [Int $maxSize] - Die maximal erlaubte Dateigröße in Bytes
			* @param [String $uploadPath] - Das Speicher-Verzeichnis auf dem Server
			* @param [Array $allowedMimeTypes] - Whitelist der erlaubten MIME-Types
			*
			* @return Array {String/NULL - Fehlermeldung im Fehlerfall, String - Der Speicherpfad auf dem Server}
			*
			*/
			function imageUpload(	$uploadedImage, 
									$maxWidth 			= IMAGE_MAX_WIDTH,
									$maxHeight 			= IMAGE_MAX_HEIGHT,
									$maxSize 			= IMAGE_MAX_SIZE,
									$uploadPath 		= IMAGE_UPLOAD_PATH,
									$allowedMimeTypes 	= IMAGE_ALLOWED_MIMETYPES
								) {

if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Aufruf:</b> imageUpload()</p>";

				/*
					Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
					Den Dateinamen [name]
					Den generierten (also ungeprüften) MIME-Type [type]
					Den temporären Pfad auf dem Server [tmp_name]
					Die Dateigröße in Bytes [size]
				*/
if(DEBUG_F)		echo "<pre class='debugImageUpload'>";
if(DEBUG_F)		print_r($uploadedImage);
if(DEBUG_F)		echo "</pre>";

				/****************** Bildinformationen sammeln **********************/

				//Dateiname
				$fileName = $uploadedImage["name"];
				//ggf. Leerzeichen im Dateinamen durch "_" ersetzen
				$fileName = str_replace(" ", "_", $fileName);
				$fileName = strtolower($fileName);

				//Dateigröße
				$fileSize = $uploadedImage["size"];
				
				// Temporärer Pfad auf dem Server
				$fileTemp = $uploadedImage["tmp_name"];

				// zufälliger Dateinamen generiern
				$randomPrefix = rand(1,999999) . str_shuffle("abcdefghijklmnopqrstuvwxyz") . rand(1,9999999);
				$fileTarget = $uploadPath.$randomPrefix."_".$fileName;

if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$fileName:</b> $fileName</p>";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$fileSize:</b>". round($fileSize/1024,2)." kb</p>";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$fileTemp:</b> $fileTemp</p>";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$fileTarget:</b> $fileTarget</p>";

				// genauere Inforamationen zum Bild holen
				$imageData = getimagesize($fileTemp);

				/*
				Die Funktion getimagesize() liefert bei gültigen Bildern ein Array zurück:
				Die Bildbreite in PX [0]
				Die Bildhöhe in PX [1]
				Einen für die HTML-Ausgabe vorbereiteten String für das IMG-Tag
				(width="480" height="532") [3]
				Die Anzahl der Bits pro Kanal ['bits']
				Die Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) ['channels']
				Den echten(!) MIME-Type ['mime']
				*/
if(DEBUG_F)		echo "<pre class='debugImageUpload'>";
if(DEBUG_F)		print_r($imageData);
if(DEBUG_F)		echo "</pre>";

				$imageWidth		= $imageData[0];
				$imageHeight	= $imageData[1];
				$imageMimeType	= $imageData["mime"];

if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$imageWidth:</b> $imageWidth</p>";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$imageHeight:</b> $imageHeight</p>";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>\$imageMimeType:</b> $imageMimeType</p>";

				/********** Bild Prüfen **********/

				// Whitelist mit erlauten Bildtypen
				// $allowedMimeTypes = array("image/jpg", "image/jpeg", "image/gif", "image/png");

				// Mime-Type prüfen
				if( !in_array($imageMimeType, $allowedMimeTypes) ) {
					$errorMessage = "Dies ist kein gültiger Bildtype!";
				// Maximal erlaubte Bildhöhe
				} elseif( $imageHeight > $maxHeight ) {
					$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";
				// Maximal erlaubte Bildbreite
				} elseif( $imageWidth > $maxWidth ) {
					$errorMessage = "Die Bildbreite darf maximal $maxWidth Pixel betragen!";
				// Maximal erlaubte Dateigröße
				} elseif( $fileSize > $maxSize ) {
					$errorMessage = "Die Dateigröße darf maximal ". round($maxSize/1024,2) ."kB betragen!";

				// Wenn es keinen Fehler gab
				} else {
					$errorMessage = NULL;
				}

				/************** Bild Speichern ****************/
				if( !$errorMessage ) {
					// Erfolgsfall
if(DEBUG_F)			echo "<p class='debugImageUpload'><b>Die Bildprüfung ergab keine Fehler</b></p>";

					// Bild an seinen endgültigen Speicherort verschieben
					move_uploaded_file($fileTemp, $fileTarget);
if(DEBUG_F)			echo "<p class='debugImageUpload'><b>Das Bild wurde erfolgreich unter $fileTarget gespeichert worden.</b></p>";

				} 

				/************ Fehlermeldung und Bildpfad zurückgeben *************/
				return array("imageError"=>$errorMessage,"imagePath"=>$fileTarget);
			}


/***********************************************************************************************************/
?>