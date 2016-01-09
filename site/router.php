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
 * 
 * hilfeseite: http://docs.joomla.org/Supporting_SEF_URLs_in_your_component
 * 
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('JSM_PATH')) {
    DEFINE('JSM_PATH', 'components/com_sportsmanagement');
}

$paramscomponent = JComponentHelper::getParams('com_sportsmanagement');
if (!defined('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO')) {
    DEFINE('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO', $paramscomponent->get('show_debug_info'));
}

if (!class_exists('sportsmanagementHelper')) {
    require_once (JPATH_ADMINISTRATOR . DS . JSM_PATH . DS . 'helpers' . DS .
        'sportsmanagement.php');
}

/**
 * sportsmanagementBuildRoute()
 * 
 * @param mixed $query
 * @return
 */
function sportsmanagementBuildRoute(&$query)
{
    $app = JFactory::getApplication();
    //$paramscomponent = JComponentHelper::getParams( 'com_sportsmanagement' );
    //    $show_debug_info = $paramscomponent->get( 'show_debug_info' );
    //    DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',$show_debug_info );
    //if (! defined('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO'))
    //{
    //DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',$paramscomponent->get( 'show_debug_info' ) );
    //}

    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query,true).'</pre>'   ),'');


    if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO) {
        $my_text = 'query -><pre>' . print_r($query, true) . '</pre>';
        //$my_text .= 'dump -><pre>'.print_r($query->dump(),true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__method__, __function__,
            'sportsmanagementRoute', __line__, $my_text);
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');
    }

    $segments = array();

    $view = (isset($query['view']) ? $query['view'] : null);
    if ($view) {
        $segments[] = $view;
        unset($query['view']);
    } else {
        return $segments;
    }


    // now, the specifics
    switch ($view) {
        case 'predictionrules':
        case 'predictionranking':
        case 'predictionentry':
        case 'predictionresults':
        case 'predictionusers':
        case 'rankingalltime';
        if (isset($query['cfg_which_database'])) {
                $segments[] = $query['cfg_which_database'];
                unset($query['cfg_which_database']);
            }
            break;
        default:
            /**
             * die ersten parameter im query
             */
            if (isset($query['cfg_which_database'])) {
                $segments[] = $query['cfg_which_database'];
                unset($query['cfg_which_database']);
            }
            if (isset($query['s'])) {
                $segments[] = $query['s'];
                unset($query['s']);
            }

            if (isset($query['p'])) {
                $segments[] = $query['p'];
                unset($query['p']);
            }
            break;
    }


    // now, the specifics
    switch ($view) {
        case 'rankingalltime':
            if (isset($query['l'])) {
                $segments[] = $query['l'];
                unset($query['l']);
            }
            if (isset($query['points'])) {
                $segments[] = $query['points'];
                unset($query['points']);
            }
            if (isset($query['type'])) {
                $segments[] = $query['type'];
                unset($query['type']);
            }
            if (isset($query['order'])) {
                $segments[] = $query['order'];
                unset($query['order']);
            }
            if (isset($query['dir'])) {
                $segments[] = $query['dir'];
                unset($query['dir']);
            }
        
        break;
        case 'event':
            if (isset($query['gcid'])) {
                $segments[] = $query['gcid'];
                unset($query['gcid']);
            }
            if (isset($query['eventID'])) {
                $segments[] = $query['eventID'];
                unset($query['eventID']);
            }
            break;
        case 'predictionrules':
            if (isset($query['prediction_id'])) {
                $segments[] = $query['prediction_id'];
                unset($query['prediction_id']);
            }

            break;
        case 'predictionranking':
            if (isset($query['prediction_id'])) {
                $segments[] = $query['prediction_id'];
                unset($query['prediction_id']);
            }
//            if (isset($query['p'])) {
//                $segments[] = $query['p'];
//                unset($query['p']);
//            }
            if (isset($query['pggroup'])) {
                $segments[] = $query['pggroup'];
                unset($query['pggroup']);
            }
            if (isset($query['pj'])) {
                $segments[] = $query['pj'];
                unset($query['pj']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['pggrouprank'])) {
                $segments[] = $query['pggrouprank'];
                unset($query['pggrouprank']);
            }
            
            if (isset($query['type'])) {
                $segments[] = $query['type'];
                unset($query['type']);
            }
            if (isset($query['from'])) {
                $segments[] = $query['from'];
                unset($query['from']);
            }
            if (isset($query['to'])) {
                $segments[] = $query['to'];
                unset($query['to']);
            }

            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');

            break;
        case 'predictionentry':
            if (isset($query['prediction_id'])) {
                $segments[] = $query['prediction_id'];
                unset($query['prediction_id']);
            }
            if (isset($query['pggroup'])) {
                $segments[] = $query['pggroup'];
                unset($query['pggroup']);
            }
//            if (isset($query['s'])) {
//                $segments[] = $query['s'];
//                unset($query['s']);
//            }
            if (isset($query['pj'])) {
                $segments[] = $query['pj'];
                unset($query['pj']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['uid'])) {
                $segments[] = $query['uid'];
                unset($query['uid']);
            }
            break;
        case 'predictionresults':
            if (isset($query['prediction_id'])) {
                $segments[] = $query['prediction_id'];
                unset($query['prediction_id']);
            }
//            if (isset($query['p'])) {
//                $segments[] = $query['p'];
//                unset($query['p']);
//            }
            if (isset($query['pggroup'])) {
                $segments[] = $query['pggroup'];
                unset($query['pggroup']);
            }
            if (isset($query['pj'])) {
                $segments[] = $query['pj'];
                unset($query['pj']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['uid'])) {
                $segments[] = $query['uid'];
                unset($query['uid']);
            }
            break;
        case 'predictionusers':
            if (isset($query['prediction_id'])) {
                $segments[] = $query['prediction_id'];
                unset($query['prediction_id']);
            }
            if (isset($query['pggroup'])) {
                $segments[] = $query['pggroup'];
                unset($query['pggroup']);
            }
            if (isset($query['pj'])) {
                $segments[] = $query['pj'];
                unset($query['pj']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['uid'])) {
                $segments[] = $query['uid'];
                unset($query['uid']);
            }

            break;
        case 'allprojects':
            if (isset($query['filter_search_nation'])) {
                $segments[] = $query['filter_search_nation'];
                unset($query['filter_search_nation']);
            }

            if (isset($query['filter_search_leagues'])) {
                $segments[] = $query['filter_search_leagues'];
                unset($query['filter_search_leagues']);
            }
            break;

        case 'clubinfo':
            if (isset($query['cid'])) {
                $segments[] = $query['cid'];
                unset($query['cid']);
            }
            break;
        case 'curve':
            if (isset($query['tid1'])) {
                $segments[] = $query['tid1'];
                unset($query['tid1']);
            }
            if (isset($query['tid2'])) {
                $segments[] = $query['tid2'];
                unset($query['tid2']);
            }
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            break;
        case 'eventsranking':
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            if (isset($query['tid'])) {
                $segments[] = $query['tid'];
                unset($query['tid']);
            }
            if (isset($query['evid'])) {
                $segments[] = $query['evid'];
                unset($query['evid']);
            }
            if (isset($query['mid'])) {
                $segments[] = $query['mid'];
                unset($query['mid']);
            }
            break;
        case 'editmatch':
        case 'editevents':
            if (isset($query['mid'])) {
                $segments[] = $query['mid'];
                unset($query['mid']);
            }
            break;
        case "matrix":
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            break;
        case 'matchreport':
        case 'nextmatch':
            if (isset($query['mid'])) {
                $segments[] = $query['mid'];
                unset($query['mid']);
            }
            if (isset($query['pics'])) {
                $segments[] = $query['pics'];
                unset($query['pics']);
            }
            if (isset($query['ptid'])) {
                $segments[] = $query['ptid'];
                unset($query['ptid']);
            }
            break;
        case "playground":
            if (isset($query['pgid'])) {
                $segments[] = $query['pgid'];
                unset($query['pgid']);
            }
            break;
        case 'ranking':
        case 'rankingmatrix':
            if (isset($query['type'])) {
                $segments[] = $query['type'];
                unset($query['type']);
            }
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['from'])) {
                $segments[] = $query['from'];
                unset($query['from']);
            }
            if (isset($query['to'])) {
                $segments[] = $query['to'];
                unset($query['to']);
            }
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            
            if (isset($query['order'])) {
                $segments[] = $query['order'];
                unset($query['order']);
            }
            
            if (isset($query['dir'])) {
                $segments[] = $query['dir'];
                unset($query['dir']);
            }
            
            break;
        case 'roster':
        case 'teaminfo':
        case 'teamstats':
            if (isset($query['tid'])) {
                $segments[] = $query['tid'];
                unset($query['tid']);
            }
            // diddipoeler
            if (isset($query['ptid'])) {
                $segments[] = $query['ptid'];
                unset($query['ptid']);
            }
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            break;

        case 'clubplan':
            if (isset($query['cid'])) {
                $segments[] = $query['cid'];
                unset($query['cid']);
            }
            if (isset($query['task'])) {
                $segments[] = $query['task'];
                unset($query['task']);
            }

            break;

        case 'teamplan':
            if (isset($query['tid'])) {
                $segments[] = $query['tid'];
                unset($query['tid']);
            }
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            if (isset($query['mode'])) {
                $segments[] = $query['mode'];
                unset($query['mode']);
            }
            // diddipoeler
            if (isset($query['ptid'])) {
                $segments[] = $query['ptid'];
                unset($query['ptid']);
            }

            break;
        case 'results':
        case 'resultsmatrix':
        case 'resultsranking':
            if (isset($query['r'])) {
                $segments[] = $query['r'];
                unset($query['r']);
            }
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            if (isset($query['mode'])) {
                $segments[] = $query['mode'];
                unset($query['mode']);
            }
            if (isset($query['order'])) {
                $segments[] = $query['order'];
                unset($query['order']);
            }
            if (isset($query['layout'])) {
                $segments[] = $query['layout'];
                unset($query['layout']);
            }

            break;
        case 'player':
        case 'staff':
            if (isset($query['tid'])) {
                $segments[] = $query['tid'];
                unset($query['tid']);
            }
            if (isset($query['pid'])) {
                $segments[] = $query['pid'];
                unset($query['pid']);
            }
            break;
        case 'clubs':
        case 'stats':
        case 'teams':
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            break;
        case 'statsranking':
            if (isset($query['division'])) {
                $segments[] = $query['division'];
                unset($query['division']);
            }
            if (isset($query['tid'])) {
                $segments[] = $query['tid'];
                unset($query['tid']);
            }
            break;
        case 'referee':
            if (isset($query['pid'])) {
                $segments[] = $query['pid'];
                unset($query['pid']);
            }
            break;
        case 'tree':
            if (isset($query['did'])) {
                $segments[] = $query['did'];
                unset($query['did']);
            }
            break;

        default:
            break;
    }

    if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO) {
        $my_text = 'segments -><pre>' . print_r($segments, true) . '</pre>';
        //$my_text .= 'dump -><pre>'.print_r($query->dump(),true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__method__, __function__,
            'sportsmanagementRoute', __line__, $my_text);
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');
    }


    return $segments;
}

