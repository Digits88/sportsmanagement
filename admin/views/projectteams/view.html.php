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
defined('_JEXEC') or die('Restricted access');

/**
 * sportsmanagementViewprojectteams
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewprojectteams extends sportsmanagementView
{
	
	/**
	 * sportsmanagementViewprojectteams::init()
	 * 
	 * @return void
	 */
	public function init ()
	{
		// Reference global application object
		$app = JFactory::getApplication();
        // JInput object
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		$uri = JFactory::getURI();
		$model	= $this->getModel();

		$this->state = $this->get('State'); 
		$this->sortDirection = $this->state->get('list.direction');
		$this->sortColumn = $this->state->get('list.ordering');
        
        $this->division = $jinput->request->get('division', 0, 'INT');
		$this->project_id = $jinput->request->get('pid', 0, 'INT');
		if ( !$this->project_id )
		{
			$this->project_id = $app->getUserState( "$option.pid", '0' );
		}
       
		$mdlProject = JModelLegacy::getInstance('Project', 'sportsmanagementModel');
		$project = $mdlProject->getProject($this->project_id);
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' project_id<br><pre>'.print_r($this->project_id,true).'</pre>'   ),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' project<br><pre>'.print_r($project,true).'</pre>'   ),'');
        
		$this->project_art_id = $project->project_art_id;
		$this->season_id = $project->season_id;
		$this->sports_type_id = $project->sports_type_id;
		
		$app->setUserState( "$option.pid", $project->id );
		$app->setUserState( "$option.season_id", $project->season_id );
		$app->setUserState( "$option.project_art_id", $project->project_art_id );
		$app->setUserState( "$option.sports_type_id", $project->sports_type_id );
        
		$starttime = microtime(); 
		$items = $this->get('Items');
        
		if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
		{
			$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
		}
        
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
        
        $table = JTable::getInstance('projectteam', 'sportsmanagementTable');
		$this->table = $table;
        
		if ( $this->project_art_id == 3 )
		{
			$filter_order = $app->getUserStateFromRequest($option.'.'.$model->_identifier.'.tl_filter_order', 'filter_order', 't.lastname', 'cmd');
		} 
		else
		{
			$filter_order = $app->getUserStateFromRequest($option.'.'.$model->_identifier.'.tl_filter_order', 'filter_order', 't.name', 'cmd');
		}
		
		
		$mdlDivisions = JModelLegacy::getInstance("divisions", "sportsmanagementModel");
        $projectdivisions = array();
		$projectdivisions = $mdlDivisions->getDivisions($this->project_id);
        
        
		$divisionsList[] = JHtml::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_DIVISION'));
		
		if ($projectdivisions)
		{ 
			$projectdivisions = array_merge($divisionsList,$projectdivisions);
		}
		
		$lists['divisions'] = $projectdivisions;
        
        //build the html select list for project assigned teams
		$ress = array();
		$res1 = array();
		$notusedteams = array();

		if ($ress = $model->getProjectTeams($this->project_id, FALSE))
		{
			$teamslist = array();
			foreach($ress as $res)
			{
				if(empty($res1->info))
				{
					$project_teamslist[] = JHtmlSelect::option($res->season_team_id, $res->text);
				}
				else
				{
					$project_teamslist[] = JHtmlSelect::option($res->season_team_id, $res->text.' ('.$res->info.')');
				}
			}

			$lists['project_teams'] = JHtmlSelect::genericlist($project_teamslist, 'project_teamslist[]', 
																' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30, count($ress)).'"', 
																'value', 
																'text');
		}
		else
		{
			$lists['project_teams'] = '<select name="project_teamslist[]" id="project_teamslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		if ($ress1 = $model->getTeams())
		{
			if ($ress = $model->getProjectTeams($this->project_id,FALSE))
			{
				foreach ($ress1 as $res1)
				{
					$used = 0;
					foreach ($ress as $res)
					{
						if ($res1->value == $res->season_team_id)
                        {
                            $used = 1;
                        }
					}

					if ($used == 0 && !empty($res1->info)){
						$notusedteams[] = JHtmlSelect::option($res1->value, $res1->text.' ('.$res1->info.')');
					}
					elseif($used == 0 && empty($res1->info))
					{
						$notusedteams[] = JHtmlSelect::option($res1->value, $res1->text);
					}
				}
			}
			else
			{
				foreach ($ress1 as $res1)
				{
					if(empty($res1->info))
					{
						$notusedteams[] = JHtmlSelect::option($res1->value, $res1->text);
					}
					else
					{
						$notusedteams[] = JHtmlSelect::option($res1->value, $res1->text.' ('.$res1->info.')');
					}
				}
			}
		}
		else
		{
			JError::raiseWarning('ERROR_CODE','<br />'.JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_ADD_TEAM').'<br /><br />');
		}

		//build the html select list for teams
		if (count($notusedteams) > 0)
		{
			$lists['teams'] = JHtmlSelect::genericlist( $notusedteams, 
														'teamslist[]', 
														' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30, count($notusedteams)).'"', 
														'value', 
														'text');
		}
		else
		{
			$lists['teams'] = '<select name="teamslist[]" id="teamslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		unset($res);
		unset($res1);
		unset($notusedteams);
        
        //build the html options for nation
		$nation[] = JHtml::_('select.option', '0', JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_COUNTRY'));
		if ($res = JSMCountries::getCountryOptions())
		{
			$nation = array_merge($nation, $res);
			$this->assignRef('search_nation', $res);
		}
		
		$lists['nation'] = $nation;
		$lists['nationpt'] = JHtmlSelect::genericlist(	$nation,
																'filter_search_nation',
																'class="inputbox" style="width:140px; " onchange="this.form.submit();"',
																'value',
																'text',
																$this->state->get('filter.search_nation'));
        
        if ( JComponentHelper::getParams($this->option)->get('show_option_projectteams_quickadd',0) )
        {
        $lists['country_teams'] = $model->getCountryTeams();
        $lists['country_teams_picture'] = $model->getCountryTeamsPicture();
        }

//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' picture<br><pre>'.print_r($lists['country_teams_picture'],true).'</pre>'   ),'');
        
        //build the html select list for all teams
		$allTeams = array();
		$all_teams[] = JHTML::_( 'select.option', '0', JText::_( 'COM_SPORTSMANAGEMENT_GLOBAL_SELECT_TEAM' ) );
		if( $allTeams = $model->getAllTeams($this->project_id) ) 
    {
			$all_teams=array_merge($all_teams,$allTeams);
		}
		$lists['all_teams']=$all_teams;
		unset($all_teams);

        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' tpl<br><pre>'.print_r($tpl,true).'</pre>'   ),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' items<br><pre>'.print_r($items,true).'</pre>'   ),'');

        
        $myoptions = array();
		$myoptions[] = JHtml::_( 'select.option', '0', JText::_( 'JNO' ) );
		$myoptions[] = JHtml::_( 'select.option', '1', JText::_( 'JYES' ) );
		$lists['is_in_score'] = $myoptions;
        $lists['use_finally'] = $myoptions;

		//$this->user = JFactory::getUser();
		$this->config = JFactory::getConfig();
		$this->lists = $lists;
        $this->divisions = $projectdivisions;
		$this->projectteam = $items;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
        $this->project = $project;
        $this->project_art_id = $this->project_art_id;
        $this->lists = $lists;
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getLayout<br><pre>'.print_r($this->getLayout(),true).'</pre>'   ),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' projectteam<br><pre>'.print_r($this->projectteam,true).'</pre>'   ),'');
        
		if ( $this->getLayout() == 'editlist' || $this->getLayout() == 'editlist_3')
		{
			$this->setLayout('editlist');
		}
        
		if ( $this->getLayout() == 'changeteams' || $this->getLayout() == 'changeteams_3')
		{
			$this->setLayout('changeteams');
		}
		
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.7
	 */
	protected function addToolbar()
	{
	   // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        
        
	//// Get a refrence of the page instance in joomla
//        $document = JFactory::getDocument();
//        $document->addScript(JURI::base().'components/com_sportsmanagement/assets/js/sm_functions.js');
//        // Set toolbar items for the page
//        $stylelink = '<link rel="stylesheet" href="'.JURI::root().'administrator/components/com_sportsmanagement/assets/css/jlextusericons.css'.'" type="text/css" />' ."\n";
//        $document->addCustomTag($stylelink);

		$app->setUserState( "$option.pid", $this->project_id );
        $app->setUserState( "$option.season_id", $this->season_id );
        $app->setUserState( "$option.project_art_id", $this->project_art_id );
        $app->setUserState( "$option.sports_type_id", $this->sports_type_id );
        
        // Set toolbar items for the page
        if ( $this->project_art_id != 3 )
        {
            $this->title = JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_TITLE');
        }
        else
        {
            $this->title = JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTPERSONS_TITLE');
        }
        
        JToolBarHelper::custom('projectteams.setseasonid', 'purge.png', 'purge_f2.png', JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_SET_SEASON_ID'), true);
		JToolBarHelper::custom('projectteams.matchgroups', 'purge.png', 'purge_f2.png', JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_CHANGE_MATCH_GROUPS'), true);
        JToolBarHelper::deleteList('', 'projectteams.delete');

		JToolBarHelper::apply('projectteams.saveshort');
        sportsmanagementHelper::ToolbarButton('changeteams', 'move', JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_CHANGE_TEAMS'));
		sportsmanagementHelper::ToolbarButton('editlist', 'upload', JText::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_ASSIGN'));
        JToolBarHelper::custom('projectteam.copy', 'copy', 'copy', JText::_('JTOOLBAR_DUPLICATE'), true);
		JToolbarHelper::checkin('projectteams.checkin');
        
        JToolBarHelper::publish('projectteams.use_table_yes', 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_SET_USE_TABLE_YES', true);
        JToolBarHelper::unpublish('projectteams.use_table_no', 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_SET_USE_TABLE_NO', true);
        
        JToolBarHelper::publish('projectteams.use_table_points_yes', 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_SET_USE_TABLE_POINTS_YES', true);
        JToolBarHelper::unpublish('projectteams.use_table_points_no', 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_BUTTON_SET_USE_TABLE_POINTS_NO', true);
            
        parent::addToolbar();  

	}
}
?>
