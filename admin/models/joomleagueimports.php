<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.modellist');

$maxImportTime = 1920;
if ((int)ini_get('max_execution_time') < $maxImportTime){@set_time_limit($maxImportTime);}

/**
 * sportsmanagementModeljoomleagueimports
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModeljoomleagueimports extends JModelList
{

static $db_num_rows = 0;


/**
 * sportsmanagementModeljoomleagueimports::updateplayerproposition()
 * 
 * @return void
 */
function updateplayerproposition()
{
$app = JFactory::getApplication();
    $db = JFactory::getDbo(); 
    $query = $db->getQuery(true);
    $option = JRequest::getCmd('option');    
    

// Select some fields
            $query = $db->getQuery(true);
            $query->clear();
		    $query->select('tp.project_position_id,tp.import');
            // From joomleague table
		    $query->from('#__joomleague_team_player AS tp');
            $query->where('tp.import != 0');
            $query->group('tp.import');
$db->setQuery($query);
$result = $db->loadObjectList();

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' result<br><pre>'.print_r($result,true).'</pre>'),'');

foreach ( $result as $row )
{
// Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                // Must be a valid primary key value.
                $object->id = $row->import;
                $object->project_position_id = $row->project_position_id;
                // Update their details in the users table using id as the primary key.
                $result = JFactory::getDbo()->updateObject('#__sportsmanagement_season_team_person_id', $object, 'id');
    
}


}


/**
 * sportsmanagementModeljoomleagueimports::updatestaffproposition()
 * 
 * @return void
 */
function updatestaffproposition()
{
$app = JFactory::getApplication();
    $db = JFactory::getDbo(); 
    $query = $db->getQuery(true);
    $option = JRequest::getCmd('option');    
    

// Select some fields
            $query = $db->getQuery(true);
            $query->clear();
		    $query->select('tp.project_position_id,tp.import');
            // From joomleague table
		    $query->from('#__joomleague_team_staff AS tp');
            $query->where('tp.import != 0');
            $query->group('tp.import');
$db->setQuery($query);
$result = $db->loadObjectList();

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' result<br><pre>'.print_r($result,true).'</pre>'),'');

foreach ( $result as $row )
{
// Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                // Must be a valid primary key value.
                $object->id = $row->import;
                $object->project_position_id = $row->project_position_id;
                // Update their details in the users table using id as the primary key.
                $result = JFactory::getDbo()->updateObject('#__sportsmanagement_season_team_person_id', $object, 'id');
    
}


}



/**
 * sportsmanagementModeljoomleagueimports::updatepositions()
 * 
 * @return void
 */
function updatepositions()
{
$app = JFactory::getApplication();
    $db = JFactory::getDbo(); 
    $query = $db->getQuery(true);
    $option = JRequest::getCmd('option');
    
$post = JRequest::get('post');
        
        $pks = JRequest::getVar('cid', null, 'post', 'array');
        
        for ($x=0; $x < count($pks); $x++)
		{
            $position = $post['position'.$pks[$x]];
            $position_old = $pks[$x];

// Fields to update.
$fields = array(
    $db->quoteName('position_id') . ' = ' . $db->quote($position),
    $db->quoteName('jl_update') . ' = 1'
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('position_id') . ' = ' . $db->quote($position_old),
    $db->quoteName('jl_update') . ' = 0'
);


$query->clear(); 
$query->update($db->quoteName('#__sportsmanagement_project_position'))->set($fields)->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
$app->enqueueMessage(JText::sprintf('COM_SPORTSMANAGEMENT_N_ITEMS_PUBLISHED',self::$db_num_rows),'');

$query->clear(); 
$query->update($db->quoteName('#__sportsmanagement_person'))->set($fields)->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
$app->enqueueMessage(JText::sprintf('COM_SPORTSMANAGEMENT_N_ITEMS_PUBLISHED',self::$db_num_rows),'');

		}
        
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' post<br><pre>'.print_r($post,true).'</pre>'),'');    
    
        
}

