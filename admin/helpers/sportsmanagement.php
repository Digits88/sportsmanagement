<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie können es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es nützlich sein wird, aber
* OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;
 
if(version_compare(JVERSION,'3.0.0','ge')) 
{
jimport('joomla.html.toolbar');            
}            

/**
 * sportsmanagementHelper
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
abstract class sportsmanagementHelper
{
    static $latitude = '';
    static $longitude = '';
	static $_jsm_db = '';
    static $_success_text = array();
    
    
    /**
     * sportsmanagementHelper::date_diff()
     * 
     * @param mixed $d1
     * @param mixed $d2
     * @return
     */
    function date_diff($d1, $d2){
/* This is correctly working time differentiating function. It's resistant to problems with 
   leap year and different days of month. Inputs are two timestamps and function returns array 
   with differences in year, month, day, hour, minute a nd second.
 
   Compares two timestamps and returns array with differencies (year, month, day, hour, minute, second)
 
   More infos at http://de2.php.net/manual/de/function.date-diff.php#93915
   
   beispiele
define(BIRTHDAY, strtotime('1980-08-26 15:25:00')); // geburtsdatum
$now  = time(); // aktuelles datum
 
// geburtsdatum dieses jahr:
$birthdayThisYear = mktime(date('H',BIRTHDAY),date('i',BIRTHDAY),date('s',BIRTHDAY),date('n',BIRTHDAY),date('j',BIRTHDAY),date('Y',$now));
 
// geburtsdatum nächstes jahr:
$birthdayNextYear = mktime(date('H',BIRTHDAY),date('i',BIRTHDAY),date('s',BIRTHDAY),date('n',BIRTHDAY),date('j',BIRTHDAY),date('Y',$now)+1);
 
// geburtsdatum letztes jahr:
$birthdayLastYear = mktime(date('H',BIRTHDAY),date('i',BIRTHDAY),date('s',BIRTHDAY),date('n',BIRTHDAY),date('j',BIRTHDAY),date('Y',$now)-1);                                                                                                                              
 
// der nächste geburtstag:
$nextBirthday     = ($now<$birthdayThisYear)?$birthdayThisYear:$birthdayNextYear;                           
 
// der letzte geburtstag:
$lastBirthday     = ($now<=$birthdayThisYear)?$birthdayLastYear:$birthdayThisYear;
 
// wie viel prozent des jahres bis zum nächsten geburtstag sind schon um? 
$percent          = ($now-$lastBirthday) / ($nextBirthday-$lastBirthday) * 100;
 
// wie viele tage sind es noch bis zum nächsten geburtstag?
$days2Birthday    = floor(($nextBirthday - $now) / 86400);
 
// alter als array  (jahre, monate, tage, stunden, minuten, sekunden - schaltjahre werde berücksichtigt!)
$age              = date_diff(BIRTHDAY, $now);
   
*/
        //check higher timestamp and switch if neccessary
        if ($d1 < $d2) {
                $temp = $d2;
                $d2 = $d1;
                $d1 = $temp;
        } else {
                $temp = $d1; //temp can be used for day count if required
        }
        $d1 = date_parse(date("Y-m-d H:i:s",$d1));
        $d2 = date_parse(date("Y-m-d H:i:s",$d2));
        //seconds
        if ($d1['second'] >= $d2['second']){
                $diff['second'] = $d1['second'] - $d2['second'];
        } else {
                $d1['minute']--;
                $diff['second'] = 60-$d2['second']+$d1['second'];
        }
        //minutes
        if ($d1['minute'] >= $d2['minute']){
                $diff['minute'] = $d1['minute'] - $d2['minute'];
        } else {
                $d1['hour']--;
                $diff['minute'] = 60-$d2['minute']+$d1['minute'];
        }
        //hours
        if ($d1['hour'] >= $d2['hour']){
                $diff['hour'] = $d1['hour'] - $d2['hour'];
        } else {
                $d1['day']--;
                $diff['hour'] = 24-$d2['hour']+$d1['hour'];
        }
        //days
        if ($d1['day'] >= $d2['day']){
                $diff['day'] = $d1['day'] - $d2['day'];
        } else {
                $d1['month']--;
                $diff['day'] = date("t",$temp)-$d2['day']+$d1['day'];
        }
        //months
        if ($d1['month'] >= $d2['month']){
                $diff['month'] = $d1['month'] - $d2['month'];
        } else {
                $d1['year']--;
                $diff['month'] = 12-$d2['month']+$d1['month'];
        }
        //years
        $diff['year'] = $d1['year'] - $d2['year'];
        return $diff;   
}

    /**
     * sportsmanagementHelper::jsmsernum()
     * generiert eine seriennummer
     * das template kann angepasst werden
     * @return
     */
    function jsmsernum()
{
    $template   = 'XX99-XX99-99XX-99XX-XXXX-99XX';
    $k = strlen($template);
    $sernum = '';
    for ($i=0; $i<$k; $i++)
    {
        switch($template[$i])
        {
            case 'X': $sernum .= chr(rand(65,90)); break;
            case '9': $sernum .= rand(0,9); break;
            case '-': $sernum .= '-';  break; 
        }
    }
    return $sernum;
}
    
    /**
     * sportsmanagementHelper::existPicture()
     * 
     * @param string $picture
     * @param string $standard
     * @return void
     */
    public static function existPicture($picture='',$standard='')
    {
    $app = JFactory::getApplication();
    $imageArray = '';
    
    if ( !JFile::exists($picture) )
    {
    //$app->enqueueMessage(__METHOD__.' '.__LINE__.' picture nicht vorhanden <pre>'.print_r($picture, true).'</pre><br>','Error');    
    return false;    
    }
    else
    {
    //$app->enqueueMessage(__METHOD__.' '.__LINE__.' picture vorhanden <pre>'.print_r($picture, true).'</pre><br>','');    
    return true;    
    }
    
    
    /*
    $file_headers = @get_headers($picture);
    
    $app->enqueueMessage(__METHOD__.' '.__LINE__.' picture_server <pre>'.print_r(COM_SPORTSMANAGEMENT_PICTURE_SERVER, true).'</pre><br>','');
    
    $app->enqueueMessage(__METHOD__.' '.__LINE__.' picture <pre>'.print_r($picture, true).'</pre><br>','');
    $app->enqueueMessage(__METHOD__.' '.__LINE__.' file_headers <pre>'.print_r($file_headers, true).'</pre><br>','');
    
    // check if files exists
    $fileExists = true;
    $app->enqueueMessage(__METHOD__.' '.__LINE__.' fileExists <pre>'.print_r($fileExists, true).'</pre><br>','');   
    $fileExists = JFile::exists($picture);
    
    $app->enqueueMessage(__METHOD__.' '.__LINE__.' fileExists <pre>'.print_r($fileExists, true).'</pre><br>','');
    
$ch = curl_init($picture);    
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$app->enqueueMessage(__METHOD__.' '.__LINE__.' retcode <pre>'.print_r($retcode, true).'</pre><br>','');
// $retcode >= 400 -> not found, $retcode = 200, found.
curl_close($ch);
    */
    
    //$imageArray = getimagesize($picture);
    //$app->enqueueMessage(__METHOD__.' '.__LINE__.' imageArray <pre>'.print_r($imageArray, true).'</pre><br>','');
    
//    if ( is_array ($imageArray) )
//    {
//        return true;
//    }
//    else
//    {
//        return true;
//    }
//    if($file_headers[0] == 'HTTP/1.1 404 Not Found') 
//    {
//    return false;
//    }
//    else 
//    {
//    return true;
//    }
        
    }
    
    /**
     * sportsmanagementHelper::setDebugInfoText()
     * 
     * @param mixed $methode
     * @param mixed $funktion
     * @param mixed $klasse
     * @param mixed $zeile
     * @param mixed $text
     * @return void
     */
    public static function setDebugInfoText($methode,$funktion,$klasse,$zeile,$text)
    {
        $app = JFactory::getApplication();
    // Create an object for the record we are going to update.
        $object = new stdClass();
        // Must be a valid primary key value.
        $object->methode = $methode;
        $object->function = $funktion;
        $object->class = $klasse;    
        $object->line = $zeile;
        $object->text = $text;
        //if ( !isset(self::$_success_text[$klasse]) )
        //if ( !is_array(self::$_success_text) )
        if ( !array_key_exists($klasse, self::$_success_text) )
        {
            self::$_success_text[$klasse] = array();
            //$app->enqueueMessage(__METHOD__.' '.__LINE__.'klasse <pre>'.print_r($klasse, true).'</pre><br>','');
        }
        $export[] = $object;
    self::$_success_text[$klasse] = array_merge(self::$_success_text[$klasse],$export);    
    
    //$app->enqueueMessage(__METHOD__.' '.__LINE__.'_success_text <pre>'.print_r(self::$_success_text, true).'</pre><br>','');
    
    }
    
    /**
     * sportsmanagementHelper::getTimezone()
     * 
     * @param mixed $project
     * @param mixed $overallconfig
     * @return
     */
    public static function getTimezone($project, $overallconfig) 
    {
		if($project) 
        {
			return $project->timezone;
		} 
        else 
        {
			return $overallconfig['time_zone'];
		}
	}
    
    
    /**
     * sportsmanagementHelper::getMatchContent()
     * 
     * @param mixed $match_id
     * @return void
     */
    public static function getMatchContent($content_id)
	{
	   $app	= JFactory::getApplication();
		// Create a new query object.		
		$db = sportsmanagementHelper::getDBConnection();
		$query = $db->getQuery(true);


        // Select some fields
        
        $query->select('introtext');
		// From the table
		$query->from('#__content');
        $query->where('id = ' . $content_id );
        
    $db->setQuery($query);
    $result = $db->loadResult();
    return $result;
	}
    
    /**
     * sportsmanagementHelper::getMatchDate()
     * 
     * @param mixed $match
     * @param string $format
     * @return
     */
    public static function getMatchDate($match, $format = 'Y-m-d')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xxxx-xx-xx";
	}

	/**
	 * sportsmanagementHelper::getMatchTime()
	 * 
	 * @param mixed $match
	 * @param string $format
	 * @return
	 */
	public static function getMatchTime($match, $format = 'H:i')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xx:xx";
	}

	/**
	 * sportsmanagementHelper::getMatchStartTimestamp()
	 * 
	 * @param mixed $match
	 * @param string $format
	 * @return
	 */
	public static function getMatchStartTimestamp($match, $format = 'Y-m-d H:i')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xxxx-xx-xx xx:xx";
	}

	/**
	 * sportsmanagementHelper::getMatchEndTimestamp()
	 * 
	 * @param mixed $match
	 * @param mixed $totalMatchDuration
	 * @param string $format
	 * @return
	 */
	public static function getMatchEndTimestamp($match, $totalMatchDuration, $format = 'Y-m-d H:i')
	{
		$endTimestamp = "xxxx-xx-xx xx:xx";
		if ($match->match_date)
		{
			$start = new DateTime(self::getMatchStartTimestamp($match));
			$end = $start->add(new DateInterval('PT'.$totalMatchDuration.'M'));
			$endTimestamp = $end->format($format);
		}
		return $endTimestamp;
	}

	/**
	 * sportsmanagementHelper::getMatchTimezone()
	 * 
	 * @param mixed $match
	 * @return
	 */
	public static function getMatchTimezone($match)
	{
		return $match->timezone;
	}
    
    /**
	 * Convert the UTC timestamp of a match (stored as UTC in the database) to:
	 * - the timezone of the Joomla user if that is set
	 * - to the project timezone as set in the project otherwise (so also for guest users,
	 *   aka visitors that have not logged in).
	 *
	 * @param match $match Typically obtained from a DB-query and contains the match_date and timezone (of the project)
	 */
	public static function convertMatchDateToTimezone(&$match)
	{
	   $app	= JFactory::getApplication();
       // Get some system objects.
		$config = JFactory::getConfig();
		$user = JFactory::getUser();
        $res = JFactory::getDate(strtotime($match->match_date));
        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' res<br><pre>'.print_r($res,true).'</pre>'),'Notice');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' match timezone<br><pre>'.print_r($match->timezone,true).'</pre>'),'Notice');
