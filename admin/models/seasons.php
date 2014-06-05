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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


/**
 * sportsmanagementModelSeasons
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelSeasons extends JModelList
{
	var $_identifier = "seasons";
	var $_order = "s.name";
    
    /**
     * sportsmanagementModelSeasons::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $mainframe = JFactory::getApplication();
                
                $layout = JRequest::getVar('layout');
                
                //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($layout,true).'</pre>'),'Notice');
                
                switch ($layout)
        {
            case 'assignteams':
            $this->_order = 't.name';
            break;
            
            case 'assignpersons':
            $this->_order = 'p.lastname';
            break;
            
            default:
		    $this->_order = 's.name';
            break;
        }
                $config['filter_fields'] = array(
                        's.name',
                        's.id',
                        's.ordering',
                        's.checked_out',
                        's.checked_out_time'
                        );
                parent::__construct($config);
        }
        
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $layout = JRequest::getVar('layout');
        // Initialise variables.
		$app = JFactory::getApplication('administrator');
        $order = '';
        
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($this->context,true).'</pre>'   ),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_nation', 'filter_search_nation', '');
		$this->setState('filter.search_nation', $temp_user_request);
        
        $value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		//switch ($layout)
//        {
//            case 'assignteams':
//            $this->order = 't.name';
//            break;
//            
//            case 'assignpersons':
//            $this->order = 'p.lastname';
//            break;
//            
//            default:
//		    $this->order = 's.name';
//            break;
//        }
        
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' _order<br><pre>'.print_r($this->_order,true).'</pre>'),'Notice');
        
        //$this->setState('list.ordering', $this->_order);
        
        // List state information.
		parent::populateState($this->_order, 'asc');
        
	}
    
	/**
	 * sportsmanagementModelSeasons::getListQuery()
	 * 
	 * @return
	 */
	protected function getListQuery()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $search	= $this->getState('filter.search');
        $search_nation	= $this->getState('filter.search_nation');
        $layout = JRequest::getVar('layout');
        $season_id = JRequest::getVar('id');
        
        $this->setState('list.ordering', $this->_order);
        
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getState<br><pre>'.print_r($this->getState(),true).'</pre>'),'Notice');
        
        //$order = '';
        
        
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
        $Subquery = $db->getQuery(true);
        
        switch ($layout)
        {
            case 'assignteams':
            // Select some fields
		    $query->select('t.*');
		    // From the seasons table
		    $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_team as t');
            $query->join('LEFT', '#__'.COM_SPORTSMANAGEMENT_TABLE.'_club AS c ON c.id = t.club_id');
            $Subquery->select('stp.team_id');
            $Subquery->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_team_id AS stp  ');
            $Subquery->where('stp.season_id = '.$season_id);
            $query->where('t.id NOT IN ('.$Subquery.')');
            if ($search_nation)
		      {
                $query->where('c.country LIKE '.$db->Quote(''.$search_nation.''));
                }
            //$order = 't.name';
            break;
            
            case 'assignpersons':
            // Select some fields
		    $query->select('p.*');
		    // From the seasons table
		    $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_person as p');
            $Subquery->select('stp.person_id');
            $Subquery->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season_person_id AS stp  ');
            $Subquery->where('stp.season_id = '.$season_id);
            $query->where('p.id NOT IN ('.$Subquery.')');
                        if ($search_nation)
		      {
                $query->where('p.country LIKE '.$db->Quote(''.$search_nation.''));
                }
            //$order = 'p.lastname';
            break;
            
            default:
            // Select some fields
		    $query->select(implode(",",$this->filter_fields));
		    // From the seasons table
		    $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season as s');
            if ($search)
		    {
            $query->where(' LOWER(s.name) LIKE '.$db->Quote('%'.$search.'%'));
            }
		    //$order = 's.name';
            break;
        }
		
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' _order<br><pre>'.print_r($this->_order,true).'</pre>'),'Notice');
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' list.ordering<br><pre>'.print_r($this->getState('list.ordering', $this->_order),true).'</pre>'),'Notice');
        
        $query->order($db->escape($this->getState('list.ordering', $this->_order)).' '.
                $db->escape($this->getState('list.direction', 'ASC')));
 
        //$mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
 
if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        }

        return $query;
	}
	
  
  



	
	/**
     * Method to return a seasons array (id,name)
     *
     * @access	public
     * @return	array seasons
     * @since	1.5.0a
     */
    function getSeasons()
    {
        // Get a db connection.
        $db = JFactory::getDBO();
        // Create a new query object.
        $query = $db->getQuery(true);
        $query->select(array('id', 'name'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_season')
        ->order('name DESC');

        $db->setQuery($query);
        if (!$result = $db->loadObjectList())
        {
            $this->setError($db->getErrorMsg());
            return array();
        }
        foreach ($result as $season)
        {
            $season->name = JText::_($season->name);
        }
        return $result;
    }
	
	

	
}
?>