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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

//require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );
//require_once('results.php');

/**
 * sportsmanagementModelTeamPlan
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelTeamPlan extends JModel
{
	var $projectid=0;
	var $teamid=0;
	var $pro_teamid=0;
	var $team=null;
	var $club=null;
	var $divisionid=0;
	var $joomleague=null;
	var $mode=0;

	function __construct()
	{
		

		$this->projectid = JRequest::getInt('p',0);
		$this->teamid = JRequest::getInt('tid',0);
		$this->divisionid = JRequest::getInt('division',0);
		$this->mode = JRequest::getInt("mode",0);
        parent::__construct();
	}

	function getDivisionID()
	{
		return $this->divisionid;
	}

	function getMode()
	{
		return $this->mode;
	}

	function getDivision()
	{
		$option = JRequest::getCmd('option');
	   $mainframe = JFactory::getApplication();
       // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $division = null;
		if ( $this->divisionid > 0 )
		{
			
        // Select some fields
        $query->select('d.*,CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(\':\',id,alias) ELSE id END AS slug');
        // From 
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_division AS d');
        // Where
        $query->where('d.id = '.$db->Quote($this->divisionid));
			
            $db->setQuery($query,0,1);
			$division = $db->loadObject();
            
            if (!$division )
		{
			$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
		}
        
		}
		return $division;
	}

	function getProjectTeamId()
	{
		$option = JRequest::getCmd('option');
	   $mainframe = JFactory::getApplication();
       // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('pt.id');
        // From 
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_project_team as pt');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id as st ON st.id = pt.team_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team as t ON t.id = st.team_id ');
        // Where
        $query->where('pt.project_id = '.$this->projectid);
        $query->where('t.id='.$this->teamid);
                 
		$db->setQuery($query,0,1);
		if (! $result = $db->loadResult())
		{
			$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
            $this->pro_teamid = 0;
            return 0;
		}
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
       {
        $mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' team_id'.'<pre>'.print_r($result,true).'</pre>' ),'');
        }
        
		$this->pro_teamid = $result;
		return $result;
	}

	function getMatchesPerRound($config,$rounds)
	{
		$rm=array();

		$ordering='DESC';
		if ($config['plan_order'])
		{
			$ordering=$config['plan_order'];
		}
		foreach ($rounds as $round)
		{
			$matches = self::_getResultsRows($round->roundcode,$this->pro_teamid,$ordering,0,1,$config['show_referee']);
			$rm[$round->roundcode] = $matches;
		}
		return $rm;
	}

	function getMatches($config)
	{
		$ordering = 'DESC';
		if ($config['plan_order'])
		{
			$ordering = $config['plan_order'];
		}
		return self::_getResultsPlan($this->pro_teamid,$ordering,0,1,$config['show_referee']);
	}

	function getMatchesRefering($config)
	{
		$ordering = 'DESC';
		if ($config['plan_order'])
		{
			$ordering = $config['plan_order'];
		}
		return self::_getResultsPlan(0,$ordering,$this->pro_teamid,1,$config['show_referee']);
	}

	function _getResultsPlan($team=0,$ordering='ASC',$referee=0,$getplayground=0,$getreferee=0)
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
       // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
		$mdlProject = JModel::getInstance("Project", "sportsmanagementModel");
        $matches = array();
		$joomleague = $mdlProject->getProject();

		if ($this->divisionid > 0)
		{
			$query='	SELECT id
					  	FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_division
					  	WHERE parent_id='.(int)$this->divisionid;
			$this->_db->setquery($query);
			$div_for_teams=$this->_db->loadResultArray();
			$div_for_teams[]=$this->getDivision()->id;
		}

		// Select some fields
        $query->select('m.*,DATE_FORMAT(m.time_present,"%H:%i") time_present,r.roundcode,r.id roundid,r.project_id,r.name');
        // From 
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS m');
        // Join 
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_round AS r ON m.round_id = r.id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_project AS p ON p.id = r.project_id ');
        // Where
        $query->where('m.published=1');

		

//win matches
		if (($this->mode)== 1)
		{
		  $query->where('( (m.projectteam1_id= ' .$team. ' AND m.team1_result > m.team2_result)'.' OR (m.projectteam2_id= ' .$team. ' AND m.team1_result < m.team2_result) )');
		}
//draw matches
		if (($this->mode)== 2)
		{
		  $query->where('m.team1_result = m.team2_result');
		}
//lost matches
		if (($this->mode)== 3)
		{
 		  $query->where('( (m.projectteam1_id= ' .$team. ' AND m.team1_result < m.team2_result)'.' OR (m.projectteam2_id= ' .$team. ' AND m.team1_result > m.team2_result) )');
			
		}
	
		if ($this->divisionid > 0)
		{
		  $query->where('(pt1.division_id IN ('.(implode(',',$div_for_teams)).') OR pt2.division_id IN ('.(implode(',',$div_for_teams)).'))');
		}

		if ($referee != 0)
		{
			$query->select('p.name AS project_name');
            $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_match_referee AS mref ON mref.match_id = m.id ');
            $query->where('mref.project_referee_id = '.$referee);
            $query->where('p.season_id = '.$joomleague->season_id);
		}
		else
		{
            $query->where('r.project_id = '.$this->projectid);
		}

		if ($this->teamid != 0)
		{
            $query->where("(m.projectteam1_id=".$team." OR m.projectteam2_id=".$team.")");
		}
        
        // Group
        $query->group('m.id');
        // Order
        $query->order("r.roundcode ".$ordering.",m.match_date,m.match_number");

		if ($getplayground)
		{
            $query->select('playground.name AS playground_name,playground.short_name AS playground_short_name');
            $query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_playground AS playground ON playground.id = m.playground_id ');
		}

		
		$db->setQuery($query);

		$matches = $db->loadObjectList();

		if ($getreferee)
		{
			$this->_getRefereesByMatch($matches,$joomleague);
		}

if (!$matches )
		{
			//$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($query,true).'</pre>' ),'Error');
			$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
		}
		
		return $matches;
	}

	function _getResultsRows($roundcode=0,$teamId=0,$ordering='ASC',$unpublished=0,$getplayground=0,$getreferee=0)
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
       // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
		$mdlProject = JModel::getInstance("Project", "sportsmanagementModel");
        $matches = array();

		$joomleague = $mdlProject->getProject();
        
        // Select some fields
        $query->select('matches.*');
        // From 
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match AS matches');
        // Join 
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_round AS r ON matches.round_id = r.id ');
        // Where
        $query->where('r.project_id = '.(int)$this->projectid);
        $query->where('r.roundcode = '.$roundcode);

		if ($teamId)
		{
		  $query->where("(matches.projectteam1_id=".$teamId." OR matches.projectteam2_id=".$teamId.")");
		}
		// Group
        $query->group('matches.id');
        // Order
        $query->order('matches.match_date '.$ordering.',matches.match_number');

		if ($this->divisionid > 0)
		{
		  $query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_division AS d1 ON pt1.division_id = d1.id ');
          $query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_division AS d2 ON pt2.division_id = d2.id ');
          $query->where("(d1.id=".$this->divisionid." OR d1.parent_id=".$this->divisionid." OR d2.id=".$this->divisionid." OR d2.parent_id=".$this->divisionid.")");
		}

		if ($unpublished != 1)
		{
		  $query->where('matches.published=1');
		}

		if ($getplayground)
		{
			$query->select('playground.name AS playground_name,playground.short_name AS playground_short_name');
			$query->join('LEFT',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_playground AS playground ON playground.id = matches.playground_id');
		}

		$db->setQuery($query);
		if (!$matches = $db->loadObjectList())
		{
			echo $db->getErrorMsg();
		}

		if ($getreferee)
		{
			$this->_getRefereesByMatch($matches,$joomleague);
		}

if (!$matches )
		{
			//$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($query_SELECT.$query_FROM.$query_WHERE.$query_END,true).'</pre>' ),'Error');
			$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.'<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
		}

		return $matches;
	}

	function _getRefereesByMatch($matches,$joomleague)
	{
		for ($index=0; $index < count($matches); $index++) {
			$referees=array();
			if ($joomleague->teams_as_referees)
			{
				$query="SELECT ref.name AS referee_name
							  FROM #__".COM_SPORTSMANAGEMENT_TABLE."_team ref
							  LEFT JOIN #__".COM_SPORTSMANAGEMENT_TABLE."_match_referee link ON link.project_referee_id=ref.id
								  WHERE link.match_id=".$matches[$index]->id."
								  ORDER BY link.ordering";
			}
			else
			{
				$query="SELECT	ref.firstname AS referee_firstname,
											ref.lastname AS referee_lastname,
											ref.id as referee_id,
											ppos.position_id,
											pos.name AS referee_position_name
								FROM #__".COM_SPORTSMANAGEMENT_TABLE."_person ref
								LEFT JOIN #__".COM_SPORTSMANAGEMENT_TABLE."_project_referee AS pref ON pref.person_id=ref.id
								LEFT JOIN #__".COM_SPORTSMANAGEMENT_TABLE."_match_referee link ON link.project_referee_id=pref.id
								INNER JOIN #__".COM_SPORTSMANAGEMENT_TABLE."_project_position AS ppos ON ppos.id=link.project_position_id
								INNER JOIN #__".COM_SPORTSMANAGEMENT_TABLE."_position AS pos ON pos.id=ppos.position_id	
								WHERE link.match_id=".$matches[$index]->id."
								  AND ref.published = 1
								  ORDER BY link.ordering";
			}

			$this->_db->setQuery($query);
			if (! $referees=$this->_db->loadObjectList())
			{
				echo $this->_db->getErrorMsg();
			}
			$matches[$index]->referees=$referees;
		}
		return $matches;
	}

	function getEventTypes($match_id)
	{
		$option = JRequest::getCmd('option');
	   $mainframe = JFactory::getApplication();
       // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('et.id as etid,me.event_type_id as id,et.*');
        // From 
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_eventtype as et');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_match_event as me ON et.id = me.event_type_id ');
        $query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_match as m ON m.id = me.match_id ');
        // Where
        $query->where('me.match_id = '.$match_id);
        // Order
        $query->order('et.ordering');

		$db->setQuery($query);
		return $db->loadObjectList('etid');
	}

}
?>