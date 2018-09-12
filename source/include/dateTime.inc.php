<?php
/************************************************************************************************************************************/

					/**
					*
					* Wandelt ein ISO Datums-/Uhrzeitformat in ein europäisches Datums-/Uhrzeitformat um
					* und separiert Datum von Uhrzeit
					*
					* @param String Das ISO Datum/Uhrzeit
					*
					* @return Array Das deutsche Datum plus die Uhrzeit
					*
					*/


					function convertIsotoEuDateTime($dateTime) {
if(DEBUG_F)		echo "<p class='debug'><b>Aufruf:</b> function convertIsotoEuDateTime($dateTime)</p>";

					$newYear = substr($dateTime, 0, 4);
					$newMonth =substr($dateTime,5,2);
					$newDay = substr($dateTime,8,2);
					$euDate = "$newDay.$newMonth.$newYear";

					if(strlen( $dateTime ) > 10 ) {
						$newTime = substr($dateTime, 11, 5);
					} else{
						$newTime = NULL;
					}
					return array("date" => $euDate, "time"=>$newTime);
				} 
/************************************************************************************************************************************/

?>