//        
//         if(version_compare(JVERSION,'3.0.0','ge')) 
//            {
//                $res->setTimezone(new DateTimeZone($app->getCfg('offset')));
//            }
//            else
//            {
//				$res->setOffset($app->getCfg('offset'));
//            }
//        
//        $test = $res->toUnix('true');    
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' test<br><pre>'.print_r($test,true).'</pre>'),'Notice');
            
		if ($match->match_date > 0)
		{
			$app = JFactory::getApplication();
			if ($app->isAdmin())
			{
				// In case we are editing match(es) always use the project timezone
				$timezone = $match->timezone;
			}
			else
			{
				// Otherwise use user timezone for display, and if not set use the project timezone
	 			$timezone = $user->getParam('timezone', $match->timezone);
			}

	 		//$matchDate = new JDate($match->match_date, 'UTC');
            $matchDate = new JDate($match->match_date);
/*            
            if(version_compare(JVERSION,'3.0.0','ge')) 
            {
                //$res->setTimezone($app->getCfg('offset'));
                $res->setTimezone(new DateTimeZone($app->getCfg('offset')));
            }
            else
            {
				$res->setOffset($app->getCfg('offset'));
            }
*/            
            if ( $timezone )
            {
	 		$matchDate->setTimezone(new DateTimeZone($timezone));
            }
            else
            {
            $timezone = $config->get('offset');   
            $matchDate->setTimezone(new DateTimeZone($config->get('offset')));
            }

	 		$match->match_date = $matchDate;
	 		$match->timezone = $timezone;
		} else {
			$match->match_date = null;
		}
        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' offset<br><pre>'.print_r($config->get('offset'),true).'</pre>'),'Notice');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' user<br><pre>'.print_r($user,true).'</pre>'),'Notice');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' timezone<br><pre>'.print_r($timezone,true).'</pre>'),'Notice');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' match  timezone<br><pre>'.print_r($match->timezone,true).'</pre>'),'Notice');
        
	}
    
    /**
     * sportsmanagementHelper::get_IP_address()
     * 
     * @return
     */
    function get_IP_address()
{
    $app	= JFactory::getApplication();
  foreach (array('HTTP_CLIENT_IP',
               'HTTP_X_FORWARDED_FOR',
               'HTTP_X_FORWARDED',
               'HTTP_X_CLUSTER_CLIENT_IP',
               'HTTP_FORWARDED_FOR',
               'HTTP_FORWARDED',
               'REMOTE_ADDR') as $key){
    if (array_key_exists($key, $_SERVER) === true){
        foreach (explode(',', $_SERVER[$key]) as $IPaddress){
            $IPaddress = trim($IPaddress); // Just to be safe

$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' key<br><pre>'.print_r($key,true).'</pre>'),'Notice');
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' IPaddress<br><pre>'.print_r($IPaddress,true).'</pre>'),'Notice');

            if (filter_var($IPaddress,
                           FILTER_VALIDATE_IP,
                           FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                !== false) {

                return $IPaddress;
            }
        }
    }
}
}

    /**
     * sportsmanagementHelper::isJoomlaVersion()
     * 
     * @param mixed $version
     * @return
     */
    public static function isJoomlaVersion ($version = '2.5')
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        
		$j = new JVersion();
        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($j,true).'</pre>'),'Notice');
//        $app->enqueueMessage(sprintf(JText::_('COM_SPORTSMANAGEMENT_JOOMLA_VERSION'), $j->RELEASE),'Notice');
        
        if (! defined('COM_SPORTSMANAGEMENT_JOOMLAVERSION'))
        {
        DEFINE( 'COM_SPORTSMANAGEMENT_JOOMLAVERSION',substr($j->RELEASE, 0, strlen($version)) );
        }
        
		return substr($j->RELEASE, 0, strlen($version)) == $version;
	}
    
    /**
	 * returns titleInfo
	 *
	 * @param prefix Text that must be placed at the start of the title.
	 */
	public static function createTitleInfo($prefix)
	{
		return (object)array(
			"prefix" => $prefix,
			"clubName" => null,
			"team1Name" => null,
			"team2Name" => null,
			"roundName" => null,
			"personName" => null,
			"playgroundName" => null,
			"projectName" => null,
			"divisionName" => null,
			"leagueName" => null,
			"seasonName" => null
		);
	}
    
    /**
	 * returns formatName
	 *
	 * @param titleInfo (info on prefix, teams (optional), project, division (optional), league and season)
	 * @param format
	 */
	public static function formatTitle($titleInfo, $format)
	{
		$name = array();

		if (!empty($titleInfo->personName)) {
			$name[] = $titleInfo->personName;
		}

		if (!empty($titleInfo->playgroundName)) {
			$name[] = $titleInfo->playgroundName;
		}

		if (!empty($titleInfo->team1Name)) {
			if (!empty($titleInfo->team2Name)) {
				$name[] = $titleInfo->team1Name." - ".$titleInfo->team2Name;
			} else {
				$name[] = $titleInfo->team1Name;
			}
		}

		if (!empty($titleInfo->clubName)) {
			$name[] = $titleInfo->clubName;
		}

		if (!empty($titleInfo->roundName)) {
			$name[] = $titleInfo->roundName;
		}

		$projectDivisionName = !empty($titleInfo->projectName) ? $titleInfo->projectName : "";
		if (!empty($titleInfo->divisionName)) $projectDivisionName .= " - ".$titleInfo->divisionName;

		switch ($format)
		{
			case 0: //Projectname
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				break;
			case 1: //Project and league name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				break;
			case 2: //Project, league and season name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 3: //Project and season name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 4: //League name
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				break;
			case 5: //League and season name
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 6: //Season name
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 7: // None
				break;
		}

		return $titleInfo->prefix . ": " . implode(" | ", $name);
	}
    
    /**
     * sportsmanagementHelper::getDBConnection()
     * 
     * @return
     */
    public static function getDBConnection($request = FALSE, $value = FALSE)
    {
    $app = JFactory::getApplication();
    $params = JComponentHelper::getParams( 'com_sportsmanagement' );
    
    $config = JFactory::getConfig();
    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' config<br><pre>'.print_r($config,true).'</pre>'),'');
    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' root<br><pre>'.print_r(JURI::root(),true).'</pre>'),'');
    
    if ( $params->get( 'cfg_dbprefix' ) )
{
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' database<br><pre> wir benutzen andere tabellen</pre>'),'');

$host 		= $config->get('host'); //replace your IP or hostname
$user 		= $config->get('user'); //database user
$password 	= $config->get('password');//database password
$database	= $config->get('db'); //database name
$prefix 	= $params->get('jsm_dbprefix'); //prefix if any else just give any random value
$driver 	= $config->get('dbtype'); //here u can also have ms sql database driver, postgres, etc
$debug 		= $config->get('config.debug');

$options	= array ( 'driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => $database, 'prefix' => $prefix );
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' database<br><pre>'.print_r($options,true).'</pre>'),'');   

$db = JDatabase::getInstance( $options );

if ( JError::isError($db) ) {
			header('HTTP/1.1 500 Internal Server Error');
			jexit('Database Error: ' . $db->toString() );
		}
        else
        {
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' database<br><pre> kein verbindungsfehler</pre>'),'');   
        }

		if ($db->getErrorNum() > 0) {
			JError::raiseError(500 , 'JDatabase::getInstance: Could not connect to database <br />' . 'joomla.library:'.$db->getErrorNum().' - '.$db->getErrorMsg() );
		}
        else
        {
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' database<br><pre> verbindung hergestellt</pre>'),'');   
        }

		$db->debug( $debug );
return $db;
}
else
{
    if ( $request )
    {
        $cfg_which_database = $value;
    }
    else
    {
       $cfg_which_database = $params->get( 'cfg_which_database' ); 
     }
       
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' cfg_which_database<br><pre>'.print_r($cfg_which_database,true).'</pre>'),'');
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getDbo<br><pre>'.print_r(JFactory::getDbo(),true).'</pre>'),'');
       

      //self::get_IP_address();
       
       if ( !$cfg_which_database )
       {
       //self::$_jsm_db = JFactory::getDbo(); 
       return JFactory::getDbo();
       }
       else
       {
       $option = array(); //prevent problems
        $option['driver']   = $params->get( 'jsm_dbtype' );            // Database driver name
        $option['host']     = $params->get( 'jsm_host' );    // Database host name
        $option['user']     = $params->get( 'jsm_user' );       // User for database authentication
        $option['password'] = $params->get( 'jsm_password' );   // Password for database authentication
        $option['database'] = $params->get( 'jsm_db' );      // Database name
        $option['prefix']   = $params->get( 'jsm_dbprefix' );             // Database prefix (may be empty)
 
        // zuerst noch überprüfen, ob der user
        // überhaupt den zugriff auf die datenbank hat.
        self::$_jsm_db = JDatabase::getInstance( $option );
        $user_id = $params->get( 'jsm_server_user' );
        //$user_password = $params->get( 'jsm_server_password' );
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jsm_server_password<br><pre>'.print_r($user_password,true).'</pre>'),'');
        
        if ( $user_id )
        {
            
        //echo "<strong>Password: </strong>" . JUserHelper::hashPassword($password);    
        //$testcrypt = JUserHelper::getCryptedPassword($user_password);
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__."<strong>Password: </strong>" . $testcrypt),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__."<strong>Password: </strong>" . JUserHelper::hashPassword($user_password)),'');
        
        // Load the profile data from the database.
        $db = self::$_jsm_db;
        $query = $db->getQuery(true);
        $query->select('up.profile_key, up.profile_value');
        $query->from('#__user_profiles as up');
        $query->where('up.user_id = '.$user_id);
        $query->where('up.profile_key LIKE '.$db->Quote(''.'jsmprofile.databaseaccess'.'') );
        $query->where('up.profile_value = 1');
        $db->setQuery($query);
        $results = $db->loadResult();
        $query->clear();
        $query->select('up.profile_key, up.profile_value');
        $query->from('#__user_profiles as up');
        $query->where('up.user_id = '.$user_id);
        $query->where('up.profile_key LIKE '.$db->Quote(''.'jsmprofile.website'.'') );
        $query->where('up.profile_value LIKE '.$db->Quote(''.JURI::root().''));
        $db->setQuery($query);
        $results2 = $db->loadResult();
        
        if ( $results && $results2 )
        {
        return JDatabase::getInstance( $option );
        }
        else
        {
        $app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_NO_DATABASE_SERVER_ACCESS'),'Error');    
        return JFactory::getDbo();    
        }
        }
        else
        {
        $app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_NO_DATABASE_SERVER_ACCESS'),'Error');    
        return JFactory::getDbo();     
        } 
  
       }    
       
       }
       //return self::$_jsm_db; 
        
    }
    
    /**
	 * Add data to the xml
	 *
	 * @param array $data data what we want to add in the xml
	 *
	 * @access private
	 * @since  1.5.0a
	 *
	 * @return void
	 */
	 function _addToXml($data)
	{
		if (is_array($data) && count($data) > 0)
		{
			$object = $data[0]['object'];
			$output = '';
			foreach ($data as $name => $value)
			{
				$output .= "<record object=\"" . self::stripInvalidXml($object) . "\">\n";
				foreach ($value as $key => $data)
				{
					if (!is_null($data) && !(substr($key, 0, 1) == "_") && $key != "object")
					{
						$output .= "  <$key><![CDATA[" . self::stripInvalidXml(trim($data)) . "]]></$key>\n";
					}
				}
				$output .= "</record>\n";
			}
			return $output;
		}
		return false;
	}    
    
/**
	 * _setJoomLeagueVersion
	 *
	 * set the version data and actual date, time and
	 * Joomla systemName from the joomleague_version table
	 *
	 * @access private
	 * @since  2010-08-26
	 *
	 * @return array
	 */
	 function _setJoomLeagueVersion()
	{
		$exportRoutine='2010-09-23 15:00:00';
			$result[0]['exportRoutine']=$exportRoutine;
			$result[0]['exportDate']=date('Y-m-d');
			$result[0]['exportTime']=date('H:i:s');
            // welche joomla version ?
            if(version_compare(JVERSION,'3.0.0','ge')) 
        {
            $result[0]['exportSystem']=JFactory::getConfig()->get('config.sitename');
        }
        else
        {
			$result[0]['exportSystem']=JFactory::getConfig()->getValue('config.sitename');
            }
			$result[0]['object']='JoomLeagueVersion';
			return $result;
	}    
    
    