/**
 * sportsmanagementModeljoomleagueimports::gettotals()
 * 
 * @return
 */
function gettotals()
{
    $app = JFactory::getApplication();
        $db = JFactory::getDbo(); 
        $option = JRequest::getCmd('option');
        
        // retrieve the value of the state variable. First see if the variable has been passed
        // in the request. Otherwise retrieve the stored value. If none of these are specified,
        // the specified default value will be returned
        // function syntax is getUserStateFromRequest( $key, $request, $default );
        
        $jsm_table = $app->getUserStateFromRequest( "$option.jsm_table", 'jsm_table', '' );
        $jl_table = $app->getUserStateFromRequest( "$option.jl_table", 'jl_table', '' );
        $season_id = $app->getUserState( "$option.season_id", '0' );
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jl_table<br><pre>'.print_r($jl_table,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' season_id<br><pre>'.print_r($season_id,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jsm_table<br><pre>'.print_r($jsm_table,true).'</pre>'),'');

        // Das "i" nach der Suchmuster-Begrenzung kennzeichnet eine Suche ohne
            // Ber�cksichtigung von Gro�- und Kleinschreibung
            if ( preg_match("/project_team/i", $jsm_table) ) 
            {
            
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('COUNT(pt.id) AS total');
            $query->from($jl_table.' AS pt');
            $query->join('INNER','#__sportsmanagement_project AS p ON p.id = pt.project_id');
            $query->where('pt.import = 0');
            
            if ( $season_id )
            {
                $query->where('p.season_id = '.$season_id);
            }
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'query<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
            $db->setQuery($query);
            $total = $db->loadResult();
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'total<br><pre>'.print_r($total,true).'</pre>'),'');
            
            return $total;
            }
            
            if (preg_match("/team_player/i", $jsm_table)) 
            {
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('COUNT(tp.id) AS total');
            $query->from($jl_table.' AS tp');
            $query->join('INNER','#__sportsmanagement_project_team AS pt ON pt.id = tp.projectteam_id');
            $query->join('INNER','#__sportsmanagement_project AS p ON p.id = pt.project_id');
            $query->where('tp.import = 0');
            
            if ( $season_id )
            {
                $query->where('p.season_id = '.$season_id);
            }
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'query<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
            $db->setQuery($query);
            $total = $db->loadResult();
            
            return $total;    
            }    


}        

/**
 * sportsmanagementModeljoomleagueimports::checkimport()
 * 
 * @return void
 */
function checkimport()
{
$app = JFactory::getApplication();
        $db = JFactory::getDbo(); 
        $option = JRequest::getCmd('option');
        $post = JRequest::get('post');
        $exportfields = array();
        $cid = $post['cid'];
        $jl = $post['jl'];
        $jsm = $post['jsm'];
        $season_id= $post['filter_season'];
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'post<br><pre>'.print_r($post,true).'</pre>'),'');
        
        
  foreach ( $cid as $key => $value )
        {
        $jsm_table = $jsm[$value];
        $jl_table = $jl[$value];
        
        // Das "i" nach der Suchmuster-Begrenzung kennzeichnet eine Suche ohne
            // Ber�cksichtigung von Gro�- und Kleinschreibung
            if (preg_match("/project_team/i", $jsm_table)) 
            {
                // store the variable that we would like to keep for next time
                // function syntax is setUserState( $key, $value );
                $app->setUserState( "$option.jsm_table", $jsm_table );
                $app->setUserState( "$option.jl_table", $jl_table );
                $app->setUserState( "$option.season_id", $season_id );
                //JRequest::setVar('jsm_table', $jsm_table);
            return true;    
            }
            elseif (preg_match("/team_player/i", $jsm_table)) 
            {
                // store the variable that we would like to keep for next time
                // function syntax is setUserState( $key, $value );
                $app->setUserState( "$option.jsm_table", $jsm_table );
                $app->setUserState( "$option.jl_table", $jl_table );
                $app->setUserState( "$option.season_id", $season_id );
                //JRequest::setVar('jsm_table', $jsm_table);
            return true;    
            }
            elseif (preg_match("/team_staff/i", $jsm_table)) 
            {
                // store the variable that we would like to keep for next time
                // function syntax is setUserState( $key, $value );
                $app->setUserState( "$option.jsm_table", $jsm_table );
                $app->setUserState( "$option.jl_table", $jl_table );
                $app->setUserState( "$option.season_id", $season_id );
                //JRequest::setVar('jsm_table', $jsm_table);
            return true;    
            }
            else
            {
            return false;
            }
        }        
    
}


