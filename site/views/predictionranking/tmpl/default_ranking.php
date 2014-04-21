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

defined('_JEXEC') or die(JText::_('Restricted access'));
JHTML::_('behavior.tooltip');

if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
{
echo 'this->config<br /><pre>~' . print_r($this->config,true) . '~</pre><br />';
echo 'this->items<br /><pre>~' . print_r($this->items,true) . '~</pre><br />';
echo 'this->pagination<br /><pre>~' . print_r($this->pagination,true) . '~</pre><br />';
echo 'this->limit<br /><pre>~' . print_r($this->limit,true) . '~</pre><br />';
echo 'this->limitstart<br /><pre>~' . print_r($this->limitstart,true) . '~</pre><br />';
echo 'this->limitend<br /><pre>~' . print_r($this->limitend,true) . '~</pre><br />';
}

?>

<style type="text/css">

.pred_ranking ul { 
    list-style: none; 
} 
.pred_ranking ul li { 
    display: inline; 
} 
</style>

<a name='jl_top' id='jl_top'></a>
<?php
foreach (sportsmanagementModelPrediction::$_predictionProjectS AS $predictionProject)
{
	$gotSettings = $predictionProjectSettings = sportsmanagementModelPrediction::getPredictionProject($predictionProject->project_id);
	if ((($this->model->pjID==$predictionProject->project_id) && ($gotSettings)) || ($this->model->pjID==0))
	{
		$showProjectID = (count(sportsmanagementModelPrediction::$_predictionProjectS) > 1) ? $this->model->pjID : $predictionProject->project_id;
		$this->model->pjID = $predictionProject->project_id;
		$this->model->predictionProject = $predictionProject;
		$actualProjectCurrentRound = sportsmanagementModelPrediction::getProjectSettings($predictionProject->project_id);
		
		?>
		<form name='resultsRoundSelector' method='post' >
			<input type='hidden' name='prediction_id' value='<?php echo (int)$this->predictionGame->id; ?>' />
			<input type='hidden' name='p' value='<?php echo (int)$predictionProject->project_id; ?>' />
			<input type='hidden' name='r' value='<?php echo (int)$this->roundID; ?>' />
			<input type='hidden' name='pjID' value='<?php echo (int)$showProjectID; ?>' />
			<input type='hidden' name='task' value='predictionranking.selectprojectround' />
			<input type='hidden' name='option' value='com_sportsmanagement' />
			<input type='hidden' name='pggroup' value='<?php echo (int)$this->model->pggroup; ?>' />
            <input type='hidden' name='pggrouprank' value='<?php echo (int)$this->model->pggrouprank; ?>' />

			<table class='blog' cellpadding='0' cellspacing='0' >
				<tr>
					<td class='sectiontableheader'>
						<?php
						echo '<b>'.JText::sprintf('COM_SPORTSMANAGEMENT_PRED_RANK_SUBTITLE_01').'</b>';
						?>
					</td>
					<td class='sectiontableheader' style='text-align:right; ' width='20%' nowrap='nowrap' >
          <?php
          
          echo JHTML::_('select.genericlist',$this->lists['ranking_array'],'pggrouprank','class="inputbox" size="1" onchange="this.form.submit(); "','value','text',$this->model->pggrouprank);
          
          $groups = sportsmanagementModelPrediction::getPredictionGroupList();
          $predictionGroups[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_PRED_SELECT_GROUPS'),'value','text');
                        $predictionGroups = array_merge($predictionGroups,$groups);
                        $htmlGroupOptions = JHTML::_('select.genericList',$predictionGroups,'pggroup','class="inputbox" onchange="this.form.submit(); "','value','text',$this->model->pggroup);
          echo $htmlGroupOptions;
						echo sportsmanagementModelPrediction::createProjectSelector(	sportsmanagementModelPrediction::$_predictionProjectS,
																	$predictionProject->project_id,
																	$showProjectID);
						if ($showProjectID > 0)
						{

							echo '&nbsp;&nbsp;';
							$link = sportsmanagementHelperRoute::getResultsRoute($predictionProject->project_id,$this->roundID);
							$imgTitle=JText::_('COM_SPORTSMANAGEMENT_PRED_ROUND_RESULTS_TITLE');
							$desc = JHTML::image('media/com_sportsmanagement/jl_images/icon-16-Matchdays.png',$imgTitle,array('border' => 0,'title' => $imgTitle));
							echo JHTML::link($link,$desc,array('target' => '_blank'));
						}
						?>
            </td>
			</tr>
 
                    
			</table><br />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
        $round_ids = NULL;
		if (($showProjectID > 0) && ($this->config['show_rankingnav']))
		{
			
            if ( $this->configentries['use_pred_select_rounds'] )
      {
      $round_ids = $this->configentries['predictionroundid'];
      }
            
            $from_matchday = $this->model->createFromMatchdayList($predictionProject->project_id,$round_ids);
			$to_matchday = $this->model->createToMatchdayList($predictionProject->project_id,$round_ids);
			?>
			<form action="<?php echo JRoute::_('index.php?option=com_sportsmanagement'); ?>" name='adminForm' id='adminForm' method='post'>
            <input type="hidden" name="view" value="predictionranking" />
				<table>
					<tr>
						<td><?php echo JHTML::_('select.genericlist',$this->lists['type'],'type','class="inputbox" size="1"','value','text',$this->model->type); ?></td>
						<td><?php echo JHTML::_('select.genericlist',$from_matchday,'from','class="inputbox" size="1"','value','text',$this->model->from); ?></td>
						<td><?php echo JHTML::_('select.genericlist',$to_matchday,'to','class="inputbox" size="1"','value','text',$this->model->to); ?></td>
						<td><input type='submit' class='button' name='reload View' value='<?php echo JText::_('COM_SPORTSMANAGEMENT_RANKING_FILTER'); ?>' /></td>
					</tr>

<tfoot>
<div class="pred_ranking">
<?php 
echo $this->pagination->getListFooter(); 
?>
</div>
</tfoot>                    
                    
				</table>
				<?php echo JHTML::_( 'form.token' ); ?>
			</form><br />
			<?php
/*			
<tfoot>
<tr>
<td colspan="4"></td>
</tr>
</tfoot>			
*/			
		}
		?>
		<table width='100%' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK'); ?></td>
				<?php
                
                if ( $this->model->pggrouprank )
                {
                    ?>
                <td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_MEMBER_GROUP'); ?></td>    
                    <?php
                }
                else
                {    
				if ($this->config['show_user_icon'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_AVATAR'); ?></td><?php
				}
				?>
				<td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_MEMBER'); ?></td>
				<?php
                
                if ($this->config['show_pred_group'])
				{
					?>
                <td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_MEMBER_GROUP'); ?></td>    
                    <?php
				}
                
                }


        if ($this->config['show_champion_tip'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_CHAMPION_TIP'); ?></td><?php
				}

				if ($this->config['show_tip_details'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_DETAILS'); ?></td><?php
				}
				?>
				<td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_POINTS'); ?></td>
				<?php
				if ($this->config['show_average_points'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_AVERAGE'); ?></td><?php
				}
				?>
				<?php
				if ($this->config['show_count_tips'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_PREDICTIONS'); ?></td><?php
				}
				?>
				<?php
				if ($this->config['show_count_joker'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_JOKERS'); ?></td><?php
				}
				?>
				<?php
				if ($this->config['show_count_topptips'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_TOPS'); ?></td><?php
				}
				?>
				<?php
				if ($this->config['show_count_difftips'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_MARGINS'); ?></td><?php
				}
				?>
				<?php
				if ($this->config['show_count_tendtipps'])
				{
					?><td class='sectiontableheader' style='text-align:center; vertical-align:top; '><?php echo JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_TENDENCIES'); ?></td><?php
				}
				?>
			</tr>
			<?php

        if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO)
        {
				echo 'default_ranking - this->predictionMember<br /><pre>~' . print_r($this->predictionMember,true) . '~</pre><br />';
        }
        
				$k = 0;
				$memberList = sportsmanagementModelPrediction::getPredictionMembersList($this->config,$this->configavatar);
				//$memberList = $this->items;
				
				if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO)
        {
        echo 'getPredictionMembersList<br /><pre>~' . print_r($memberList,true) . '~</pre><br />';
				}
				
				$membersResultsArray = array();
				$membersDataArray = array();
                
                if ( $this->model->pggrouprank )
                {
                $groupmembersResultsArray = array();
				$groupmembersDataArray = array();
                }

        // anfang der tippmitglieder
				foreach ($memberList AS $member)
				{

					if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
          {
          echo '<br />this->model->page<pre>~' . print_r($this->model->page,true) . '~</pre><br />';
          }
                              
					$memberPredictionPoints = sportsmanagementModelPrediction::getPredictionMembersResultsList(	$showProjectID,
																								sportsmanagementModelPrediction::$from,
																								sportsmanagementModelPrediction::$to,
																								$member->user_id,
																								sportsmanagementModelPrediction::$type);
																								
					if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
          {																			
					echo '<br />memberPredictionPoints<pre>~' . print_r($memberPredictionPoints,true) . '~</pre><br />';
					}
					
					
					
					$predictionsCount = 0;
					$totalPoints = 0;
					$ChampPoints = 0;
					$totalTop = 0;
					$totalDiff = 0;
					$totalTend = 0;
					$totalJoker = 0;
					if (!empty($memberPredictionPoints))
					{
						foreach ($memberPredictionPoints AS $memberPredictionPoint)
						{
							if ((!is_null($memberPredictionPoint->homeResult)) ||
								(!is_null($memberPredictionPoint->awayResult)) ||
								(!is_null($memberPredictionPoint->homeDecision)) ||
								(!is_null($memberPredictionPoint->awayDecision)))
							{
								$predictionsCount++;
								$result = sportsmanagementModelPrediction::createResultsObject(	$memberPredictionPoint->homeResult,
																				$memberPredictionPoint->awayResult,
																				$memberPredictionPoint->prTipp,
																				$memberPredictionPoint->prHomeTipp,
																				$memberPredictionPoint->prAwayTipp,
																				$memberPredictionPoint->prJoker,
																				$memberPredictionPoint->homeDecision,
																				$memberPredictionPoint->awayDecision);
								$newPoints = sportsmanagementModelPrediction::getMemberPredictionPointsForSelectedMatch($predictionProject,$result);
								//if (!is_null($memberPredictionPoint->prPoints))
								{
									$points=$memberPredictionPoint->prPoints;
									if ($newPoints!=$points)
									{
										// this check also should be done if the result is not displayed
										$memberPredictionPoint = sportsmanagementModelPrediction::savePredictionPoints(	$memberPredictionPoint,
																									$predictionProject,
																									true);
										$points=$newPoints;
									}
									$totalPoints=$totalPoints+$points;
								}
								if (!is_null($memberPredictionPoint->prJoker)){$totalJoker=$totalJoker+$memberPredictionPoint->prJoker;}
								if (!is_null($memberPredictionPoint->prTop)){$totalTop=$totalTop+$memberPredictionPoint->prTop;}
								if (!is_null($memberPredictionPoint->prDiff)){$totalDiff=$totalDiff+$memberPredictionPoint->prDiff;}
								if (!is_null($memberPredictionPoint->prTend)){$totalTend=$totalTend+$memberPredictionPoint->prTend;}
							}
						}
					}

          $ChampPoints = sportsmanagementModelPrediction::getChampionPoints($member->champ_tipp);
          
					$membersResultsArray[$member->pmID]['pg_group_name']				= $member->pg_group_name;
                    $membersResultsArray[$member->pmID]['pg_group_id']				= $member->pg_group_id;
                    $membersResultsArray[$member->pmID]['rank']				= 0;
					$membersResultsArray[$member->pmID]['predictionsCount']	= $predictionsCount;
					$membersResultsArray[$member->pmID]['totalPoints']		= $totalPoints + $ChampPoints;
					$membersResultsArray[$member->pmID]['totalTop']			= $totalTop;
					$membersResultsArray[$member->pmID]['totalDiff']		= $totalDiff;
					$membersResultsArray[$member->pmID]['totalTend']		= $totalTend;
					$membersResultsArray[$member->pmID]['totalJoker']		= $totalJoker;
                    
                    if ( $this->model->pggrouprank )
                    {
                    // f�r die gruppentabelle
                    $groupmembersResultsArray[$member->pg_group_id]['pg_group_id']			= $member->pg_group_id;
                    $groupmembersResultsArray[$member->pg_group_id]['pg_group_name'] = $member->pg_group_name;
                    $groupmembersResultsArray[$member->pg_group_id]['rank']				= 0;
					$groupmembersResultsArray[$member->pg_group_id]['predictionsCount']	+= $predictionsCount;
					$groupmembersResultsArray[$member->pg_group_id]['totalPoints']		+= $totalPoints + $ChampPoints;
					$groupmembersResultsArray[$member->pg_group_id]['totalTop']			+= $totalTop;
					$groupmembersResultsArray[$member->pg_group_id]['totalDiff']		+= $totalDiff;
					$groupmembersResultsArray[$member->pg_group_id]['totalTend']		+= $totalTend;
					$groupmembersResultsArray[$member->pg_group_id]['totalJoker']		+= $totalJoker;
                    }

					// check all needed output for later
					$picture = $member->avatar;
					$playerName = $member->name;					
					
					if (((!isset($member->avatar)) ||
						($member->avatar=='') ||
						(!file_exists($member->avatar)) ||
						((!$member->show_profile) && ($this->predictionMember->pmID!=$member->pmID))))
					{
						$picture = sportsmanagementHelper::getDefaultPlaceholder("player");
					}
					//tobe removed
					//$imgTitle = JText::sprintf('JL_PRED_AVATAR_OF',$member->name);
					//$output = JHTML::image($member->avatar,$imgTitle,array(' width' => 20, ' title' => $imgTitle));
					
					$output = sportsmanagementHelper::getPictureThumb($picture, $playerName,0,25);
					$membersDataArray[$member->pmID]['show_user_icon'] = $output;
                    $membersDataArray[$member->pmID]['pg_group_name']				= $member->pg_group_name;
                    $membersDataArray[$member->pmID]['pg_group_id']				= $member->pg_group_id;
                    
                    if ( $this->model->pggrouprank )
                    {
                    $groupmembersDataArray[$member->pg_group_id]['pg_group_name']				= $member->pg_group_name;
                    $groupmembersDataArray[$member->pg_group_id]['pg_group_id']				= $member->pg_group_id;
                    }

          if ( $member->aliasName )
          {
          $member->name = $member->aliasName;
          }
          
					if (($this->config['link_name_to'])&&(($member->show_profile)||($this->predictionMember->pmID==$member->pmID)))
					{
						$link = JSMPredictionHelperRoute::getPredictionMemberRoute($this->predictionGame->id,$member->pmID);
						$output = JHTML::link($link,$member->name);
					}
					else
					{
						$output = $member->name;
					}
					$membersDataArray[$member->pmID]['name'] = $output;
					
					$imgTitle = JText::sprintf('COM_SPORTSMANAGEMENT_PRED_RANK_SHOW_DETAILS_OF',$member->name);
					$imgFile = JHTML::image( "media/com_sportsmanagement/jl_images/zoom.png", $imgTitle , array(' title' => $imgTitle));
					$link = JSMPredictionHelperRoute::getPredictionResultsRoute($this->predictionGame->id ,$actualProjectCurrentRound ,$this->model->pjID,$member->pmID);
					if (($member->show_profile)||($this->predictionMember->pmID==$member->pmID))
					{
						$output = JHTML::link( $link, $imgFile);
					}
					else
					{
						$output = '&nbsp;';
					}

					$membersDataArray[$member->pmID]['show_tip_details']	= $output;
					$membersDataArray[$member->pmID]['champ_tipp']		= $member->champ_tipp;
                    
                    if ( $this->model->pggrouprank )
                    {
                    $imgTitle = JText::sprintf('COM_SPORTSMANAGEMENT_PRED_RANK_SHOW_DETAILS_OF',$member->pg_group_name);
					$imgFile = JHTML::image( "media/com_sportsmanagement/jl_images/zoom.png", $imgTitle , array(' title' => $imgTitle));
					$link = JSMPredictionHelperRoute::getPredictionResultsRoute($this->predictionGame->id ,$actualProjectCurrentRound ,$this->model->pjID,$member->pmID,'',$member->pg_group_id);
                    $output = JHTML::link( $link, $imgFile);
                    $groupmembersDataArray[$member->pg_group_id]['show_tip_details']	= $output;    
                    }    
				
                
                }
        // ende der tippmitglieder
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
				echo '<br />membersResultsArray<pre>~' . print_r($membersResultsArray,true) . '~</pre><br />';
				echo '<br />membersDataArray<pre>~' . print_r($membersDataArray,true) . '~</pre><br />';
                if ( $this->model->pggrouprank )
                {
                echo '<br />groupmembersResultsArray<pre>~' . print_r($groupmembersResultsArray,true) . '~</pre><br />';
				echo '<br />groupmembersDataArray<pre>~' . print_r($groupmembersDataArray,true) . '~</pre><br />';
                }
				}
                
                if ( $this->model->pggrouprank )
                    {
                        $computedMembersRanking = sportsmanagementModelPrediction::computeMembersRanking($groupmembersResultsArray,$this->config);
                        }
                        else
                        {
                            $computedMembersRanking = sportsmanagementModelPrediction::computeMembersRanking($membersResultsArray,$this->config);
                        }

				
				$recordCount = count($computedMembersRanking);
				
				if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
				echo '<br />computedMembersRanking<pre>~' . print_r($computedMembersRanking,true) . '~</pre><br />';
				}
				
				

				$i=1;
                
                if ( $this->model->pggrouprank )
                    {
                    $schluessel = 'pg_group_id';
                    $membersDataArray = $groupmembersDataArray;
                    $membersResultsArray = $groupmembersResultsArray;
                    }
                    else
                    {
                    $schluessel = 'pmID';    
                    }    
				


				// schleife �ber die sortierte tabelle anfang
                foreach ($computedMembersRanking AS $key => $value)
				{
				
				foreach ( $this->items as $items )
				{
				//if ( $key == $items->pmID )
                if ( $key == $items->$schluessel )
				{

					$class = ($k==0) ? 'sectiontableentry1' : 'sectiontableentry2';
					$styleStr = ($this->predictionMember->pmID==$key) ? ' style="background-color:'.$this->config['background_color_ranking'].'; color:black; " ' : '';
					$class = ($this->predictionMember->pmID==$key) ? 'sectiontableentry1' : $class;
					$tdStyleStr = " style='text-align:center; vertical-align:middle; ' ";

					
                        ?>
                        
						<tr class='<?php echo $class; ?>' <?php echo $styleStr; ?> >
							<td<?php echo $tdStyleStr; ?>><?php echo $value['rank']; ?></td>
							<?php
						if ( $this->model->pggrouprank )
                    {
                        ?>
							<td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['pg_group_name']; ?></td>
                            <td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['show_tip_details']; ?></td>
							<?php
                        }
                        else
                        {
                            if ($this->config['show_user_icon'])
							{
								?>
								<td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['show_user_icon']; ?></td>
								<?php
							}
							?>
							<td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['name']; ?></td>
							<?php
							if ($this->config['show_pred_group'])
				{
				    ?>
							<td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['pg_group_name']; ?></td>
							<?php
				    }
                    }
							// soll der meistertipp angezeigt werden ? anfang
							if ($this->config['show_champion_tip'])
							{
							if ( $membersDataArray[$key]['champ_tipp'] )
              {
                if ($this->config['show_champion_tip_club_logo'])
							{
							if ( $showProjectID )
                            {
                            $champLogo = $this->model->getChampLogo($showProjectID,$membersDataArray[$key]['champ_tipp']);    
                            
                            if ( $champLogo->name )
                            {
                            $imgTitle = $champLogo->name;
				$imgFile = JHTML::image( $champLogo->logo_big, $imgTitle , array('title' => $imgTitle, 'width' => '20' ));
                            }
                            else
                            {
                                $imgFile = '';
                            }
                            
                            } 
                            else
                            {
                            $imgTitle = JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_CHAMPION_TIP');
				$imgFile = JHTML::image( "media/com_sportsmanagement/event_icons/goal2.png", $imgTitle , array('title' => $imgTitle));    
                            }
                             
                             
							 }
                             else
                             {
              $imgTitle = JText::_('COM_SPORTSMANAGEMENT_PRED_RANK_CHAMPION_TIP');
				$imgFile = JHTML::image( "media/com_sportsmanagement/event_icons/goal2.png", $imgTitle , array('title' => $imgTitle));
              }
              ?>
              <td <?php echo $tdStyleStr; ?> >
              <?PHP
              echo $imgFile;
              ?>
              </td>
              <?PHP
              }
              else
              {
              ?>
              <td>
              </td>
              <?PHP
              }
              
							}
                            // soll der meistertipp angezeigt werden ? ende
							if ( !$this->model->pggrouprank )
                            {
							if ($this->config['show_tip_details'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php echo $membersDataArray[$key]['show_tip_details']; ?></td><?php
							}
                            }
							?>
							<td<?php echo $tdStyleStr; ?>><?php echo $membersResultsArray[$key]['totalPoints']; ?></td>
							<?php
							if ($this->config['show_average_points'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php
								if ($membersResultsArray[$key]['predictionsCount'] > 0)
								{
									echo number_format(round($membersResultsArray[$key]['totalPoints']/$membersResultsArray[$key]['predictionsCount'],2),2);
								}
								else
								{
									echo number_format(0,2);
								}
								?></td><?php
							}
							?>
							<?php
							if ($this->config['show_count_tips'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php echo $membersResultsArray[$key]['predictionsCount']; ?></td><?php
							}
							?>
							<?php
							if ($this->config['show_count_joker'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php echo $membersResultsArray[$key]['totalJoker']; ?></td><?php
							}
							?>
							<?php
							if ($this->config['show_count_topptips'])
							{
								?><td style='text-align:center; vertical-align:middle; '><?php echo $membersResultsArray[$key]['totalTop']; ?></td><?php
							}
							?>
							<?php
							if ($this->config['show_count_difftips'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php echo $membersResultsArray[$key]['totalDiff']; ?></td><?php
							}
							?>
							<?php
							if ($this->config['show_count_tendtipps'])
							{
								?><td<?php echo $tdStyleStr; ?>><?php echo $membersResultsArray[$key]['totalTend']; ?></td><?php
							}
							?>
						</tr>
						<?php
                        //}
						$k = (1-$k);
						$i++;
					  }
          }
          
				}
                // schleife �ber die sortierte tabelle ende
			?>
            
    
    
		</table>
		<?php
	}
}
?>