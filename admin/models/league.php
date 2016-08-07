<?php
/** Joomla Sports Management ein Programm zur Verwaltung f�r alle Sportarten
* @version 1.0.26
* @file		administrator/components/sportsmanagement/models/divisions.php
* @author diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license This file is part of Joomla Sports Management.
*
* Joomla Sports Management is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Joomla Sports Management is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Joomla Sports Management. If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von Joomla Sports Management.
*
* Joomla Sports Management ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* Joomla Sports Management wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HRLEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
//jimport('joomla.application.component.modeladmin');
 

/**
 * sportsmanagementModelleague
 * 
 * @package 
 * @author Dieter Pl�ger
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class sportsmanagementModelleague extends JSMModelAdmin
//class sportsmanagementModelleague extends JModelAdmin
{
	
	/**
	 * Override parent constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   3.2
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	
//    $this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' config<br><pre>'.print_r($config,true).'</pre>'),'');
//    $this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getName<br><pre>'.print_r($this->getName(),true).'</pre>'),'');
    
	}	   
   
    /**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
	   // Reference global application object
//        $app = JFactory::getApplication();
//        // JInput object
//        $jinput = $app->input;
//        $option = $jinput->getCmd('option');
//       $date = JFactory::getDate();
//	   $user = JFactory::getUser();
       $post = $this->jsmjinput->post->getArray();
       
       //$app->enqueueMessage(JText::_('sportsmanagementModelplayground save<br><pre>'.print_r($data,true).'</pre>'),'Notice');
       //$app->enqueueMessage(JText::_('sportsmanagementModelplayground post<br><pre>'.print_r($post,true).'</pre>'),'Notice');
       
       $data['sports_type_id'] = $data['request']['sports_type_id'];
       $data['agegroup_id'] = $data['request']['agegroup_id'];
       
       if (isset($post['extended']) && is_array($post['extended'])) 
		{
			// Convert the extended field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($post['extended']);
			$data['extended'] = (string)$parameter;
		}
        
        if (isset($post['extendeduser']) && is_array($post['extendeduser'])) 
		{
			// Convert the extended field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($post['extendeduser']);
			$data['extendeduser'] = (string)$parameter;
		}
        
        //$app->enqueueMessage(JText::_('sportsmanagementModelplayground save<br><pre>'.print_r($data,true).'</pre>'),'Notice');
        
        // Set the values
	   $data['modified'] = $this->jsmdate->toSql();
	   $data['modified_by'] = $this->jsmuser->get('id');
       
        // Proceed with the save
		//return parent::save($data); 
        
       // zuerst sichern, damit wir bei einer neuanlage die id haben
       if ( parent::save($data) )
       {
			$id =  (int) $this->getState($this->getName().'.id');
            $isNew = $this->getState($this->getName() . '.new');
            $data['id'] = $id;
            
            if ( $isNew )
            {
                //Here you can do other tasks with your newly saved record...
                $this->jsmapp->enqueueMessage(JText::plural(strtoupper($this->jsmoption) . '_N_ITEMS_CREATED', $id),'');
            }
           
		}
        
        return true;  
    }
    
    /**
	 * Method to update checked leagues
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
	function saveshort()
	{
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Get the input
        $pks = $jinput->getVar('cid', null, 'post', 'array');
        $post = JRequest::get('post');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        //$app->enqueueMessage('saveshort pks<br><pre>'.print_r($pks, true).'</pre><br>','Notice');
        //$app->enqueueMessage('saveshort post<br><pre>'.print_r($post, true).'</pre><br>','Notice');
        $my_text = 'pks <pre>'.print_r($pks,true).'</pre>';    
        $my_text .= 'post <pre>'.print_r($post,true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text);
        }
        
        $result=true;
		for ($x=0; $x < count($pks); $x++)
		{
		  
			$tblLeague = & $this->getTable();
            
			$tblLeague->id	= $pks[$x];
            $tblLeague->associations = $post['association' . $pks[$x]];
            $tblLeague->country = $post['country' . $pks[$x]];
            $tblLeague->agegroup_id = $post['agegroup'.$pks[$x]];

			if(!$tblLeague->store()) 
            {
				//$this->setError($this->_db->getErrorMsg());
                $app->enqueueMessage(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($this->_db->getErrorMsg(), true).'</pre><br>','Error');
				$result = false;
			}
		}
		return $result;
	}






        
}