function importjoomleaguenew()
{
$app = JFactory::getApplication();
$db = JFactory::getDbo(); 
$query = $db->getQuery(true);
$option = JRequest::getCmd('option');
//$post = JRequest::get('post');
$exportfields = array();        
$table_copy = array();        

$table_copy[] = 'club';
$table_copy[] = 'division';
$table_copy[] = 'league';
$table_copy[] = 'match';
$table_copy[] = 'person';
$table_copy[] = 'playground';

$table_copy[] = 'position_statistic';

$table_copy[] = 'project';
$table_copy[] = 'project_position';
$table_copy[] = 'round';
$table_copy[] = 'season';
$table_copy[] = 'statistic';
$table_copy[] = 'team';
$table_copy[] = 'template_config';


$table_copy[] = 'prediction_admin';
$table_copy[] = 'prediction_game';
$table_copy[] = 'prediction_groups';
$table_copy[] = 'prediction_member';
$table_copy[] = 'prediction_project';
$table_copy[] = 'prediction_result';
$table_copy[] = 'prediction_template';
        
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' table_copy <br><pre>'.print_r($table_copy,true).'</pre>'),'');        

// als erstes kommen die tabellen, die nur kopiert werden !        
foreach ( $table_copy as $key => $value )
{
$jsm_table = '#__sportsmanagement_'.$value;
$jl_table = '#__joomleague_'.$value;
                
$jl_fields = $db->getTableFields('#__joomleague_'.$value);
$jsm_fields = $db->getTableFields('#__sportsmanagement_'.$value);

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jl_fields <br><pre>'.print_r($jl_fields,true).'</pre>'),'');
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jsm_fields <br><pre>'.print_r($jsm_fields,true).'</pre>'),'');            

$query = $db->getQuery(true);
$query->clear();
$query->select('COUNT(id) AS total');
$query->from($jsm_table);
$db->setQuery($query);

$totals = $db->loadResult();

if ( $totals )
{
$app->enqueueMessage(JText::_('Daten aus der Tabelle: ( '.$jl_table.' ) koennen nicht kopiert werden. Tabelle: ( '.$jsm_table.' ) nicht leer!'),'Error');     
}
else
{

unset($jsm_field_array);
unset($exportfields);    
$jsm_field_array = $jsm_fields[$jsm_table];
    
foreach ( $jl_fields[$jl_table] as $key2 => $value2 )
            {
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'key2<br><pre>'.print_r($key2,true).'</pre>'),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'value2<br><pre>'.print_r($value2,true).'</pre>'),'');
                
                switch ($key2)
                {
                    case 'import':
                    case 'ordering':
                    case 'checked_out':
                    case 'checked_out_time':
                    case 'modified':
                    case 'modified_by':
                    case 'out':
                    break;
                    default:
                    if (array_key_exists($key2, $jsm_field_array)) 
                    {
                    //$exportfields[] = $db->Quote($key2);
                    $exportfields[] = $key2;
                    }
                    break;
                }
                
            }
            
            $select_fields = implode(',', $exportfields);
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'exportfields<br><pre>'.print_r($exportfields,true).'</pre>'),'');
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'select_fields<br><pre>'.print_r($select_fields,true).'</pre>'),'');
            
            $query = $db->getQuery(true);
            $query->clear();
            $query = 'INSERT INTO '.$jsm_table.' ('.$select_fields.') SELECT '.$select_fields.' FROM '.$jl_table;
            $db->setQuery($query);
            if (!$db->query())
		    {
			$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
		    }
            else
            {
            $app->enqueueMessage(JText::_('Daten aus der Tabelle: ( '.$jl_table.' ) in die Tabelle: ( '.$jsm_table.' ) importiert!'),'Notice');    
            }

}   

         
}

