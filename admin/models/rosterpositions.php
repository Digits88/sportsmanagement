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


jimport('joomla.application.component.modellist');


/**
 * sportsmanagementModelrosterpositions
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelrosterpositions extends JModelList
{
	var $_identifier = "rosterpositions";
	
	protected function getListQuery()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $search	= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'.search','search','','string');
        
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('obj.*');
		// From the hello table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_rosterposition as obj');
        // Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = obj.checked_out');
        if ($search)
		{
        $query->where(self::_buildContentWhere());
        }
		$query->order(self::_buildContentOrderBy());
 
		//$mainframe->enqueueMessage(JText::_('leagues query<br><pre>'.print_r($query,true).'</pre>'   ),'');
        return $query;
        
        
        
	}

	function _buildContentOrderBy()
	{
		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'l_filter_order','filter_order','obj.ordering','cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'l_filter_order_Dir','filter_order_Dir','','word');
		if ($filter_order == 'obj.ordering')
		{
			$orderby=' obj.ordering '.$filter_order_Dir;
		}
		else
		{
			$orderby=' '.$filter_order.' '.$filter_order_Dir.',obj.ordering ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();
		$filter_order		= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'l_filter_order','filter_order','obj.ordering','cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'l_filter_order_Dir','filter_order_Dir','','word');
		$search				= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'l_search','search','','string');
		$search=JString::strtolower($search);
		$where=array();
		if ($search)
		{
			$where[]='LOWER(obj.name) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		$where=(count($where) ? ' '.implode(' AND ',$where) : '');
		return $where;
	}
    
    function getRosterHome()
    {
        $bildpositionenhome = array();
$bildpositionenhome['HOME_POS'][0]['heim']['oben'] = 5;
$bildpositionenhome['HOME_POS'][0]['heim']['links'] = 233;
$bildpositionenhome['HOME_POS'][1]['heim']['oben'] = 113;
$bildpositionenhome['HOME_POS'][1]['heim']['links'] = 69;
$bildpositionenhome['HOME_POS'][2]['heim']['oben'] = 113;
$bildpositionenhome['HOME_POS'][2]['heim']['links'] = 179;
$bildpositionenhome['HOME_POS'][3]['heim']['oben'] = 113;
$bildpositionenhome['HOME_POS'][3]['heim']['links'] = 288;
$bildpositionenhome['HOME_POS'][4]['heim']['oben'] = 113;
$bildpositionenhome['HOME_POS'][4]['heim']['links'] = 397;
$bildpositionenhome['HOME_POS'][5]['heim']['oben'] = 236;
$bildpositionenhome['HOME_POS'][5]['heim']['links'] = 179;
$bildpositionenhome['HOME_POS'][6]['heim']['oben'] = 236;
$bildpositionenhome['HOME_POS'][6]['heim']['links'] = 288;
$bildpositionenhome['HOME_POS'][7]['heim']['oben'] = 318;
$bildpositionenhome['HOME_POS'][7]['heim']['links'] = 69;
$bildpositionenhome['HOME_POS'][8]['heim']['oben'] = 318;
$bildpositionenhome['HOME_POS'][8]['heim']['links'] = 233;
$bildpositionenhome['HOME_POS'][9]['heim']['oben'] = 318;
$bildpositionenhome['HOME_POS'][9]['heim']['links'] = 397;
$bildpositionenhome['HOME_POS'][10]['heim']['oben'] = 400;
$bildpositionenhome['HOME_POS'][10]['heim']['links'] = 233;
        return $bildpositionenhome;
    }
    
    function getRosterAway()
    {
        $bildpositionenaway = array();
$bildpositionenaway['AWAY_POS'][0]['heim']['oben'] = 970;
$bildpositionenaway['AWAY_POS'][0]['heim']['links'] = 233;
$bildpositionenaway['AWAY_POS'][1]['heim']['oben'] = 828;
$bildpositionenaway['AWAY_POS'][1]['heim']['links'] = 69;
$bildpositionenaway['AWAY_POS'][2]['heim']['oben'] = 828;
$bildpositionenaway['AWAY_POS'][2]['heim']['links'] = 179;
$bildpositionenaway['AWAY_POS'][3]['heim']['oben'] = 828;
$bildpositionenaway['AWAY_POS'][3]['heim']['links'] = 288;
$bildpositionenaway['AWAY_POS'][4]['heim']['oben'] = 828;
$bildpositionenaway['AWAY_POS'][4]['heim']['links'] = 397;
$bildpositionenaway['AWAY_POS'][5]['heim']['oben'] = 746;
$bildpositionenaway['AWAY_POS'][5]['heim']['links'] = 179;
$bildpositionenaway['AWAY_POS'][6]['heim']['oben'] = 746;
$bildpositionenaway['AWAY_POS'][6]['heim']['links'] = 288;
$bildpositionenaway['AWAY_POS'][7]['heim']['oben'] = 664;
$bildpositionenaway['AWAY_POS'][7]['heim']['links'] = 69;
$bildpositionenaway['AWAY_POS'][8]['heim']['oben'] = 664;
$bildpositionenaway['AWAY_POS'][8]['heim']['links'] = 397;
$bildpositionenaway['AWAY_POS'][9]['heim']['oben'] = 587;
$bildpositionenaway['AWAY_POS'][9]['heim']['links'] = 179;
$bildpositionenaway['AWAY_POS'][10]['heim']['oben'] = 587;
$bildpositionenaway['AWAY_POS'][10]['heim']['links'] = 288;
return $bildpositionenaway;
    }
    

	
}
?>