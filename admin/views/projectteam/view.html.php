<?php
/**
 * @copyright	Copyright (C) 2013 fussballineuropa.de. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Sportsmanagement Component
 *
 * @static
 * @package	Sportsmanagement
 * @since	0.1
 */
class sportsmanagementViewProjectteam extends JView
{
	function display($tpl = null)
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
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
        
        $project_id	= $this->item->project_id;;
        $mdlProject = JModel::getInstance("Project", "sportsmanagementModel");
	    $project = $mdlProject->getProject($project_id);
        $this->assignRef('project',$project);
        $team_id	= $this->item->team_id;;
        $mdlTeam = JModel::getInstance("Team", "sportsmanagementModel");
	    $project_team = $mdlTeam->getTeam($team_id);
        $trainingdata = $mdlTeam->getTrainigData($team_id);
        
        $daysOfWeek=array(	0 => JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT'),
			1 => JText::_('MONDAY'),
			2 => JText::_('TUESDAY'),
			3 => JText::_('WEDNESDAY'),
			4 => JText::_('THURSDAY'),
			5 => JText::_('FRIDAY'),
			6 => JText::_('SATURDAY'),
			7 => JText::_('SUNDAY'));
        $dwOptions=array();
			foreach($daysOfWeek AS $key => $value)
            {
                $dwOptions[]=JHtml::_('select.option',$key,$value);
            }
			foreach ($trainingdata AS $td)
			{
				$lists['dayOfWeek'][$td->id]=JHtml::_('select.genericlist',$dwOptions,'dayofweek['.$td->id.']','class="inputbox"','value','text',$td->dayofweek);
			}    
            
        $extended = sportsmanagementHelper::getExtended($item->extended, 'projectteam');
		$this->assignRef( 'extended', $extended );
        //$this->assign('cfg_which_media_tool', JComponentHelper::getParams('com_sportsmanagement')->get('cfg_which_media_tool',0) );
        
        $this->assignRef('project',$project);
        $this->assignRef('lists',	$lists);
        $this->assignRef('project_team',$project_team);
        $this->assignRef('trainingData',$trainingdata);
		
 
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
    
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{
		
        JRequest::setVar('hidemainmenu', true);
        JRequest::setVar('pid', $this->item->project_id);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;
		$canDo = sportsmanagementHelper::getActions($this->item->id);
		JToolBarHelper::title($isNew ? JText::_('COM_SPORTSMANAGEMENT_PROJECTTEAM_NEW') : JText::_('COM_SPORTSMANAGEMENT_PROJECTTEAM_EDIT'), 'helloworld');
		// Built the actions for new and existing records.
		if ($isNew) 
		{
			// For new records, check the create permission.
			if ($canDo->get('core.create')) 
			{
				JToolBarHelper::apply('projectteam.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('projectteam.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('projectteam.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel('projectteam.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($canDo->get('core.edit'))
			{
				// We can save the new record
				JToolBarHelper::apply('projectteam.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('projectteam.save', 'JTOOLBAR_SAVE');
 
				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) 
				{
					JToolBarHelper::custom('projectteam.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if ($canDo->get('core.create')) 
			{
				JToolBarHelper::custom('projectteam.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('projectteam.cancel', 'JTOOLBAR_CLOSE');
		}
    sportsmanagementHelper::ToolbarButtonOnlineHelp();    
        
        /*
        JToolBarHelper::title(JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_TITLE'));
		
		JToolBarHelper::save('projectteam.save');
		JToolBarHelper::apply('projectteam.apply');
		JToolBarHelper::cancel('projectteam.cancel',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_CLOSE'));
		JToolBarHelper::divider();
		//JLToolBarHelper::onlinehelp();
        */
	}
    
    /**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = $this->item->id == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_HELLOWORLD_HELLOWORLD_CREATING') : JText::_('COM_HELLOWORLD_HELLOWORLD_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_sportsmanagement/views/sportsmanagement/submitbutton.js");
		JText::script('COM_HELLOWORLD_HELLOWORLD_ERROR_UNACCEPTABLE');
	}
    
}
?>