/**
	 * _setLeagueData
	 *
	 * set the league data from the joomleague_league table
	 *
	 * @access private
	 * @since  1.5.5241
	 *
	 * @return array
	 */
	 function _setLeagueData($league)
	{
		
        if ( $league )
        {
            $result[] = JArrayHelper::fromObject($league);
			$result[0]['object'] = 'League';
			return $result;
		}
		return false;
        		
	}    

/**
	 * _setProjectData
	 *
	 * set the project data from the joomleague table
	 *
	 * @access private
	 * @since  1.5.0a
	 *
	 * @return array
	 */
	 function _setProjectData($project)
	{
		if ( $project )
        {
            $result[] = JArrayHelper::fromObject($project);
			$result[0]['object'] = 'JoomLeague20';
			return $result;
		}
		return false;
	}    

/**
	 * _setSeasonData
	 *
	 * set the season data from the joomleague_season table
	 *
	 * @access private
	 * @since  1.5.5241
	 *
	 * @return array
	 */
	 function _setSeasonData($season)
	{
		if ( $season )
        {
            $result[] = JArrayHelper::fromObject($season);
			$result[0]['object'] = 'Season';
			return $result;
		}
		return false;
	}
    
    
    /**
	 * _setSportsType
	 *
	 * set the SportsType
	 *
	 * @access private
	 * @since  1.5.5241
	 *
	 * @return array
	 */
	 function _setSportsType($sportstype)
	{

		if ( $sportstype )
		{
			$result[] = JArrayHelper::fromObject($sportstype);
			$result[0]['object'] = 'SportsType';
			return $result;
		}
		return false;

	}        
    
/**
	 * _setXMLData
	 *
	 * 
	 *
	 * @access private
	 * @since  1.5.0a
	 *
	 * @return void
	 */
	 function _setXMLData($data, $object)
	{
	if ( $data )
        {
            foreach ( $data as $row )
            {
                $result[] = JArrayHelper::fromObject($row);
            }
			$result[0]['object'] = $object;
			return $result;
		}
		return false;
	}
    

    
    
    
	
	/**
	 * sportsmanagementHelper::addSubmenu()
	 * 
	 * @param mixed $submenu
	 * @return void
	 */
	public static function addSubmenu($submenu) 
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $document = JFactory::getDocument();
        //$show_debug_info = JComponentHelper::getParams($option)->get('show_debug_info',0) ;
        // retrieve the value of the state variable. If no value is specified,
        // the specified default value will be returned.
        // function syntax is getUserState( $key, $default );
        $project_id = $app->getUserState( "$option.pid", '0' );
        $project_team_id = $app->getUserState( "$option.project_team_id", '0' );
        $team_id = $app->getUserState( "$option.team_id", '0' );
        $club_id = $app->getUserState( "$option.club_id", '0' );
      
        
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
//            $app->enqueueMessage(JText::_('addSubmenu post<br><pre>'.print_r(JRequest::get('post'),true).'</pre>'),'');
//            $app->enqueueMessage(JText::_('addSubmenu project_id<br><pre>'.print_r($project_id,true).'</pre>'),'');
//            $app->enqueueMessage(JText::_('addSubmenu project_team_id<br><pre>'.print_r($project_team_id,true).'</pre>'),'');
//            $app->enqueueMessage(JText::_('addSubmenu team_id<br><pre>'.print_r($team_id,true).'</pre>'),'');
//            $app->enqueueMessage(JText::_('addSubmenu club_id<br><pre>'.print_r($club_id,true).'</pre>'),'');
            
        $my_text = 'post <pre>'.print_r(JRequest::get('post'),true).'</pre>';    
        $my_text .= 'project_id <pre>'.print_r($project_id,true).'</pre>';
        $my_text .= 'project_team_id <pre>'.print_r($project_team_id,true).'</pre>';
        $my_text .= 'team_id <pre>'.print_r($team_id,true).'</pre>';
        $my_text .= 'club_id <pre>'.print_r($club_id,true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text);
        
        }
        
        //$app->enqueueMessage(JText::_('addSubmenu project_id<br><pre>'.print_r($project_id,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' joomla version -> <br><pre>'.print_r(COM_SPORTSMANAGEMENT_JOOMLAVERSION,true).'</pre>'),'');
        
        if(version_compare(JVERSION,'3.0.0','ge')) 
        {
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_MENU'),
			'index.php?option=com_sportsmanagement',
			$submenu == 'cpanel'
		);
        
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_PROJECTS'),
			'index.php?option=com_sportsmanagement&view=projects',
			$submenu == 'projects'
		);

        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_PREDICTIONS'),
			'index.php?option=com_sportsmanagement&view=predictions',
			$submenu == 'predictions'
		);
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_CURRENT_SEASONS'),
			'index.php?option=com_sportsmanagement&view=currentseasons',
			$submenu == 'currentseasons'
		);
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_GOOGLE_CALENDAR'),
			'index.php?option=com_sportsmanagement&view=jsmgcalendars',
			$submenu == 'googlecalendar'
		);
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_EXTENSIONS'),
			'index.php?option=com_sportsmanagement&view=extensions',
			$submenu == 'extensions'
		);
        JHtmlSidebar::addEntry(
			JText::_('COM_SPORTSMANAGEMENT_SUBMENU_SPECIAL_EXTENSIONS'),
			'index.php?option=com_sportsmanagement&view=specialextensions',
			$submenu == 'specialextensions'
		);
        
        
        }
        else
        {    
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_MENU'), 'index.php?option=com_sportsmanagement', $submenu == 'cpanel');
		
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_EXTENSIONS'), 'index.php?option=com_sportsmanagement&view=extensions', $submenu == 'extensions');
        
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_PROJECTS'), 'index.php?option=com_sportsmanagement&view=projects', $submenu == 'projects');
        
        if ( $project_id != 0 )
        {
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_PROJECTS_DETAILS'), 'index.php?option=com_sportsmanagement&view=project&layout=panel&id='.$project_id, $submenu == 'project');
        }
        else
        {
        }
        
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_PREDICTIONS'), 'index.php?option=com_sportsmanagement&view=predictions', $submenu == 'predictions');
        
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_CURRENT_SEASONS'), 'index.php?option=com_sportsmanagement&view=currentseasons', $submenu == 'currentseasons');
        
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_GOOGLE_CALENDAR'), 'index.php?option=com_sportsmanagement&view=jsmgcalendars', $submenu == 'googlecalendar');
        
        JSubMenuHelper::addEntry(JText::_('COM_SPORTSMANAGEMENT_SUBMENU_SPECIAL_EXTENSIONS'), 'index.php?option=com_sportsmanagement&view=specialextensions', $submenu == 'specialextensions');
        
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-helloworld {background-image: url(../media/com_sportsmanagement/images/tux-48x48.png);}');
		if ($submenu == 'extensions') 
		{
			$document->setTitle(JText::_('COM_SPORTSMANAGEMENT_ADMINISTRATION_EXTENSIONS'));
		}
        }
	}
    
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_sportsmanagement';
		}
		else {
			$assetName = 'com_sportsmanagement.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
    
    /**
	 * 
	 * @param string $data
	 * @param string $file
	 * @return object
	 */
	static function getExtendedStatistic($data='', $file, $format='ini') 
    {
        $app = JFactory::getApplication();
		//$xmlfile = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'extended'.DS.$file.'.xml';
		$templatepath = JPATH_COMPONENT_ADMINISTRATOR.DS.'statistics';
        $xmlfile = $templatepath.DS.$file.'.xml';
        
        //$app->enqueueMessage(JText::_('sportsmanagementHelper data<br><pre>'.print_r($data,true).'</pre>'),'Notice');
        //$app->enqueueMessage(JText::_('sportsmanagementHelper getExtendedStatistic<br><pre>'.print_r($jRegistry,true).'</pre>'),'Notice');
        
		$extended = JForm::getInstance('params', $xmlfile,
				array('control'=> 'params'),
				false, '/config');
		$extended->bind($data);
		return $extended;
	}
    
  
  	/**
	 * support for extensions which can overload extended data
	 * @param string $data
	 * @param string $file
	 * @return object
	 */
	static function getExtended($data='', $file, $format='ini') 
    {
        $app = JFactory::getApplication();
		$xmlfile = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'extended'.DS.$file.'.xml';
		
    /*
    //extension management
		$extensions = sportsmanagementHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$JLGPATH_EXTENSION = JPATH_COMPONENT_SITE.DS.'extensions'.DS.$extension.DS.'admin';
			//General extension extended xml 
			$file = $JLGPATH_EXTENSION.DS.'assets'.DS.'extended'.DS.$file.'.xml';
			if(file_exists(JPath::clean($file))) {
				$xmlfile = $file;
				break; //first extension file will win
			}
		}
		*/
		
		/*
		 * extended data
		*/
		
        $jRegistry = new JRegistry;
		//$jRegistry->loadString($data, $format);
        
        if(version_compare(JVERSION,'3.0.0','ge')) 
        {
        
        $jRegistry->loadString($data); 
        }
        else
        {
        $jRegistry->loadJSON($data);   
        }
        
        //$app->enqueueMessage(JText::_('sportsmanagementHelper data<br><pre>'.print_r($data,true).'</pre>'),'Notice');
        //$app->enqueueMessage(JText::_('sportsmanagementHelper getExtended<br><pre>'.print_r($jRegistry,true).'</pre>'),'Notice');
        
		$extended = JForm::getInstance('extended', $xmlfile,
				array('control'=> 'extended'),
				false, '/config');
		$extended->bind($jRegistry);
		return $extended;
	}
    
    
    /**
     * sportsmanagementHelper::getExtendedUser()
     * 
     * @param string $data
     * @param mixed $file
     * @param string $format
     * @return
     */
    static function getExtendedUser($data='', $file, $format='ini') 
    {
        $app = JFactory::getApplication();
		$xmlfile = JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'extendeduser'.DS.$file.'.xml';
		
    /*
    //extension management
		$extensions = sportsmanagementHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$JLGPATH_EXTENSION = JPATH_COMPONENT_SITE.DS.'extensions'.DS.$extension.DS.'admin';
			//General extension extended xml 
			$file = $JLGPATH_EXTENSION.DS.'assets'.DS.'extended'.DS.$file.'.xml';
			if(file_exists(JPath::clean($file))) {
				$xmlfile = $file;
				break; //first extension file will win
			}
		}
		*/
		
		/*
		 * extended data
		*/
		
        if (JFile::exists($xmlfile))
        {
        $jRegistry = new JRegistry;
		//$jRegistry->loadString($data, $format);
        if(version_compare(JVERSION,'3.0.0','ge')) 
        {
        $jRegistry->loadString($data);
        }
        else
        {
        $jRegistry->loadJSON($data);    
        }
        
        //$app->enqueueMessage(JText::_('sportsmanagementHelper data<br><pre>'.print_r($data,true).'</pre>'),'Notice');
        //$app->enqueueMessage(JText::_('sportsmanagementHelper getExtended<br><pre>'.print_r($jRegistry,true).'</pre>'),'Notice');
        
		$extended = JForm::getInstance('extendeduser', $xmlfile,
				array('control'=> 'extendeduser'),
				false, '/config');
		$extended->bind($jRegistry);
		return $extended;
        }
        else
        {
            return false;
        }
	}
    
	/**
	 * Method to return a project array (id,name)
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	public static function getProjects()
	{
		$db = sportsmanagementHelper::getDBConnection();

		$query='	SELECT	id,
							name

					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project
					ORDER BY ordering, name ASC';

		$db->setQuery($query);

		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}

//	/**
//	 * Method to return the project teams array (id,name)
//	 *
//	 * @access	public
//	 * @return	array
//	 * @since	0.1
//	 */
//	function getProjectteams($project_id)
//	{
//		$db = sportsmanagementHelper::getDBConnection();
//		$query='	SELECT	pt.id AS value,
//							t.name AS text,
//							t.notes
//
//					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t
//					LEFT JOIN #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt ON pt.team_id=t.id
//					WHERE pt.project_id='.$project_id.'
//					ORDER BY name ASC ';
//
//		$db->setQuery($query);
//		if (!$result=$db->loadObjectList())
//		{
//			$this->setError($db->getErrorMsg());
//			return false;
//		}
//		else
//		{
//			return $result;
//		}
//	}

//	/**
//	 * Method to return the project teams array (id,name)
//	 *
//	 * @access	public
//	 * @return	array
//	 * @since	1.5.03a
//	 */
//	function getProjectteamsNew($project_id)
//	{
//		$db = sportsmanagementHelper::getDBConnection();
//
//		$query='	SELECT	pt.team_id AS value,
//							t.name AS text,
//							t.notes
//
//					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t
//					LEFT JOIN #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team AS pt ON pt.team_id=t.id
//					WHERE pt.project_id='.(int) $project_id.'
//					ORDER BY name ASC ';
//
//		$db->setQuery($query);
//		if (!$result=$db->loadObjectList())
//		{
//			$this->setError($db->getErrorMsg());
//			return false;
//		}
//		else
//		{
//			return $result;
//		}
//	}

	/**
	 * sportsmanagementHelper::getProjectFavTeams()
	 * 
	 * @param mixed $project_id
	 * @return
	 */
	public static function getProjectFavTeams($project_id)
	{
		
        if ( $project_id )
        {
        $row = JTable::getInstance( 'project', 'sportsmanagementTable' );
        $row->load( $project_id );
        return $row;
        }
        else
        {
        return false;    
        }
//        $db = sportsmanagementHelper::getDBConnection();
//
//		$query='	SELECT fav_team,
//							fav_team_color,
//					fav_team_text_color,
//							fav_team_highlight_type,
//							fav_team_text_bold
//
//					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project
//					WHERE id='.(int) $project_id;
//
//		$db->setQuery($query);
//		if (!$result=$db->loadObject())
//		{
//			$this->setError($db->getErrorMsg());
//			return false;
//		}
//		else
//		{
//			return $result;
//		}
	}

	
    /**
	 * Method to return the project
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	function getTeamplayerProject($projectteam_id)
	{
		$db = sportsmanagementHelper::getDBConnection();
		$query='SELECT project_id FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team WHERE id='.(int) $projectteam_id;
		$db->setQuery($query);
		if ( !$result = $db->loadResult() )
		{
			//$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}
    
    /**
	 * Method to return a SportsType name
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	public static function getSportsTypeName($sportsType)
	{
		$db = sportsmanagementHelper::getDBConnection();
		$query = 'SELECT name FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type WHERE id='.(int) $sportsType;
		$db->setQuery($query);
		if (!$result=$db->loadResult())
		{
			//$this->setError($db->getErrorMsg());
			return false;
		}
		return JText::_($result);
	}

	/**
	 * Method to return a sportsTypees array (id,name)
	 *
	 * @access	public
	 * @return	array seasons
	 * @since	1.5.0a
	 */
	function getSportsTypes()
	{
		$db = sportsmanagementHelper::getDBConnection();
		$query='SELECT id, name FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_sports_type ORDER BY name ASC ';
		$db->setQuery($query);
		if ( !$result = $db->loadObjectList() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		foreach ($result as $sportstype){
			$sportstype->name = JText::_($sportstype->name);
		}
		return $result;
	}

	/**
	 * Method to return a SportsType name
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	public static function getPosPersonTypeName($personType)
	{
		switch ($personType)
		{
			case 2	:	$result =	JText::_('COM_SPORTSMANAGEMENT_F_TEAM_STAFF');
			break;
			case 3	:	$result =	JText::_('COM_SPORTSMANAGEMENT_F_REFEREES');
			break;
			case 4	:	$result =	JText::_('COM_SPORTSMANAGEMENT_F_CLUB_STAFF');
			break;
			default	:
			case 1	:	$result =	JText::_('COM_SPORTSMANAGEMENT_F_PLAYERS');
			break;
		}
		return $result;
	}

	/**
	 * return name of extension assigned to current project.
	 * @param int project_id
	 * @return string or false
	 */
	function getExtension($project_id=0)
	{
		$option = 'com_sportsmanagement';
		if (!$project_id)
		{
			$app = JFactory::getApplication();
			$project_id = $app->getUserState($option.'project', 0);
		}
		if (!$project_id)
		{
			return false;
		}

		$db = sportsmanagementHelper::getDBConnection();
        $query = $db->getQuery(true);
        $query->select('extension');
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project');
        $query->where('id ='. $db->Quote((int)$project_id) );
        
//		$query='SELECT extension FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project WHERE id='. $db->Quote((int)$project_id);
		$db->setQuery($query);
		$res = $db->loadResult();

		return (!empty($res) ? $res : false);
	}

	/**
	 * sportsmanagementHelper::getExtensions()
	 * 
	 * @return
	 */
	public static function getExtensions()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
        $option = 'com_sportsmanagement';
        $view = $jinput->get('view');
		$arrExtensions = array();
		$excludeExtension = array();
		
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' view<br><pre>'.print_r($view,true).'</pre>'),'Notice');
        
		if(JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions')) {
			$folderExtensions  = JFolder::folders(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions',
													'.', false, false, $excludeExtension);
			if($folderExtensions !== false) {
				foreach ($folderExtensions as $ext)
				{
					//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' ext<br><pre>'.print_r($ext,true).'</pre>'),'Notice');
                    
                    if ( $ext == $view )
                    {
                    $arrExtensions[] = $ext;
                    }
				}
			}
		}

		return $arrExtensions;
	}
    
