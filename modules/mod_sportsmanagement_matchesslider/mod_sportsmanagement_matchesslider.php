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

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

if ( !defined('JSM_PATH') )
{
DEFINE( 'JSM_PATH','components/com_sportsmanagement' );
}

require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'helpers'.DS.'sportsmanagement.php');  
require_once(JPATH_SITE.DS.JSM_PATH.DS.'models'.DS.'project.php' );
require_once(JPATH_SITE.DS.JSM_PATH.DS.'models'.DS.'results.php');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'helpers'.DS.'route.php' );

if (!defined('_JLMATCHLISTSLIDERMODPATH')) { define('_JLMATCHLISTSLIDERMODPATH', dirname( __FILE__ ));}
if (!defined('_JLMATCHLISTSLIDERMODURL')) { define('_JLMATCHLISTSLIDERMODURL', JURI::base().'modules/mod_sportsmanagement_matchesslider/');}
require_once (_JLMATCHLISTSLIDERMODPATH.DS.'helper.php');
require_once (_JLMATCHLISTSLIDERMODPATH.DS.'connectors'.DS.'sportsmanagement.php');

// welche joomla version ?
if(version_compare(JVERSION,'3.0.0','ge')) 
{
JHTML::_('behavior.framework', true);
}
else
{
JHTML::_('behavior.mootools');
}

$doc = JFactory::getDocument();
$doc->addScript( _JLMATCHLISTSLIDERMODURL.'assets/js/jquery.simplyscroll.js' );
//$doc->addStyleSheet(_JLMATCHLISTMODURL.'tmpl/'.$template.'/mod_sportsmanagement_matchesslider.css');
$doc->addStyleSheet(_JLMATCHLISTSLIDERMODURL.'assets/css/mod_sportsmanagement_matchesslider.css');


JHTML::_('behavior.tooltip');
$doc->addScriptDeclaration('
(function($) {
	$(function() { //on DOM ready
		$("#scroller").simplyScroll({
			customClass: \'custom\',
			direction: \'backwards\',
			pauseOnHover: false,
			frameRate: 20,
			speed: 2
		});
	});
})(jQuery);
  ');

//$mod = new MatchesSliderSportsmanagementConnector($params, $module->id, $match_id);
//$matches = $mod->getMatches();

$config = array();
$slidermatches = array();
$projectid = JRequest::getInt('p',0);
if ( !$projectid )
{
    foreach( $params->get('project') as $key => $value )
    {
        $projectid = $value;
        sportsmanagementModelProject::$projectid = $projectid;
        $matches = sportsmanagementModelResults::getResultsRows(0,0,$config,$params);
        //$slidermatches[] = $matches;
        $slidermatches = array_merge($matches);
    }
    
}    
else
{
sportsmanagementModelProject::$projectid = $projectid;
$matches = sportsmanagementModelResults::getResultsRows(0,0,$config,$params);
$slidermatches = array_merge($matches);
}

foreach( $slidermatches as $match )
{
$routeparameter = array();
$routeparameter['cfg_which_database'] = $params->get('cfg_which_database');
$routeparameter['s'] = $params->get('s');
$routeparameter['p'] = $match->project_slug;

switch ( $params->get('p_link_func') )
{
    case 'results':
    $routeparameter['r'] = $match->round_slug;
$routeparameter['division'] = 0;
$routeparameter['mode'] = 0;
$routeparameter['order'] = '';
$routeparameter['layout'] = '';
    $link = sportsmanagementHelperRoute::getSportsmanagementRoute('results',$routeparameter);
    break;
    case 'ranking':
    $link = sportsmanagementHelperRoute::getRankingRoute( $match->project_slug, $match->round_slug,null,null,0,0 );
    break;
    case 'resultsrank':
    $link = sportsmanagementHelperRoute::getResultsRankingRoute( $match->project_slug, $match->round_slug, 0  );
    break;
}

}



require(JModuleHelper::getLayoutPath('mod_sportsmanagement_matchesslider'));


?>
