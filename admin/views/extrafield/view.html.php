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
* OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

/**
 * sportsmanagementViewextrafield
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewextrafield extends sportsmanagementView
{
	
	/**
	 * sportsmanagementViewextrafield::display()
	 * 
	 * @param mixed $tpl
	 * @return
	 */
	public function init ()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		$uri = JFactory::getURI();
        $starttime = microtime(); 
        
        // get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
		$script = $this->get('Script');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;
		
//		$extended = sportsmanagementHelper::getExtended($item->extended, 'jlextcountry');
//		$this->assignRef( 'extended', $extended );
		$this->cfg_which_media_tool	= JComponentHelper::getParams('com_sportsmanagement')->get('cfg_which_media_tool', 0);
 

	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
        $app	= JFactory::getApplication();
		$jinput	= $app->input;
		$jinput->set('hidemainmenu', true);
        
        $isNew = $this->item->id ? $this->title = JText::_('COM_SPORTSMANAGEMENT_EXTRAFIELD_EDIT') : $this->title = JText::_('COM_SPORTSMANAGEMENT_EXTRAFIELD_NEW');
        $this->icon = 'extrafield';
		
        parent::addToolbar();
	}
    

}