//	public static function getExtensions($project_id)
//	{
//		$option='com_sportsmanagement';
//		$arrExtensions = array();
//		$excludeExtension = array();
//		if ($project_id) {
//			$db= sportsmanagementHelper::getDBConnection();
//			$query='SELECT extension FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project WHERE id='. $db->Quote((int)$project_id);
//
//			$db->setQuery($query);
//			$res=$db->loadObject();
//			if(!empty($res)) {
//				$excludeExtension = explode(",", $res->extension);
//			}
//		}
//		if(JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions')) {
//			$folderExtensions  = JFolder::folders(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions',
//													'.', false, false, $excludeExtension);
//			if($folderExtensions !== false) {
//				foreach ($folderExtensions as $ext)
//				{
//					$arrExtensions[] = $ext;
//				}
//			}
//		}
//
//		return $arrExtensions;
//	}
	
		/**
		 * sportsmanagementHelper::getExtensionsOverlay()
		 * 
		 * @param mixed $project_id
		 * @return
		 */
		public static function getExtensionsOverlay($project_id)
	{
		$option = 'com_sportsmanagement';
		$arrExtensions = array();
		$excludeExtension = array();
		if ($project_id) {
			$db = sportsmanagementHelper::getDBConnection();
			$query = 'SELECT extension FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_project WHERE id='. $db->Quote((int)$project_id);

			$db->setQuery($query);
			$res=$db->loadObject();
			if(!empty($res)) {
				$excludeExtension = explode(",", $res->extension);
			}
		}
		if ( JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions-overlay') ) {
			$folderExtensions  = JFolder::folders(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.'extensions-overlay',
													'.', false, false, $excludeExtension);
			if($folderExtensions !== false) {
				foreach ($folderExtensions as $ext)
				{
					$arrExtensions[] = $ext;
				}
			}
		}

		return $arrExtensions;
	}

	/**
	 * returns number of years between 2 dates
	 *
	 * @param string $birthday date in YYYY-mm-dd format
	 * @param string $current_date date in YYYY-mm-dd format,default to today
	 * @return int age
	 */
	public static function getAge($date, $seconddate)
	{

		if ( ($date != "0000-00-00") &&
		(preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$date,$regs) ) &&
		($seconddate == "0000-00-00") )
		{
			$intAge = date('Y') - $regs[1];
			if($regs[2] > date('m'))
			{
				$intAge--;
			}
			else
			{
				if($regs[2] == date('m'))
				{
					if($regs[3] > date('d')) $intAge--;
				}
			}
			return $intAge;
		}

		if ( ($date != "0000-00-00") &&
		( preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$date,$regs) ) &&
		($seconddate != "0000-00-00") &&
		( preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$seconddate,$regs2) ) )
		{
			$intAge=$regs2[1] - $regs[1];
			if($regs[2] > $regs2[2])
			{
				$intAge--;
			}
			else
			{
				if($regs[2] == $regs2[2])
				{
					if($regs[3] > $regs2[3] ) $intAge--;
				}
			}
			return $intAge;
		}

		return '-';
	}

	/**
	 * returns the default placeholder
	 *
	 * @param string $type ,default is player
	 * @return string placeholder (path)
	 */
	public static function getDefaultPlaceholder($type="player")
	{	
		$params		 	=	JComponentHelper::getParams('com_sportsmanagement');
		$ph_player		=	$params->get('ph_player',0);
		$ph_logo_big	=	$params->get('ph_logo_big',0);	
		$ph_logo_medium	=	$params->get('ph_logo_medium',0);		
		$ph_logo_small	=	$params->get('ph_logo_small',0);
		$ph_icon		=	$params->get('ph_icon', 'images/com_sportsmanagement/database/placeholders/placeholder_21.png');			
		$ph_team		=	$params->get('ph_team',0);
		
			//setup the different placeholders
			switch ($type) {
				case "player": //player
					return $ph_player;
					break;
				case "clublogobig": //club logo big
                case "logo_big":
					return $ph_logo_big;
					break;
				case "clublogomedium": //club logo medium
                case "logo_middle":
					return $ph_logo_medium;
					break;
				case "clublogosmall": //club logo small
                case "logo_small":
					return $ph_logo_small;
					break;
				case "icon": //icon
					return $ph_icon;
					break;
				case "team": //team picture
                case "team_picture":
                case "projectteam_picture":
					return $ph_team;;
					break;					
				default:
					$picture=null;
				break;
			}	
	}
	
	/**
	 *
	 * static method which return a <img> tag with the given picture
	 * @param string $picture
	 * @param string $alttext
	 * @param int $width=40, if set to 0 the original picture width will be used
	 * @param int $height=40, if set to 0 the original picture height will be used
	 * @param int $type=0, 0=player, 1=club logo big, 2=club logo medium, 3=club logo small
	 * @return string
	 */
	public static function getPictureThumb($picture, $alttext, $width=40, $height=40, $type=0)
	{
		$ret = "";
		$picturepath 	= 	JPath::clean(JPATH_SITE.DS.str_replace(JPATH_SITE.DS, '', $picture));
		$params		 	=	JComponentHelper::getParams('com_sportsmanagement');
		$ph_player		=	$params->get('ph_player',0);
		$ph_logo_big	=	$params->get('ph_logo_big',0);	
		$ph_logo_medium	=	$params->get('ph_logo_medium',0);		
		$ph_logo_small	=	$params->get('ph_logo_small',0);
		$ph_icon		=	$params->get('ph_icon',0);			
		$ph_team		=	$params->get('ph_team',0);
		
		if (!file_exists($picturepath) || $picturepath == JPATH_SITE.DS)
		{
			//setup the different placeholders
			switch ($type) {
				case 0: //player
					$picture = JPATH_SITE.DS.$ph_player;
					break;
				case 1: //club logo big
					$picture = JPATH_SITE.DS.$ph_logo_big;
					break;
				case 2: //club logo medium
					$picture = JPATH_SITE.DS.$ph_logo_medium;
					break;
				case 3: //club logo small
					$picture = JPATH_SITE.DS.$ph_logo_small;
					break;
				case 4: //icon
					$picture = JPATH_SITE.DS.$ph_icon;
					break;
				case 5: //team picture
					$picture = JPATH_SITE.DS.$ph_team;
					break;					
				default:
					$picture = null;
				break;
			}
		}
		if (!empty($picture))
		{
			$params = JComponentHelper::getParams('com_sportsmanagement');
			$format = "JPG"; //PNG is not working in IE8
			$format = $params->get('thumbformat', 'PNG');
			$bUseThumbLib = $params->get('usethumblib', false);
			if($bUseThumbLib) {
				if (file_exists($picturepath)) {
					$picture = $picturepath;
				}
				$thumb = PhpThumbFactory::create($picture);
				$thumb->setFormat($format);

				//height and width set, resize it with the thumblib
				if($height>0 && $width>0) {
					$thumb->setMaxHeight($height);
					$thumb->adaptiveResizeQuadrant ($width, $height, $quadrant = 'C');
					$pic = $thumb->getImageAsString();
					$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
					$ret .='" alt="'.$alttext.'" title="'.$alttext.'"/>';
				}
				//height==0 and width set, let the browser resize it
				if($height == 0 && $width >0) {
					$thumb->setMaxWidth($width);
					$pic = $thumb->getImageAsString();
					$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
					$ret .= '" width="'.$width.'" alt="'.$alttext.'" title="'.$alttext.'"/>';
				}
				//width==0 and height set, let the browser resize it
				if($height>0 && $width==0) {
					$thumb->setMaxHeight($height);
					$pic = $thumb->getImageAsString();
					$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
					$ret .= '" height="'.$height.'" alt="'.$alttext.'" title="'.$alttext.'"/>';
				}
				//width==0 and height==0, use original picture size
				if($height==0 && $width==0) {
					$thumb->setMaxHeight($height);
					$pic = $thumb->getImageAsString();
					$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
					$ret .= '" alt="'.$alttext.'" title="'.$alttext.'"/>';
				}
			} else {
				$picture = JURI::root(true).'/'.str_replace(JPATH_SITE.DS, "", $picture);
				$title = $alttext;
				//height and width set, let the browser resize it
				$bUseHighslide = $params->get('use_highslide', false);
				if($bUseHighslide && $type != 4) {
					$title .= ' (' . JText::_('COM_SPORTSMANAGEMENT_GLOBAL_CLICK_TO_ENLARGE') . ')';
					$ret .= '<a href="'.$picture.'" class="highslide">';
				}
				$ret .= '<img ';
				$ret .= ' ';
				if($height > 0 && $width > 0) {
					$ret .= ' src="'.$picture;
					$ret .= '" width="'.$width.'" height="'.$height.'"
							alt="'.$alttext.'" title="'.$title.'"';
				}
				//height==0 and width set, let the browser resize it
				if($height == 0 && $width > 0) {
					$ret .= ' src="'.$picture;
					$ret .= '" width="'.$width.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height set, let the browser resize it
				if($height > 0 && $width == 0) {
					$ret .= ' src="'.$picture;
					$ret .= '" height="'.$height.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height==0, use original picture size
				if($height == 0 && $width == 0) {
					$ret .= ' src="'.$picture;
					$ret .= '" alt="'.$alttext.'" title="'.$title.'"';
				}
				$ret .= '/>';
				if($bUseHighslide) {
					$ret .= '</a>';
				}
			}
				
		}
			
		return $ret;
	}

	/**
	 * static method which extends template path for given view names
	 * Can be used by views to search for extensions that implement parts of common views
	 * and add their path to the template search path.
	 * (e.g. 'projectheading', 'backbutton', 'footer')
	 * @param array(string) $viewnames, names of views for which templates need to be loaded,
	 *                      so that extensions are used when available
	 * @param JLGView       $view to which the template paths should be added
	 */
	public static function addTemplatePaths($templatesToLoad, &$view)
	{
		$extensions = sportsmanagementHelper::getExtensions(JRequest::getInt('p'));
		foreach ($templatesToLoad as $template)
		{
			$view->addTemplatePath(JPATH_COMPONENT . DS . 'views' . DS . $template . DS . 'tmpl');
			if (is_array($extensions) && count($extensions) > 0)
			{
				foreach ($extensions as $e => $extension) 
				{
					$extension_views = JPATH_COMPONENT_SITE . DS . 'extensions' . DS . $extension . DS . 'views';
					$tmpl_path = $extension_views . DS . $template . DS . 'tmpl';
					if (JFolder::exists($tmpl_path))
					{
						$view->addTemplatePath($tmpl_path);
					}
				}
			}
		}
	}


	/**
	 * sportsmanagementHelper::getTimestamp()
	 * 
	 * @param mixed $date
	 * @param integer $use_offset
	 * @param mixed $offset
	 * @return
	 */
	public static function getTimestamp($date = null, $use_offset = 0, $offset = null)
	{
		$date = $date ? $date : 'now';
		$app = JFactory::getApplication();
		$res = JFactory::getDate(strtotime($date));

		//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' date<br><pre>'.print_r($date,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' use_offset<br><pre>'.print_r($use_offset,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' offset<br><pre>'.print_r($offset,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' res<br><pre>'.print_r($res,true).'</pre>'),'');
        
        if ($use_offset)
		{
			if ($offset)
			{
				$serveroffset = explode(':', $offset);
                
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' serveroffset<br><pre>'.print_r($serveroffset,true).'</pre>'),'');
                
                if(version_compare(JVERSION,'3.0.0','ge')) 
                {
                //$res->setTimezone($serveroffset[0]);   
                $res->setTimezone(new DateTimeZone($serveroffset[0])); 
                }
                else
                {
				$res->setOffset($serveroffset[0]);
                }
			}
			else
			{
			 
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' offset<br><pre>'.print_r($app->getCfg('offset'),true).'</pre>'),'');
			
            if(version_compare(JVERSION,'3.0.0','ge')) 
            {
                //$res->setTimezone($app->getCfg('offset'));
                $res->setTimezone(new DateTimeZone($app->getCfg('offset')));
            }
            else
            {
				$res->setOffset($app->getCfg('offset'));
            }
			
            }
		}
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' res<br><pre>'.print_r($res,true).'</pre>'),'');
        
		return $res->toUnix('true');
	}

	/**
	 * Method to convert a date from 0000-00-00 to 00-00-0000 or back
	 * return a date string
	 * $direction == 1 means from convert from 0000-00-00 to 00-00-0000
	 * $direction != 1 means from convert from 00-00-0000 to 0000-00-00
	 * call by sportsmanagementHelper::convertDate($date) inside the script
	 *
	 * When no "-" are given in $date two short date formats (DDMMYYYY and DDMMYY) are supported
	 * for example "31122011" or "311211" for 31 december 2011
	 * 
	 * @access	public
	 * @return	array
	 *
	 */
	static function convertDate($DummyDate,$direction=1)
	{
		if(!strpos($DummyDate, "-") !==false)
		{
			// for example 31122011 is used for 31 december 2011
			if (strlen($DummyDate) == 8 )
			{
				$result  = substr($DummyDate,4,4);
				$result .= '-';
				$result .= substr($DummyDate,2,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
			// for example 311211 is used for 31 december 2011
			elseif (strlen($DummyDate) == 6 )
 			{
 				$result  = substr(date("Y"),0,2);
				$result .= substr($DummyDate,4,4);
				$result .= '-';
				$result .= substr($DummyDate,2,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
		}
		else
		{

			if ($direction == 1)
			{
				$result  = substr($DummyDate,8);
				$result .= '-';
				$result .= substr($DummyDate,5,2);
				$result .= '-';
				$result .= substr($DummyDate,0,4);
			}
			else
			{
				$result  = substr($DummyDate,6,4);
				$result .= '-';
				$result .= substr($DummyDate,3,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
		}

		return $result;
	}

	/**
	 * sportsmanagementHelper::showTeamIcons()
	 * 
	 * @param mixed $team
	 * @param mixed $config
	 * @return
	 */
	public static function showTeamIcons(&$team,&$config,$cfg_which_database = 0,$s=0)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($team,true).'</pre>'),'');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
            {
                $my_text = 'team <br><pre>'.print_r($team,true).'</pre>';    
        sportsmanagementHelper::$_success_text[__METHOD__][__LINE__] = $my_text;
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.'<br><pre>'.print_r($team,true).'</pre>'),'');
            }
            
        if(!isset($team->projectteamid)) return "";
		$projectteamid = $team->projectteam_slug;
		$teamname      = $team->name;
		$teamid        = $team->team_id;
		$teamSlug      = (isset($team->team_slug) ? $team->team_slug : $teamid);
		$clubSlug      = (isset($team->club_slug) ? $team->club_slug : $team->club_id);
		$division_slug = (isset($team->division_slug) ? $team->division_slug : $team->division_id);
		$projectSlug   = (isset($team->project_slug) ? $team->project_slug : $team->project_id);
		$output        = '';

		if ($config['show_team_link'])
		{
		  $routeparameter = array();
       $routeparameter['cfg_which_database'] = $cfg_which_database;
       $routeparameter['s'] = $s;
       $routeparameter['p'] = $projectSlug;
       $routeparameter['tid'] = $teamSlug;
       $routeparameter['ptid'] = $projectteamid;
       
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('roster',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_ROSTER_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/team_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if (((!isset($team_plan)) || ($teamid!=$team_plan->id)) && ($config['show_plan_link']))
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['tid'] = $teamSlug;
		$routeparameter['division'] = $division_slug;
		$routeparameter['mode'] = 0;
		$routeparameter['ptid'] = $projectteamid;
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('teamplan',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_TEAMPLAN_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/calendar_icon.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_curve_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['tid1'] = $teamSlug;
		$routeparameter['tid2'] = 0;
		$routeparameter['division'] = $division_slug;

			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('curve',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_CURVE_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/curve_icon.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_teaminfo_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['tid'] = $teamSlug;
		$routeparameter['ptid'] = $projectteamid;

			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('teaminfo',$routeparameter);
            $title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_TEAMINFO_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/teaminfo_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_club_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['cid'] = $clubSlug;
		$routeparameter['task'] = NULL;

       
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('clubinfo',$routeparameter);
			$title=JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_CLUBINFO_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/mail.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_teamstats_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['tid'] = $teamSlug;
		
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('teamstats',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_TEAMSTATS_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/teamstats_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link, $desc);
		}

		if ($config['show_clubplan_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['cid'] = $clubSlug;
		$routeparameter['task'] = NULL;
		
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('clubplan',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_CLUBPLAN_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/clubplan_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link, $desc);
		}
        
        if ($config['show_rivals_link'])
		{
		$routeparameter = array();
        $routeparameter['cfg_which_database'] = $cfg_which_database;
		$routeparameter['s'] = $s;
		$routeparameter['p'] = $projectSlug;
		$routeparameter['tid'] = $teamSlug;
		
			$link = sportsmanagementHelperRoute::getSportsmanagementRoute('rivals',$routeparameter);
			$title = JText::_('COM_SPORTSMANAGEMENT_TEAMICONS_RIVALS_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_sportsmanagement/jl_images/rivals.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link, $desc);
		}

		return $output;
	}

	/**
	 * sportsmanagementHelper::formatTeamName()
	 * 
	 * @param mixed $team
	 * @param mixed $containerprefix
	 * @param mixed $config
	 * @param integer $isfav
	 * @param mixed $link
	 * @return
	 */
	public static function formatTeamName($team,$containerprefix,&$config,$isfav=0,$link=null,$cfg_which_database = 0)
	{
	   $app = JFactory::getApplication();
       
		$output			= '';
		$desc			= '';

		if ((isset($config['results_below'])) && ($config['results_below']) && ($config['show_logo_small']))
		{
			$js_func		= 'visibleMenu';
			$style_append	= 'visibility:hidden';
			$container		= 'span';
		}
		else
		{
			$js_func		= 'switchMenu';
			$style_append	= 'display:none';
			$container		= 'div';
		}
		
		$showIcons =	(
						($config['show_info_link'] == 2) && ($isfav)
					) ||
					(
						($config['show_info_link'] == 1) &&
						(
							$config['show_club_link'] ||
							$config['show_team_link'] ||
							$config['show_curve_link'] ||
							$config['show_plan_link'] ||
							$config['show_teaminfo_link'] ||
							$config['show_teamstats_link'] ||
							$config['show_clubplan_link'] ||
                            $config['show_rivals_link']
						)
					);
		$containerId = $containerprefix.'t'.$team->id.'p'.$team->project_id;
		if ($showIcons)
		{
			$onclick	= $js_func.'(\''.$containerId.'\');return false;';
			$params		= array('onclick' => $onclick);
		}

		$style = 'padding:2px;';
		if ($config['highlight_fav'] && $isfav)
		{
			$favs = self::getProjectFavTeams($team->project_id);
			$style .= ($favs->fav_team_text_bold != '') ? 'font-weight:bold;' : '';
			$style .= (trim($favs->fav_team_text_color) != '') ? 'color:'.trim($favs->fav_team_text_color).';' : '';
			$style .= (trim($favs->fav_team_color) != '') ? 'background-color:'.trim($favs->fav_team_color).';' : '';
		}

		$desc .= '<span style="'.$style.'">';
		
		$formattedTeamName = "";
		if ($config['team_name_format']== 0)
		{
			$formattedTeamName = $team->short_name;
		}
		else if ($config['team_name_format']== 1)
		{
			$formattedTeamName = $team->middle_name;
		}
		if (empty($formattedTeamName))
		{
			$formattedTeamName = $team->name;
		}

		if (($config['team_name_format']== 0) && (!empty($team->short_name))) 
		{
			$desc .=  '<acronym title="'.$team->name.'">'.$team->short_name.'</acronym>';
		}
		else
		{
			$desc .= $formattedTeamName;
		}

		$desc .=  '</span>';

		if ($showIcons)
		{
			$output .= JHtml::link('javascript:void(0);',$desc,$params);
            //$output .= '<ul id="submenu"><li><a id="'.$containerId.'" >'.$formattedTeamName.'</a></li></ul>';
			$output .= '<'.$container.' id="page-'.$containerId.'" style="'.$style_append.';" class="rankingteam">';
            //$output .= '<div id="config-document">';
            //$output .= '<'.$container.' id="page-'.$containerId.'" >';
			$output .= self::showTeamIcons ($team,$config,$cfg_which_database);
			$output .= '</'.$container.'>';
            //$output .= '</div>';
		}
		else
		{
			$output = $desc;
		}
		
		if ($link != null)
		{
			$output = JHtml::link($link, $output);
		}

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' output<br><pre>'.print_r($output,true).'</pre>'),'');

		return $output;
	}

	/**
	 * sportsmanagementHelper::showClubIcon()
	 * 
	 * @param mixed $team
	 * @param integer $type
	 * @param integer $with_space
	 * @return void
	 */
	public static function showClubIcon(&$team,$type=1,$with_space=0)
	{
		if (($type == 1) && (isset($team->country)))
		{
			if ( $team->logo_small != '' )
			{
				echo JHtml::image($team->logo_small, '');
				if ($with_space == 1){
					echo ' style="padding:1px;"';
				}
			}
			else
			{
				echo '&nbsp;';
			}
		}
		elseif (($type == 2) && (isset($team->country)))
		{
			echo JSMCountries::getCountryFlag($team->country);
		}
	}

	

	/**
	 * sportsmanagementHelper::showColorsLegend()
	 * 
	 * @param mixed $colors
	 * @param mixed $divisions
	 * @return void
	 */
	public static function showColorsLegend($colors,$divisions=NULL)
	{
		$jinput = JFactory::getApplication()->input;
		$favshow = $jinput->getString('func', '');
		//$favshow = JRequest::getVar('func', '');
		if (($favshow != 'showCurve') && (sportsmanagementModelProject::$_project->fav_team))
		{
			$fav = array('color'=>sportsmanagementModelProject::$_project->fav_team_color,'description'=> JText::_('COM_SPORTSMANAGEMENT_RANKING_FAVTEAM'));
			array_push($colors,$fav);
		}
        
        if ( !$divisions )
        {
		foreach($colors as $color)
		{
			if (trim($color['description']) != '')
			{
				echo '<td align="center" style="background-color:'.$color['color'].';"><b>'.$color['description'].'</b>&nbsp;</td>';
			}

		}
        }

		foreach( $divisions as $division )
		{
		echo '<tr>';	
		echo '<td align="center" style=""><b>'.$division->name.'</b>&nbsp;</td>';
$jRegistry = new JRegistry;
        if(version_compare(JVERSION,'3.0.0','ge')) 
        {
        $jRegistry->loadString($division->rankingparams); 
        }
        else
        {
        $jRegistry->loadJSON($division->rankingparams);
        }
        $configvalues = $jRegistry->toArray();
        $colors = array();
for($a=1; $a <= sizeof($configvalues[rankingparams]); $a++ )
{
$colors[] = implode(",",$configvalues[rankingparams][$a] );
}   
$configvalues = implode(";",$colors);       
$colors = sportsmanagementModelProject::getColors($configvalues,sportsmanagementModelProject::$cfg_which_database);        			
		foreach($colors as $color)
		{
			if (trim($color['description']) != '')
			{
				//echo '<tr>';
				echo '<td align="center" style="background-color:'.$color['color'].';"><b>'.$color['description'].'</b>&nbsp;</td>';
				//echo '</tr>';	
			}

		}
			
		echo '</tr>';	
		}	
	
	
	}


	/**
	 * Removes invalid XML
	 *
	 * @access public
	 * @param string $value
	 * @return string
	 */
	public function stripInvalidXml($value)
	{
		$ret = '';
		$current = '';
		if (is_null($value)){
			return $ret;
		}

		$length = strlen($value);
		for ($i = 0 ; $i < $length; $i++)
		{
			$current = ord($value{$i});
			if (($current == 0x9) ||
			($current == 0xA) ||
			($current == 0xD) ||
			(($current >= 0x20) && ($current <= 0xD7FF)) ||
			(($current >= 0xE000) && ($current <= 0xFFFD)) ||
			(($current >= 0x10000) && ($current <= 0x10FFFF)))
			{
				$ret .= chr($current);
			}
			else
			{
				$ret .= ' ';
			}
		}
		return $ret;
	}

	/**
	 * sportsmanagementHelper::getVersion()
	 * 
	 * @return
	 */
	public static function getVersion() 
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
       //$db = sportsmanagementHelper::getDBConnection();
       $query = JFactory::getDbo()->getQuery(true);
       // Select some fields
        $query->select('manifest_cache');
		// From the table
		$query->from('#__extensions');
        $query->where('name LIKE '.JFactory::getDbo()->Quote(''.'com_sportsmanagement'.'') );
        JFactory::getDbo()->setQuery( $query );
        
//	   $query->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_sportsmanagement"');
       $manifest_cache = json_decode( JFactory::getDbo()->loadResult(), true );
	   
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' manifest_cache -><br><pre>'.print_r($manifest_cache,true).'</pre>'),'');
       
       return $manifest_cache['version'];	
	}



	/**
	 * returns formatName
	 *
	 * @param prefix
	 * @param firstName
	 * @param nickName
	 * @param lastName
	 * @param format
	 */
	static function formatName($prefix, $firstName, $nickName, $lastName, $format)
	{
		$name = array();
		if ($prefix)
		{
			$name[] = $prefix;
		}
		switch ($format)
		{
			case 0: //Firstname 'Nickname' Lastname
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 1: //Lastname, 'Nickname' Firstname
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 2: //Lastname, Firstname 'Nickname'
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				break;
			case 3: //Firstname Lastname
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 4: //Lastname, Firstname
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 5: //'Nickname' - Firstname Lastname
				if ($nickName != "") {
					$name[] = "'" . $nickName . "' - ";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 6: //'Nickname' - Lastname, Firstname
				if ($nickName != "") {
					$name[] = "'" . $nickName . "' - ";
				}
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 7: //Firstname Lastname (Nickname)
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName ;
				}
				if ($nickName != "") {
					$name[] = "(" . $nickName . ")";
				}
				break;
			case 8: //F. Lastname
				if ($firstName != "") {
					$name[] = $firstName[0] . ".";
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 9: //Lastname, F.
				if ($lastName != "") {
					$name[] = $lastName.",";
				}
				if ($firstName != "") {
					$name[] = $firstName[0] . ".";
				}
				break;
			case 10: //Lastname
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 11: //Firstname 'Nickname' L.
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($lastName != "") {
					$name[] = $lastName[0]. ".";
				}
				break;
			case 12: //Nickname
				if ($nickName != "") {
					$name[] = $nickName;
				}
				break;
			case 13: //Firstname L.
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName[0]. ".";
				}
				break;
			case 14: //Lastname Firstname
				if ($lastName != "") {
					$name[] = $lastName;
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 15: //Lastname newline Firstname
				if ($lastName != "") {
					$name[] = $lastName;
					$name[] = '<br \>';
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 16: //Firstname newline Lastname
				if ($lastName != "") {
					$name[] = $lastName;
					$name[] = '<br \>';
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
		}

		return implode(" ", $name);
	}

	/**
	 * Creates the print button
	 *
	 * @param string $print_link
	 * @param array $config
	 * @since 1.5.2
	 */
	public static function printbutton($print_link, &$config)
	{
		
$app = JFactory::getApplication();        
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' config<br><pre>'.print_r($config,true).'</pre>'),'Notice');

        if ($config['show_print_button'] == 1) {
			JHtml::_('behavior.tooltip');
			$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no';
			// checks template image directory for image, if non found default are loaded
			if ($config['show_icons'] == 1 ) {
				$image = JHtml::image('media/com_sportsmanagement/jl_images/printButton.png', JText::_( 'Print' ));
			} else {
				$image = JText::_( 'Print' );
			}
			if (JRequest::getInt('pop')) {
				//button in popup
				$output = '<a href="javascript: void(0)" onclick="window.print();return false;">'.$image.'</a>';
			} else {
				//button in view
				$overlib = JText::_( 'COM_SPORTSMANAGEMENT_GLOBAL_PRINT_TIP' );
				$text = JText::_( 'COM_SPORTSMANAGEMENT_GLOBAL_PRINT' );

/**
 * welche joomla version 
 * und ist seo eingestellt
 */
        if(version_compare(JVERSION,'3.0.0','ge')) 
        {
        $sef = JFactory::getConfig()->get('sef', false);
        }
        else
        {
		$sef = JFactory::getConfig()->getValue('config.sef', false);
        }
		//$print_urlparams = ($sef ? "/component/1" : "&tmpl=component&print=1");
        $print_urlparams = ($sef ? "?tmpl=component&print=1" : "&tmpl=component&print=1");

				if(is_null($print_link)) {
				    $output	= '<a href="javascript: void(0)" class="editlinktip hasTip" onclick="window.open(window.location.href + \''.$print_urlparams.'\',\'win2\',\''.$status.'\'); return false;" rel="nofollow" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
				} else {
				    $output	= '<a href="'. JRoute::_($print_link) .'" class="editlinktip hasTip" onclick="window.open(window.location.href + \''.$print_urlparams.'\',\'win2\',\''.$status.'\'); return false;" rel="nofollow" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
				}
			}
			return $output;
		}
		return;
	}

	/**
	 * return true if mootools upgrade is enabled
	 *
	 * @return boolean
	 */
	function isMootools12()
	{
		$version = new JVersion();
		if ($version->RELEASE == '1.5' && $version->DEV_LEVEL >= 19 && JPluginHelper::isEnabled( 'system', 'mtupgrade' ) ) {
			return true;
		}
		else {
			return false;
		}
	}
    
    
    /**
     * sportsmanagementHelper::ToolbarButton()
     * 
     * @param mixed $layout
     * @param string $icon_image
     * @param string $alt_text
     * @param string $view
     * @param integer $type
     * @return void
     */
    static function ToolbarButton($layout = Null,$icon_image = 'upload',$alt_text = 'My Label',$view = '',$type=0,$issueview=NULL,$issuelayout=NULL)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
    
$zusatz = '';
$project_id = $jinput->get('pid');  
if ( $project_id )
{
	$zusatz = '&pid='.$project_id;
}
    //$app->enqueueMessage(JText::_('ToolbarButton layout<br><pre>'.print_r(JRequest::getVar('layout'),true).'</pre>'),'Notice');
    //$app->enqueueMessage(JText::_('ToolbarButton get<br><pre>'.print_r($_GET,true).'</pre>'),'Notice');
    
    if ( !$view )
    {
    $view = JRequest::getVar( "view") ;
    }
    
    $modal_popup_width = JComponentHelper::getParams($option)->get('modal_popup_width',0) ;
    $modal_popup_height = JComponentHelper::getParams($option)->get('modal_popup_height',0) ;
    $bar = JToolBar::getInstance('toolbar');
    $page_url = JFilterOutput::ampReplace('index.php?option=com_sportsmanagement&view='.$view.'&tmpl=component&layout='.$layout.'&type='.$type.'&issueview='.$issueview.'&issuelayout='.$issuelayout.$zusatz );
    
    $bar->appendButton('Popup', $icon_image, $alt_text, $page_url, $modal_popup_width, $modal_popup_height);
    
//    $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' modal_popup_width<br><pre>'.print_r($modal_popup_width,true).'</pre>'),'Notice');
//    $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' modal_popup_height<br><pre>'.print_r($modal_popup_height,true).'</pre>'),'Notice');
    

    
    }
    
    
    /**
     * sportsmanagementHelper::ToolbarButtonOnlineHelp()
     * 
     * @return void
     */
    static function ToolbarButtonOnlineHelp()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		$document = JFactory::getDocument();
		$view = $jinput->get( 'view' ) ;
    $layout = $jinput->get( 'layout' ) ;
    $view = ucfirst(strtolower($view));
    $layout = ucfirst(strtolower($layout));
    $document->addScript(JURI::root(true).'/administrator/components/com_sportsmanagement/assets/js/sm_functions.js');
    $window_width = '<script>alert($(window).width()); </script>';
    $window_height = '<script>alert(window.screen.height); </script>';
    
    //$app->enqueueMessage(JText::_('ToolbarButtonOnlineHelp width<br><pre>'.print_r($window_width,true).'</pre>'),'Notice');
    //$app->enqueueMessage(JText::_('ToolbarButtonOnlineHelp width<br><pre>'.print_r($_SESSION,true).'</pre>'),'Notice');
    
    switch ($view)
    {
    case 'Template':
    case 'Predictiontemplate':
    $template_help = $app->getUserState($option.'template_help');
    $view = $view.'_'.$template_help;
    break;
    default:
    break;
    }
    $cfg_help_server = JComponentHelper::getParams($option)->get('cfg_help_server','') ;
    $modal_popup_width = JComponentHelper::getParams($option)->get('modal_popup_width',0) ;
    $modal_popup_height = JComponentHelper::getParams($option)->get('modal_popup_height',0) ;
    $bar = JToolBar::getInstance('toolbar');
    
    if ( $layout )
    {
    $send = '<a class="modal" rel="{handler: \'iframe\', size: {x: '.$modal_popup_width.', y: '.$modal_popup_height.'}}" '.
         ' href="'.$cfg_help_server.'SM-Backend:'.$view.'-'.$layout   .'"><span title="send" class="icon-32-help"></span>'.JText::_('Onlinehilfe').'</a>';    
    }
    else
    {
    $send = '<a class="modal" rel="{handler: \'iframe\', size: {x: '.$modal_popup_width.', y: '.$modal_popup_height.'}}" '.
         ' href="'.$cfg_help_server.'SM-Backend:'.$view.'"><span title="send" class="icon-32-help"></span>'.JText::_('Onlinehilfe').'</a>';
	}
    /*
    $send = '<a class="modal" rel="{handler: \'iframe\', size: {x: '.'<script>width; </script>'.', y: '.$modal_popup_height.'}}" '.
         ' href="'.$cfg_help_server.'SM-Backend:'.$view.'"><span title="send" class="icon-32-help"></span>'.JText::_('Onlinehilfe').'</a>';	
    */
        
        // Add a help button.
		$bar->appendButton('Custom',$send);	
		//$bar->appendButton('Help', $ref, $com, $override, $component);
	}
    
		
	/**
	* return project rounds as array of objects(roundid as value, name as text)
	*
	* @param string $ordering
	* @return array
	*/
	public static function getRoundsOptions($project_id, $ordering = 'ASC', $required = false, $round_ids = NULL, $cfg_which_database = 0)
	{
		$app = JFactory::getApplication();
        $db = self::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        //$query->select('id as value');
        
        if( $app->isAdmin() ) 
{
        $query->select('id AS value');
        }
        else
        {
        $query->select('CONCAT_WS( \':\', id, alias ) AS value');    
        }
        $query->select('name AS text');
        $query->select('id, name, round_date_first, round_date_last, roundcode');
        
        $query->from('#__sportsmanagement_round');
        
        $query->where('project_id = '.$project_id);
        $query->where('published = 1');
        
        if ( $round_ids )
    {
    $query->where('id IN (' . implode(',', $round_ids) . ')');   
    }
        
        $query->order('roundcode '.$ordering);  
        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.' project_id'.'<pre>'.print_r($project_id,true).'</pre>' ),'');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.' ordering'.'<pre>'.print_r($ordering,true).'</pre>' ),'');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.' required'.'<pre>'.print_r($required,true).'</pre>' ),'');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.' round_ids'.'<pre>'.print_r($round_ids,true).'</pre>' ),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__FUNCTION__.' '.'<pre>'.print_r($query->dump(),true).'</pre>' ),'');

		$db->setQuery($query);
		if (!$required)
		{
			$mitems = array(JHtml::_('select.option', '', JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT')));
			return array_merge($mitems, $db->loadObjectList());
		}
		else
		{
			return $db->loadObjectList();
		}
	}
	
	/**
	 * returns -1/0/1 if the team lost/drew/won in specified game, or false if not played/cancelled
	 *  
	 * @param object $game date from match table
	 * @param int $ptid project team id
	 * @return false|int
	 */
	public static function getTeamMatchResult($game, $ptid)
	{
		if (!isset($game->team1_result)) {
			return false;
		}
		if ($game->cancel) {
			return false;
		}
		
		if (!$game->alt_decision)
		{
			$result1 = $game->team1_result;
			$result2 = $game->team2_result;
		}
		else
		{
			$result1 = $game->team1_result_decision;
			$result2 = $game->team2_result_decision;
		}
		if ($result1 == $result2) {
			return 0;
		}
		
		if ($ptid == $game->projectteam1_id) {
			return ($result1 > $result2) ? 1 : -1;
		}
		else {
			return ($result1 > $result2) ? -1 : 1;
		}
	}
    



/**
 * sportsmanagementHelper::getExtraSelectOptions()
 * 
 * @param string $view
 * @param string $field
 * @param bool $template
 * @param integer $fieldtyp
 * @return
 */
public static function getExtraSelectOptions($view = '', $field = '', $template = FALSE, $fieldtyp = 0  )	
{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $select_columns = array();
        $select_values = array();
        $select_options = array();    
    // Create a new query object.		
		$db = sportsmanagementHelper::getDBConnection();
		$query = $db->getQuery(true);
        
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($view,true).'</pre>'),'Notice');    
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($field,true).'</pre>'),'Notice');

        // Select some fields
        if ( $template )
        {
        $query->select('select_columns,select_values');
		// From the table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_user_extra_fields');
        $query->where('template_backend LIKE '. $db->Quote(''.$view.'') );
        $query->where('name LIKE ' . $db->Quote(''.$field.'') );    
        }
        else
        {
        $query->select('select_columns,select_values');
		// From the table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_user_extra_fields');
        $query->where('views_backend LIKE '. $db->Quote(''.$view.'') );
        $query->where('views_backend_field LIKE ' . $db->Quote(''.$field.'') );    
        }
    
		
        $query->where('fieldtyp = ' . $fieldtyp );
        
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        
    $db->setQuery($query);
    $result = $db->loadObject();
    if ( $result)
		{
		  $select_columns = explode(",",$result->select_columns);
          //$select_values = explode(",",$result->select_values);
          
          if ( $result->select_values )
          {
          $select_values = explode(",",$result->select_values);  
          }
          else
          {
          $select_values = explode(",",$result->select_columns);  
          }
          //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($select_columns,true).'</pre>'),'Notice');
          
          foreach($select_columns as $key => $value )
          {
          $temp = new stdClass();
          $temp->value = $value;
          $temp->text = $select_values[$key];
          $select_options[] = $temp;  
          }
          
          return $select_options;
          
		}
		else
        {
//            JError::raiseError(0, $db->getErrorMsg());
            return false;
        }
}    
    
    
    
    
    /**
     * sportsmanagementHelper::checkUserExtraFields()
     * 
     * @param string $template
     * @return
     */
    static function checkUserExtraFields($template = 'backend', $cfg_which_database = 0)
    {
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $db = sportsmanagementHelper::getDBConnection();
        $query = $db->getQuery(true);
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' template<br><pre>'.print_r($template,true).'</pre>'),'Notice');
        
        $query->select('ef.id');
		$query->from('#__sportsmanagement_user_extra_fields as ef ');
        $query->where('ef.template_'.$template.' LIKE ' . $db->Quote(''.JRequest::getVar('view').'') );
    
			$db->setQuery($query);
			if ($db->loadResult())
			{
				return true;
			}
            else
            {
                return false;
            }    
        
    }
    
    
    /**
     * sportsmanagementHelper::getUserExtraFields()
     * 
     * @param mixed $jlid
     * @param string $template
     * @return
     */
    static function getUserExtraFields($jlid, $template = 'backend', $cfg_which_database = 0)
    {
        $app = JFactory::getApplication();
    	$db = sportsmanagementHelper::getDBConnection();
        $query = $db->getQuery(true);
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' db-id<br><pre>'.print_r($jlid,true).'</pre>'),'Notice');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' template<br><pre>'.print_r($template,true).'</pre>'),'Notice');
        
        $query->select('ef.*,ev.fieldvalue as fvalue,ev.id as value_id ');
		$query->from('#__sportsmanagement_user_extra_fields as ef ');
        $query->join('LEFT', '#__sportsmanagement_user_extra_fields_values as ev ON ( ef.id = ev.field_id AND ev.jl_id = '.$jlid .')' );
        $query->where('ef.template_'.$template.' LIKE ' . $db->Quote(''.JRequest::getVar('view').'') );
        //$query->where('ev.jl_id = '.$jlid );
        $query->order('ef.ordering');
        
    
		
		try {
        $db->setQuery($query);
        $result = $db->loadObjectList();
    }
    catch (Exception $e){
//            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
//            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Error');
			$result = false;
		}
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($result,true).'</pre>'),'Error');
        
		return $result;
    
    }
    
    
    /**
     * sportsmanagementHelper::saveExtraFields()
     * 
     * @param mixed $post
     * @param mixed $pid
     * @return void
     */
    public static function saveExtraFields($post, $pid)
  {
    $app = JFactory::getApplication();
       $address_parts = array();
    //$app->enqueueMessage(JText::_('sportsmanagementHelper saveExtraFields<br><pre>'.print_r($post,true).'</pre>'),'Notice');
    
    $db = sportsmanagementHelper::getDBConnection();
  //-------extra fields-----------//
		if(isset($post['extraf']) && count($post['extraf']))
    {
        //$app->enqueueMessage(JText::_('sportsmanagementHelper saveExtraFields<br><pre>'.print_r($post,true).'</pre>'),'Notice');
        //$app->enqueueMessage(JText::_('sportsmanagementHelper saveExtraFields pid<br><pre>'.print_r($pid,true).'</pre>'),'Notice');
			for($p = 0;$p<count($post['extraf']);$p++)
            {
                // Create a new query object.
                $query = $db->getQuery(true);
// delete all
$conditions = array(
	$db->quoteName('field_id') . '='.$post['extra_id'][$p], 
	$db->quoteName('jl_id') . '='.$pid
);
 
$query->delete($db->quoteName('#__sportsmanagement_user_extra_fields_values'));
$query->where($conditions);
 
//$db->setQuery($query);  

	try {
        $db->setQuery($query);
        $result = $db->query();
    }
    catch (Exception $e){
        
        }
        
//if (!$db->query())
//		{
//			
//            $app->enqueueMessage(JText::_('sportsmanagementHelper saveExtraFields delete<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
//		}
        
// Create a new query object.
        $query = $db->getQuery(true);
        // Insert columns.
        $columns = array('field_id','jl_id','fieldvalue');
        // Insert values.
        $values = array($post['extra_id'][$p],$pid,'\''.$post['extraf'][$p].'\'');
        // Prepare the insert query.
        $query
            ->insert($db->quoteName('#__'.COM_SPORTSMANAGEMENT_TABLE.'_user_extra_fields_values'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
        // Set the query using our newly populated query object and execute it.
        //$db->setQuery($query);

	try {
        $db->setQuery($query);
        $result = $db->query();
    }
    catch (Exception $e){
        
        }
        
//if (!$db->query())
//		{
//			
//            $app->enqueueMessage(JText::_('sportsmanagementHelper saveExtraFields insert<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
//		}

			}
		}
  
  }
    
    /**
	* Fetch google map data refere to
	* http://code.google.com/apis/maps/documentation/geocoding/#Geocoding	 
	*/	 	
	public static function getAddressData($address)
	{
	   $app = JFactory::getApplication();

		$url = 'http://maps.google.com/maps/api/geocode/json?' . 'address='.urlencode($address) .'&sensor=false&language=de';
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($url,true).'</pre>'),'');        

		$content = self::getContent($url);
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($content,true).'</pre>'),'');   		

		$status = null;	
		if(!empty($content))
		{
			$json = new JSMServices_JSON();
			$status = $json->decode($content);
		}

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($status,true).'</pre>'),'');   

		return $status;
	}
    
    /**
* @function     getOSMGeoCoords
* @param        $address : string
* @returns      -
* @description  Gets GeoCoords by calling the OpenStreetMap geoencoding API
*/
public function getOSMGeoCoords($address)
{
    $app = JFactory::getApplication();
    $coords = array();
        
    //$address = utf8_encode($address);
    
    // call OSM geoencoding api
    // limit to one result (limit=1) without address details (addressdetails=0)
    // output in JSON
    $geoCodeURL = "http://nominatim.openstreetmap.org/search?format=json&limit=1&addressdetails=1&q=".urlencode($address);

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($geoCodeURL,true).'</pre>'),'');   
    
    $result = json_decode(file_get_contents($geoCodeURL), true);
    
    