/**
 * sportsmanagementParseRoute()
 * 
 * @param mixed $segments
 * @return
 */
function sportsmanagementParseRoute($segments)
{
    $app = JFactory::getApplication();
    //$paramscomponent = JComponentHelper::getParams( 'com_sportsmanagement' );
    //    $show_debug_info = $paramscomponent->get( 'show_debug_info' );
    //    DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',$show_debug_info );
    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');

    //    if (! defined('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO'))
    //{
    //DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',$paramscomponent->get( 'show_debug_info' ) );
    //}

    if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO) {
        $my_text = 'segments -><pre>' . print_r($segments, true) . '</pre>';
        //$my_text .= 'dump -><pre>'.print_r($query->dump(),true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__method__, __function__,
            'sportsmanagementRoute', __line__, $my_text);
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');
    }

    $vars = array();

    $vars['view'] = $segments[0];

    // now, the specifics
    switch ($vars['view']) 
    {
        case 'predictionrules':
        case 'predictionranking':
        case 'predictionentry':
        case 'predictionresults':
        case 'predictionusers':
        case 'rankingalltime';
        if (isset($segments[1])) 
        {
                $vars['cfg_which_database'] = $segments[1];
            }        
            break;
        default:
            /**
             * die ersten parameter im query
             */
            if (isset($segments[1])) {
                $vars['cfg_which_database'] = $segments[1];
            }
            if (isset($segments[2])) {
                $vars['s'] = $segments[2];
            }

            if (isset($segments[3])) {
                $vars['p'] = $segments[3];
            }
            break;
    }


    switch ($vars['view']) // the view...
        {
        case 'rankingalltime';
        if (isset($segments[2])) {
                $vars['l'] = $segments[2];
            }    
        if (isset($segments[3])) {
                $vars['points'] = $segments[3];
            }
            if (isset($segments[4])) {
                $vars['type'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['order'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['dir'] = $segments[6];
            }    
        break;    
        case 'event':
            $vars['gcid'] = $segments[1];
            if (count($segments) < 3) {
                $vars['eventID'] = JRequest::getVar('eventId');
            } else {
                $vars['eventID'] = $segments[2];
            }
            $vars['Itemid'] = jsmGCalendarUtil::getItemId($vars['gcid']);
            break;
        case 'google':
        case 'gcalendar':
            // do nothing
            break;


        case 'predictionranking':
            if (isset($segments[2])) {
                $vars['prediction_id'] = $segments[2];
            }
            if (isset($segments[3])) {
                $vars['pggroup'] = $segments[3];
            }
            if (isset($segments[4])) {
                $vars['pj'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['r'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['pggrouprank'] = $segments[6];
            }
            if (isset($segments[7])) {
                $vars['type'] = $segments[7];
            }
            if (isset($segments[8])) {
                $vars['from'] = $segments[8];
            }
            if (isset($segments[9])) {
                $vars['to'] = $segments[9];
            }


            break;

        case 'predictionentry':
            if (isset($segments[2])) {
                $vars['prediction_id'] = $segments[2];
            }
             if (isset($segments[3])) {
                $vars['pggroup'] = $segments[3];
            }
            if (isset($segments[4])) {
                $vars['pj'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['r'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['uid'] = $segments[6];
            }

            break;

        case 'predictionresults':
            if (isset($segments[2])) {
                $vars['prediction_id'] = $segments[2];
            }
            if (isset($segments[3])) {
                $vars['pggroup'] = $segments[3];
            }
            if (isset($segments[4])) {
                $vars['pj'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['r'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['uid'] = $segments[6];
            }
            break;

        case 'predictionusers':
            if (isset($segments[2])) {
                $vars['prediction_id'] = $segments[2];
            }
            if (isset($segments[3])) {
                $vars['uid'] = $segments[3];
            }
            if (isset($segments[4])) {
                $vars['pggroup'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['pj'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['r'] = $segments[5];
            }

            break;

        case 'predictionrules':
            if (isset($segments[2])) {
                $vars['prediction_id'] = $segments[2];
            }

            break;

        case 'allprojects':
            if (isset($segments[1])) {
                $vars['filter_search_nation'] = $segments[1];
            }


            if (isset($segments[2])) {
                $vars['filter_search_leagues'] = $segments[2];
            }

            break;

        case 'clubinfo':

            if (isset($segments[4])) {
                $vars['cid'] = $segments[4];
            }
            break;
        case 'curve':

            if (isset($segments[4])) {
                $vars['tid1'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['tid2'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['division'] = $segments[6];
            }

            break;
        case 'eventsranking':

            if (isset($segments[4])) {
                $vars['division'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['tid'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['evid'] = $segments[6];
            }
            if (isset($segments[7])) {
                $vars['mid'] = $segments[7];
            }
            break;
        case 'editmatch':
        case 'editevents':

            if (isset($segments[4])) {
                $vars['mid'] = $segments[4];
            }
            break;
        case 'matrix':

            if (isset($segments[4])) {
                $vars['division'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['r'] = $segments[5];
            }
            break;
        case 'playground':

            if (isset($segments[4])) {
                $vars['pgid'] = $segments[4];
            }
            break;
        case "ranking":
        case 'rankingmatrix':

            if (isset($segments[4])) {
                $vars['type'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['r'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['from'] = $segments[6];
            }
            if (isset($segments[7])) {
                $vars['to'] = $segments[7];
            }
            if (isset($segments[8])) {
                $vars['division'] = $segments[8];
            }
            
            if (isset($segments[9])) {
                $vars['order'] = $segments[9];
            }
            if (isset($segments[10])) {
                $vars['dir'] = $segments[10];
            }
            break;

        case 'teamplan':

            if (isset($segments[4])) {
                $vars['tid'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['division'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['mode'] = $segments[6];
            }
            // diddipoeler
            if (isset($segments[7])) {
                $vars['ptid'] = $segments[7];
            }

            break;

        case 'clubplan':

            if (isset($segments[4])) {
                $vars['cid'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['task'] = $segments[5];
            }

            break;

        case 'roster':
        case 'teaminfo':
        case 'teamstats':

            if (isset($segments[4])) {
                $vars['tid'] = $segments[4];
            }
            //diddipoeler
            if (isset($segments[5])) {
                $vars['ptid'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['division'] = $segments[6];
            }
            break;

        case 'results':
        case 'resultsmatrix':
        case 'resultsranking':

            if (isset($segments[4])) {
                $vars['r'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['division'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['mode'] = $segments[6];
            }
            if (isset($segments[7])) {
                $vars['order'] = $segments[7];
            }
            if (isset($segments[8])) {
                $vars['layout'] = $segments[8];
            }

            break;

        case 'matchreport':
        case 'nextmatch':

            if (isset($segments[4])) {
                $vars['mid'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['pics'] = $segments[5];
            }
            if (isset($segments[6])) {
                $vars['ptid'] = $segments[6];
            }
            break;

        case 'player':
        case 'staff':

            if (isset($segments[4])) {
                $vars['tid'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['pid'] = $segments[5];
            }
            break;
        case 'clubs':
        case 'stats':
        case 'teams':

            if (isset($segments[4])) {
                $vars['division'] = $segments[4];
            }
            break;
        case 'statsranking':

            if (isset($segments[4])) {
                $vars['division'] = $segments[4];
            }
            if (isset($segments[5])) {
                $vars['tid'] = $segments[5];
            }
            break;
            // /standard-referee/referee/46/2
        case 'referee':

            if (isset($segments[4])) {
                $vars['pid'] = $segments[4];
            }
            break;
        case 'tree':

            if (isset($segments[5])) {
                $vars['did'] = $segments[5];
            }
            break;

        default:

            break;
    }

    if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO) {
        $my_text = 'vars -><pre>' . print_r($vars, true) . '</pre>';
        $my_text .= 'segments -><pre>' . print_r($segments, true) . '</pre>';
        //$my_text .= 'dump -><pre>'.print_r($query->dump(),true).'</pre>';
        sportsmanagementHelper::setDebugInfoText(__method__, __function__,
            'sportsmanagementRoute', __line__, $my_text);
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' segments<br><pre>'.print_r($segments,true).'</pre>'   ),'');
    }


    return $vars;
}
?>