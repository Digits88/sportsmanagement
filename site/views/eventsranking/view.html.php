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

jimport('joomla.application.component.view');

/**
 * sportsmanagementViewEventsRanking
 * 
 * @package 
 * @author diddi
 * @copyright 2015
 * @version $Id$
 * @access public
 */
class sportsmanagementViewEventsRanking extends JViewLegacy
{
	/**
	 * sportsmanagementViewEventsRanking::display()
	 * 
	 * @param mixed $tpl
	 * @return void
	 */
	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		// read the config-data from template file
		$model = $this->getModel();
		//$config = sportsmanagementModelProject::getTemplateConfig($this->getName());
        sportsmanagementModelProject::setProjectID(JRequest::getInt('p',0),$model::$cfg_which_database);
		$config = sportsmanagementModelProject::getTemplateConfig($this->getName(),$model::$cfg_which_database,__METHOD__);

		$this->assign('project', sportsmanagementModelProject::getProject($model::$cfg_which_database,__METHOD__));
		$this->assign('division', sportsmanagementModelProject::getDivision(0,$model::$cfg_which_database));
		$this->assignRef('matchid', $model::$matchid);
		$this->assign('overallconfig', sportsmanagementModelProject::getOverallConfig($model::$cfg_which_database));
		$this->assignRef('config', $config);
		$this->assign('teamid', $model->getTeamId());
		$this->assign('teams', sportsmanagementModelProject::getTeamsIndexedById(0,'name',$model::$cfg_which_database));
		$this->assign('favteams', sportsmanagementModelProject::getFavTeams($model::$cfg_which_database));
		$this->assign('eventtypes', sportsmanagementModelProject::getEventTypes(0,$model::$cfg_which_database));
		$this->assign('limit', $model->getLimit());
		$this->assign('limitstart', $model->getLimitStart());
		$this->assign('pagination', $this->get('Pagination'));
		$this->assign('eventranking', $model->getEventRankings($this->limit));
		$this->assign('multiple_events', count($this->eventtypes) > 1 );

		$prefix = JText::_('COM_SPORTSMANAGEMENT_EVENTSRANKING_PAGE_TITLE');
		if ( $this->multiple_events )
		{
			$prefix .= " - " . JText::_( 'COM_SPORTSMANAGEMENT_EVENTSRANKING_TITLE' );
		}
		else
		{
			// Next query will result in an array with exactly 1 statistic id
			$evid = array_keys($this->eventtypes);

			// Selected one valid eventtype, so show its name
			$prefix .= " - " . JText::_($this->eventtypes[$evid[0]]->name);
		}

		// Set page title
		$titleInfo = sportsmanagementHelper::createTitleInfo($prefix);
		if (!empty($this->teamid) && array_key_exists($this->teamid, $this->teams))
		{
			$titleInfo->team1Name = $this->teams[$this->teamid]->name;
		}
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		if (!empty( $this->division ) && $this->division->id != 0)
		{
			$titleInfo->divisionName = $this->division->name;
		}
		$this->assign('pagetitle', sportsmanagementHelper::formatTitle($titleInfo, $this->config["page_title_format"]));
		$document->setTitle($this->pagetitle);
        
        $this->headertitle = $this->pagetitle;

		parent::display($tpl);
	}

}
?>