/*
[COM_SPORTSMANAGEMENT_SUBLOCALITY_LONG_NAME] => D�rpum
[COM_SPORTSMANAGEMENT_LOCALITY_LONG_NAME] => Bordelum
[COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_1_LONG_NAME] => Schleswig-Holstein
[COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_1_SHORT_NAME] => SH
*/    
    if ( isset($result[0]) )
    {        
    $coords['latitude'] = $result[0]["lat"];
    $coords['longitude'] = $result[0]["lon"];
    $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_1_LONG_NAME'] = $result[0]["address"]["state"];
    
    $coords['COM_SPORTSMANAGEMENT_LOCALITY_LONG_NAME'] = $result[0]["address"]["city"];
    $coords['COM_SPORTSMANAGEMENT_SUBLOCALITY_LONG_NAME'] = $result[0]["address"]["residential"];
    $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_2_LONG_NAME'] = $result[0]["address"]["county"];
    
    }
    
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($result,true).'</pre>'),'');

    return $coords;
}

    /**
     * sportsmanagementHelper::resolveLocation()
     * 
     * @param mixed $address
     * @return
     */
    public static function resolveLocation($address)
	{
		$app = JFactory::getApplication();
		$coords = array();
		$data = self::getAddressData($address);
        
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($address,true).'</pre>'),'');
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($data->status,true).'</pre>'),'');
//$osm = self::getOSMGeoCoords($address);  
        
		if ($data)
        {
			if ($data->status == 'OK')
			{
				self::$latitude  = $data->results[0]->geometry->location->lat;
				$coords['latitude'] = $data->results[0]->geometry->location->lat; 
				self::$longitude = $data->results[0]->geometry->location->lng;
				$coords['longitude'] = $data->results[0]->geometry->location->lng;
				
				for ($a=0; $a < sizeof($data->results[0]->address_components); $a++ )
				{
        switch($data->results[0]->address_components[$a]->types[0])
        {
        case 'administrative_area_level_1':
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_1_LONG_NAME'] = $data->results[0]->address_components[$a]->long_name;
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_1_SHORT_NAME'] = $data->results[0]->address_components[$a]->short_name;
        break;
        
        case 'administrative_area_level_2':
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_2_LONG_NAME'] = $data->results[0]->address_components[$a]->long_name;
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_2_SHORT_NAME'] = $data->results[0]->address_components[$a]->short_name;
        break;
        
        case 'administrative_area_level_3':
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_3_LONG_NAME'] = $data->results[0]->address_components[$a]->long_name;
        $coords['COM_SPORTSMANAGEMENT_ADMINISTRATIVE_AREA_LEVEL_3_SHORT_NAME'] = $data->results[0]->address_components[$a]->short_name;
        break;

        case 'locality':
        $coords['COM_SPORTSMANAGEMENT_LOCALITY_LONG_NAME'] = $data->results[0]->address_components[$a]->long_name;
        break;
        
        case 'sublocality':
        $coords['COM_SPORTSMANAGEMENT_SUBLOCALITY_LONG_NAME'] = $data->results[0]->address_components[$a]->long_name;
        break;
                        
        }
                
        
        }
				
				return $coords;
			}
//            else
//            {
//                $osm = self::getOSMGeoCoords($address);
//            }
          
		}
        
	}
    

	/**
	 * sportsmanagementHelper::getContent()
	 * Return content of the given url
	 * @param mixed $url
	 * @param bool $raw
	 * @param bool $headerOnly
	 * @return
	 */
	static public function getContent($url , $raw = false , $headerOnly = false)
	{
		if (!$url)
			return false;
		
		if (function_exists('curl_init'))
		{
			$ch			= curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, true );
			
			if($raw){
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true );
			}

			$response	= curl_exec($ch);
			
			$curl_errno	= curl_errno($ch);
			$curl_error	= curl_error($ch);
			
			if ($curl_errno!=0)
			{
				$app	= JFactory::getApplication();
				$err		= 'CURL error : '.$curl_errno.' '.$curl_error;
				$app->enqueueMessage($err, 'error');
			}
			
			$code		= curl_getinfo( $ch , CURLINFO_HTTP_CODE );

			// For redirects, we need to handle this properly instead of using CURLOPT_FOLLOWLOCATION
			// as it doesn't work with safe_mode or openbase_dir set.
			if( $code == 301 || $code == 302 )
			{
				list( $headers , $body ) = explode( "\r\n\r\n" , $response , 2 );
				
				preg_match( "/(Location:|URI:)(.*?)\n/" , $headers , $matches );
				
				if( !empty( $matches ) && isset( $matches[2] ) )
				{
					$url	= JString::trim( $matches[2] );
					curl_setopt( $ch , CURLOPT_URL , $url );
					curl_setopt( $ch , CURLOPT_RETURNTRANSFER, 1);
					curl_setopt( $ch , CURLOPT_HEADER, true );
					$response	= curl_exec( $ch );
				}
			}
			
			
			if (!$raw){
				list( $headers , $body )	= explode( "\r\n\r\n" , $response , 2 );
			}
			
			$ret	= $raw ? $response : $body;
			$ret	= $headerOnly ? $headers : $ret;
			
			curl_close($ch);
			return $ret;
		}
	
		// CURL unavailable on this install
		return false;
	}
    
    /**
     * sportsmanagementHelper::getPictureClub()
     * 
     * @param mixed $id
     * @return
     */
    static function getPictureClub($id)
    {
        $app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $db = sportsmanagementHelper::getDBConnection();
    // Create a new query object.
        $query = $db->getQuery(true);
        $query->select(array('logo_big'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_club')
        ->where('id = '.$id);    
        $db->setQuery($query);
        $picture = $db->loadResult();
        if (JFile::exists(JPATH_SITE.DS.$picture))
        {
            // alles ok
        }
        else
        {
            $picture = JComponentHelper::getParams($option)->get('ph_logo_big', '');
        }
		return $picture;
    }
    
    /**
     * sportsmanagementHelper::getPicturePlayground()
     * 
     * @param mixed $id
     * @return
     */
    static function getPicturePlayground($id)
    {
        $app = JFactory::getApplication();
        $jinput = $app->input;
		$option = $jinput->getCmd('option');
        $db = sportsmanagementHelper::getDBConnection();
    // Create a new query object.
        $query = $db->getQuery(true);
        $query->select(array('picture'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_playground')
        ->where('id = '.$id);    
        $db->setQuery($query);
        $picture = $db->loadResult();
        if (JFile::exists(JPATH_SITE.DS.$picture))
        {
            // alles ok
        }
        else
        {
            $picture = JComponentHelper::getParams($option)->get('ph_team','');
        }
		return $picture;    
        
    }
    
    /**
     * sportsmanagementHelper::getArticleList()
     * 
     * @param mixed $project_category_id
     * @return
     */
    public static function getArticleList($project_category_id)
    {
        $app = JFactory::getApplication();
        $jinput = $app->input;
		$option = $jinput->getCmd('option');
       // $db = sportsmanagementHelper::getDBConnection();
       
       // wenn der user die k2 komponente
       // in der konfiguration ausgewählt hat,
       // kommt es zu einem fehler, wenn wir darüber selektieren
       
       // ist k2 installiert ?
       if ( JComponentHelper::getParams($option)->get('which_article_component') == 'com_k2' )
       {
       $k2 = JComponentHelper::getComponent('com_k2');
       
       if( !$k2->option )
       {
       $app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_ADMIN_COM_K2_NOT_AVAILABLE'),'Error');
       return false; 
       }
       //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' k2<br><pre>'.print_r($k2,true).'</pre>'),'');
       
       }
       
    // Create a new query object.
        $query = JFactory::getDBO()->getQuery(true);
        
        $query->select('c.id as value,c.title as text');
       
        switch ( JComponentHelper::getParams($option)->get('which_article_component') )
    {
        case 'com_content':
        $query->from('#__content as c');
        break;
        case 'com_k2':
        $query->from('#__k2_items as c');
        break;
        default:
        $query->from('#__content as c');
        break;
    }
    $query->where('catid ='. $project_category_id );
       JFactory::getDBO()->setQuery($query); 
        $result = JFactory::getDBO()->loadObjectList();
        return $result;
    }
    
    
    /**
     * sportsmanagementHelper::time_to_sec()
     * 
     * @param mixed $time
     * @return
     */
    function time_to_sec($time) 
    { 
    $hours = substr($time, 0, -6); 
    $minutes = substr($time, -5, 2); 
    $seconds = substr($time, -2); 

    return $hours * 3600 + $minutes * 60 + $seconds; 
} 

/**
 * sportsmanagementHelper::sec_to_time()
 * 
 * @param mixed $seconds
 * @return
 */
function sec_to_time($seconds) 
{ 
    $hours = floor($seconds / 3600); 
    $minutes = floor($seconds % 3600 / 60); 
    $seconds = $seconds % 60; 

    return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds); 
} 
    

	/**
	 * Internal method to get a JavaScript object notation string from an array
	 *
	 * @param   array  $array  The array to convert to JavaScript object notation
	 *
	 * @return  string  JavaScript object notation representation of the array
	 *
	 * @since   3.0
	 */
	public static function getJSObject(array $array = array())
	{
		$elements = array();

		foreach ($array as $k => $v)
		{
			// Don't encode either of these types
			if (is_null($v) || is_resource($v))
			{
				continue;
			}

			// Safely encode as a Javascript string
			$key = json_encode((string) $k);

			if (is_bool($v))
			{
				$elements[] = $key . ': ' . ($v ? 'true' : 'false');
			}
			elseif (is_numeric($v))
			{
				$elements[] = $key . ': ' . ($v + 0);
			}
			elseif (is_string($v))
			{
				if (strpos($v, '\\') === 0)
				{
					// Items such as functions and JSON objects are prefixed with \, strip the prefix and don't encode them
					$elements[] = $key . ': ' . substr($v, 1);
				}
				else
				{
					// The safest way to insert a string
					$elements[] = $key . ': ' . json_encode((string) $v);
				}
			}
			else
			{
				//$elements[] = $key . ': ' . static::getJSObject(is_object($v) ? get_object_vars($v) : $v);
                $elements[] = $key . ': ' . self::getJSObject(is_object($v) ? get_object_vars($v) : $v);
			}
		}

		return '{' . implode(',', $elements) . '}';
	}








        
}