// jetzt kommen die positionen
$jl_position = array(); 

$query = $db->getQuery(true);
$query->clear();
$query->select('*');
$query->from('#__joomleague_position');
$query->where('parent_id = 0');
$db->setQuery($query);
$result = $db->loadObjectList();

if ( $result )
{
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' __joomleague_position -> <br><pre>'.print_r($result,true).'</pre>'),'');  
foreach( $result as $row )
{
    
$query = $db->getQuery(true);
$query->clear();
$query->select('id');
$query->from('#__sportsmanagement_position');
$query->where('name LIKE '. $db->Quote( '' . $row->name . '') );
$db->setQuery($query);
$pos_result = $db->loadResult();

if ( $pos_result )
{
$jl_position[$row->id] = $pos_result;    
}
else
{    
$mdl = JModelLegacy::getInstance("position", "sportsmanagementModel");
$mdlTable = $mdl->getTable();    

$mdlTable->name = $row->name;
$mdlTable->alias = $row->alias;
$mdlTable->parent_id = $row->parent_id;
$mdlTable->persontype = $row->persontype;
$mdlTable->sports_type_id = $row->sports_type_id;
$mdlTable->published = $row->published;

if ($mdlTable->store()===false)
{
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
}
else
{
$jl_position[$row->id] = $db->insertid();
}

}

}
  
}

$query = $db->getQuery(true);
$query->clear();
$query->select('*');
$query->from('#__joomleague_position');
$query->where('parent_id != 0');
$db->setQuery($query);
$result = $db->loadObjectList();

if ( $result )
{
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' __joomleague_position -> <br><pre>'.print_r($result,true).'</pre>'),'');  
foreach( $result as $row )
{

$query = $db->getQuery(true);
$query->clear();
$query->select('id');
$query->from('#__sportsmanagement_position');
$query->where('name LIKE '. $db->Quote( '' . $row->name . '') );
$db->setQuery($query);
$pos_result = $db->loadResult();

if ( $pos_result )
{
$jl_position[$row->id] = $pos_result;    
}
else
{  
$mdl = JModelLegacy::getInstance("position", "sportsmanagementModel");
$mdlTable = $mdl->getTable();    

$mdlTable->name = $row->name;
$mdlTable->alias = $row->alias;
$mdlTable->parent_id = $jl_position[$row->parent_id];
$mdlTable->persontype = $row->persontype;
$mdlTable->sports_type_id = $row->sports_type_id;
$mdlTable->published = $row->published;

if ($mdlTable->store()===false)
{
$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
}
else
{
$jl_position[$row->id] = $db->insertid();
}

}

}
  
}

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jl_position -> <br><pre>'.print_r($jl_position,true).'</pre>'),'');

// dann die positions id�s in den tabellen updaten
foreach( $jl_position as $key => $value )
{
// Fields to update.
    $fields = array(
    $db->quoteName('position_id') .'=\''.$value.'\''
        );
     // Conditions for which records should be updated.
    $conditions = array(
    $db->quoteName('position_id') .'='. $key
    );
    $query->clear();
     $query->update($db->quoteName('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person'))->set($fields)->where($conditions);
     $db->setQuery($query);  
     if (!sportsmanagementModeldatabasetool::runJoomlaQuery())
		{
		    $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
		} 
    $query->clear();    
    $query->update($db->quoteName('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_position'))->set($fields)->where($conditions);
     $db->setQuery($query);  
     if (!sportsmanagementModeldatabasetool::runJoomlaQuery())
		{
		    $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
		}
    $query->clear();    
    $query->update($db->quoteName('#__'.COM_SPORTSMANAGEMENT_TABLE.'_position_statistic'))->set($fields)->where($conditions);
     $db->setQuery($query);  
     if (!sportsmanagementModeldatabasetool::runJoomlaQuery())
		{
		    $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
		}         
           
}



                    
}
        
/**
 * sportsmanagementModeljoomleagueimports::import()
 * 
 * @return void
 */
function import()
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo(); 
        $option = JRequest::getCmd('option');
        $post = JRequest::get('post');
        $exportfields = array();
        $cid = $post['cid'];
        $jl = $post['jl'];
        $jlid = $post['jlid'];
        $jsm = $post['jsm'];
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($post,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($cid,true).'</pre>'),'');
        
        foreach ( $cid as $key => $value )
        {
            $jl_fields = $db->getTableFields($jl[$value]);
            $jsm_fields = $db->getTableFields($jsm[$value]);
            
            $jsm_table = $jsm[$value];
            $jl_table = $jl[$value];
            // wenn in der jsm tabelle eintr�ge vorhanden sind
            // dann wird nicht kopiert. es soll kein schaden entstehen
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('COUNT(id) AS total');
            $query->from($jsm_table);
            $db->setQuery($query);
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'query<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
            
            $totals = $db->loadResult();
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'totals<br><pre>'.print_r($totals,true).'</pre>'),'');
            
            // noch die zu importierenden tabellen pr�fen
            // Das "i" nach der Suchmuster-Begrenzung kennzeichnet eine Suche ohne
            // Ber�cksichtigung von Gro�- und Kleinschreibung
            if ( preg_match("/project_team/i", $jsm_table) || preg_match("/team_player/i", $jsm_table) || preg_match("/team_staff/i", $jsm_table) ) 
            {
            $app->enqueueMessage(JText::_('Sie muessen die Daten aus der Tabelle: ( '.$jl_table.' ) in die neue Struktur umsetzen!'),'');
            // wir m�ssen ein neues feld an die tabelle zum import einf�gen
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' jl_fields<br><pre>'.print_r($jl_fields,true).'</pre>'),'');
            if (array_key_exists('import', $jl_fields[$jl_table]  ) )
            {
                $app->enqueueMessage(JText::_('Importfeld ist vorhanden'),'');
            }
            else
            {
                $app->enqueueMessage(JText::_('importfeld ist nicht vorhanden'),'');
                $query = $db->getQuery(true);
                $query = 'ALTER TABLE '.$jl_table.' ADD import INT(11) NOT NULL DEFAULT 0 ';
                $db->setQuery($query);
                $db->query();
                if ( preg_match("/team_player/i", $jsm_table) ) 
                {
                    $query = $db->getQuery(true);
                    $query = 'ALTER TABLE #__joomleague_match_event ADD import TINYINT(1) NOT NULL DEFAULT 0 ';
                    $db->setQuery($query);
                    $db->query();
                    $query = $db->getQuery(true);
                    $query = 'ALTER TABLE #__joomleague_match_player ADD import TINYINT(1) NOT NULL DEFAULT 0 ';
                    $db->setQuery($query);
                    $db->query();
                    $query = $db->getQuery(true);
                    $query = 'ALTER TABLE #__joomleague_match_referee ADD import TINYINT(1) NOT NULL DEFAULT 0 ';
                    $db->setQuery($query);
                    $db->query();
                    $query = $db->getQuery(true);
                    $query = 'ALTER TABLE #__joomleague_match_staff ADD import TINYINT(1) NOT NULL DEFAULT 0 ';
                    $db->setQuery($query);
                    $db->query();
                
                }
            }
            
            } 
            else 
            {
            

            if ( $totals )
            {
            $app->enqueueMessage(JText::_('Daten aus der Tabelle: ( '.$jl[$value].' ) koennen nicht kopiert werden. Tabelle: ( '.$jsm[$value].' ) nicht leer!'),'Error');     
            }
            else
            {
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($jl_fields,true).'</pre>'),'');
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'jsm_fields<br><pre>'.print_r($jsm_fields,true).'</pre>'),'');
            
            $jsm_field_array = $jsm_fields[$jsm[$value]];
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'jsm_field_array<br><pre>'.print_r($jsm_field_array,true).'</pre>'),'');
            
            foreach ( $jl_fields[$jl[$value]] as $key2 => $value2 )
            {
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'key2<br><pre>'.print_r($key2,true).'</pre>'),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'value2<br><pre>'.print_r($value2,true).'</pre>'),'');
                
                switch ($key2)
                {
                    case 'import':
                    case 'ordering':
                    case 'checked_out':
                    case 'checked_out_time':
                    case 'modified':
                    case 'modified_by':
                    case 'out':
                    break;
                    default:
                    if (array_key_exists($key2, $jsm_field_array)) 
                    {
                    //$exportfields[] = $db->Quote($key2);
                    $exportfields[] = $key2;
                    }
                    break;
                }
                
            }
            
            $select_fields = implode(',', $exportfields);
            
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'exportfields<br><pre>'.print_r($exportfields,true).'</pre>'),'');
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'select_fields<br><pre>'.print_r($select_fields,true).'</pre>'),'');
            
            $query->clear();
            $query = 'INSERT INTO '.$jsm[$value].' ('.$select_fields.') SELECT '.$select_fields.' FROM '.$jl[$value];
            $db->setQuery($query);
            if (!$db->query())
		    {
			$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
		    }
            else
            {
            $app->enqueueMessage(JText::_('Daten aus der Tabelle: ( '.$jl[$value].' ) in die Tabelle: ( '.$jsm[$value].' ) importiert!'),'Notice');    
            }
            
            // Create an object for the record we are going to update.
            $object = new stdClass();
            // Must be a valid primary key value.
            $object->id = $jlid[$value];
            $object->import = 1;
            // Update their details in the users table using id as the primary key.
            $result = JFactory::getDbo()->updateObject('#__'.COM_SPORTSMANAGEMENT_TABLE.'_jl_tables', $object, 'id');   
            
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'query<br><pre>'.print_r($query,true).'</pre>'),'');
            
            unset($exportfields);
            
            // in der template tabelle stehen die parameter nicht im json format
            if (preg_match("/template_config/i", $jsm_table)) 
            {
                $app->enqueueMessage(JText::_('Die Parameter aus der Tabelle: ( '.$jsm_table.' ) werden in das JSON-Format umgesetzt!'),'');
                $query = $db->getQuery(true);
                $query->clear();
                $query->select('id,params,template');
                $query->from($jsm_table);
                $db->setQuery($query);
                $results = $db->loadObjectList();
                
                foreach($results as $param )
                {
                    $xmlfile = JPATH_COMPONENT_SITE.DS.'settings'.DS.'default'.DS.$param->template.'.xml';
                    
                    if ( JFile::exists($xmlfile) )
			        {
                    $form = JForm::getInstance($param->template, $xmlfile,array('control'=> ''));
		            $form->bind($param->params);
                    $newparams = array();
                    foreach($form->getFieldset($fieldset->name) as $field)
                    {
                    $newparams[$field->name] = $field->value;
                    }
                    $t_params = json_encode( $newparams );

                    // Create an object for the record we are going to update.
                    $object = new stdClass();
                    // Must be a valid primary key value.
                    $object->id = $param->id;
                    $object->params = $t_params;
                    // Update their details in the users table using id as the primary key.
                    $result = JFactory::getDbo()->updateObject($jsm_table, $object, 'id');
                    }   	
                }
            
            }
            
            } 
            
            }   
        
        
        }
     
        }
            
}    

?>