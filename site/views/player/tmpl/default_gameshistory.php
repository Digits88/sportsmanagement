<?php 
/** SportsManagement ein Programm zur Verwaltung f?r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: ? 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie k?nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp?teren
* ver?ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n?tzlich sein wird, aber
* OHNE JEDE GEW?HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew?hrleistung der MARKTF?HIGKEIT oder EIGNUNG F?R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f?r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

defined('_JEXEC') or die('Restricted access'); 


$picture_path_sport_type_name = 'images/com_sportsmanagement/database/events';
//if ( $this->project->fs_sport_type_name )
//{
//$picture_path_sport_type_name = 'images/com_sportsmanagement/database/events/'.$this->project->fs_sport_type_name;
//}

?>
<!-- Player stats History START -->
<?php
if (count($this->games))
{
	?>
<h2><?php echo JText::_('COM_SPORTSMANAGEMENT_PERSON_GAMES_HISTORY'); ?></h2>
<table class="<?PHP echo $this->config['history_table_class']; ?>" >
	<tr>
		<td>
		<table id="gameshistory" class="<?PHP echo $this->config['history_table_class']; ?>">
			<thead>
				<tr class="sectiontableheader">
					<th class="td_l" colspan="6"><?php echo JText::_('COM_SPORTSMANAGEMENT_PERSON_GAMES'); ?></th>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution'] == 1)
					{
						?>
					<th class="td_c"><?php
					$imageTitle = JText::_('COM_SPORTSMANAGEMENT_PERSON_STARTROSTER');
                    $picture = $picture_path_sport_type_name.'/startroster.png';
                    //echo $picture;
                    if ( !curl_init($picture) )
{
$picture = sportsmanagementHelper::getDefaultPlaceholder("icon");
}
					echo JHtml::image($picture,$imageTitle,array(' title' => $imageTitle));
					?></th>
					<th class="td_c"><?php
					$imageTitle = JText::_('COM_SPORTSMANAGEMENT_PERSON_IN');
                    $picture = $picture_path_sport_type_name.'/in.png';
                    if ( !curl_init($picture) )
{
$picture = sportsmanagementHelper::getDefaultPlaceholder("icon");
}
					echo JHtml::image($picture,$imageTitle,array(' title' => $imageTitle));
					?></th>
					<th class="td_c"><?php
					$imageTitle = JText::_('COM_SPORTSMANAGEMENT_PERSON_OUT');
                    $picture = $picture_path_sport_type_name.'/out.png';
                    if ( !curl_init($picture) )
{
$picture = sportsmanagementHelper::getDefaultPlaceholder("icon");
}
					echo JHtml::image($picture,$imageTitle,array(' title' => $imageTitle));
					?></th>
                    
                    <th class="td_c"><?php
				$imageTitle=JText::_('COM_SPORTSMANAGEMENT_PLAYED_TIME');
                $picture = $picture_path_sport_type_name.'/uhr.png';
                if ( !curl_init($picture) )
{
$picture = sportsmanagementHelper::getDefaultPlaceholder("icon");
}
				echo JHtml::image($picture,$imageTitle,array('title'=> $imageTitle,'height'=> 11));
		?></th>
        
					<?php
					}
					if ($this->config['show_career_events_stats'])
					{
						if (count($this->AllEvents))
						{
							foreach($this->AllEvents as $eventtype)
							{
								?>
					<th class="td_c"><?php
					$iconPath = $eventtype->icon;
					if ( !strpos(" ".$iconPath,"/") )
					{
						$iconPath = "images/com_sportsmanagement/database/events/".$iconPath;
					}
                    
                    if ( !curl_init($iconPath) )
{
$iconPath = sportsmanagementHelper::getDefaultPlaceholder("icon");
}

					echo JHtml::image(	$iconPath,JText::_($eventtype->name),
					array(	"title" => JText::_($eventtype->name),
																		"align" => "top",
																		"hspace" => "2"));
					?></th>
					<?php
							}
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{

							//do not show statheader when there are no stats
							if (!empty($stat)) {
							    if ($stat->showInPlayer()) {
							?>
					<th class="td_c"><?php echo $stat->getImage(); ?></th>
					<?php
							    }
							}
						}
					}
                    if ($this->config['show_player_market_value'] )
                    {
                    ?>
					<th class="td_c"><?php echo JText::_('COM_SPORTSMANAGEMENT_EURO_MARKET_VALUE'); ?></th>
					<?php    
                    }
                    
					?>
				</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			$total = array();
			$total['startRoster'] = 0;
			$total['in'] = 0;
			$total['out'] = 0;
            $total['playedtime'] = 0;
			$total_event_stats = array();
            
            //echo ' games<br><pre>'.print_r($this->games,true).'</pre><br>';
			
            foreach ($this->games as $game)
			{
			 $routeparameter = array();
$routeparameter['cfg_which_database'] = JRequest::getInt('cfg_which_database',0);
$routeparameter['s'] = JRequest::getInt('s',0);
$routeparameter['p'] = $this->project->slug;
$routeparameter['mid'] = $game->match_slug;
$report_link = sportsmanagementHelperRoute::getSportsmanagementRoute('matchreport',$routeparameter); 
$routeparameter = array();
$routeparameter['cfg_which_database'] = JRequest::getInt('cfg_which_database',0);
$routeparameter['s'] = JRequest::getInt('s',0);
$routeparameter['p'] = $this->project->slug;
$routeparameter['tid'] = $this->teams[$game->projectteam1_id]->team_slug;
$routeparameter['ptid'] = 0;
$teaminfo_home_link = sportsmanagementHelperRoute::getSportsmanagementRoute('teaminfo',$routeparameter);				
$routeparameter['tid'] = $this->teams[$game->projectteam2_id]->team_slug;				
$teaminfo_away_link = sportsmanagementHelperRoute::getSportsmanagementRoute('teaminfo',$routeparameter);
				
				// gespielte zeit
                $model = $this->getModel();
                $timePlayed = $model->getTimePlayed($this->teamPlayer->id,$this->project->game_regular_time,$game->id,$this->overallconfig['person_events']);
                
//echo __FILE__.' '.__LINE__.' teamPlayer->id<br><pre>'.print_r($this->teamPlayer->id,true).'</pre><br>';
//echo __FILE__.' '.__LINE__.' game->id<br><pre>'.print_r($game->id,true).'</pre><br>';
//echo __FILE__.' '.__LINE__.' timePlayed<br><pre>'.print_r($timePlayed,true).'</pre><br>';
                
                ?>
				<tr class="">
					<td class="td_l"><?php
					echo JHtml::link($report_link,strftime($this->config['games_date_format'],strtotime($game->match_date)));
					?></td>
					<td class="td_r<?php if ($game->projectteam_id == $game->projectteam1_id) echo " playerteam"; ?>">
						<?php 
						if ( $this->config['show_gameshistory_teamlink'] ) 
                        {
echo sportsmanagementHelperHtml::getBootstrapModalImage('gameshistory'.$game->id.'-'.$game->projectteam1_id,$game->home_logo,$game->home_name,'20');							
                            echo JHtml::link($teaminfo_home_link, $this->teams[$game->projectteam1_id]->name); 
						} 
                        else 
                        {
echo sportsmanagementHelperHtml::getBootstrapModalImage('gameshistory'.$game->id.'-'.$game->projectteam1_id,$game->home_logo,$game->home_name,'20');							
                            echo $this->teams[$game->projectteam1_id]->name;
						}
						?>
					</td>
					<td class="td_r"><?php echo $game->team1_result; ?></td>
					<td class="td_c"><?php echo $this->overallconfig['seperator']; ?></td>
					<td class="td_l"><?php echo $game->team2_result; ?></td>
					<td class="td_l<?php if ($game->projectteam_id == $game->projectteam2_id) echo " playerteam"; ?>">
						<?php 
						if ( $this->config['show_gameshistory_teamlink'] ) 
                        {
echo sportsmanagementHelperHtml::getBootstrapModalImage('gameshistory'.$game->id.'-'.$game->projectteam2_id,$game->away_logo,$game->away_name,'20');                            
							echo JHtml::link($teaminfo_away_link, $this->teams[$game->projectteam2_id]->name); 
						} 
                        else 
                        {
echo sportsmanagementHelperHtml::getBootstrapModalImage('gameshistory'.$game->id.'-'.$game->projectteam2_id,$game->away_logo,$game->away_name,'20');                            
							echo $this->teams[$game->projectteam2_id]->name;
						}
						?>
					</td>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution']==1)
					{
						?>
					<td class="td_c"><?php
					$total['startRoster'] += $game->started;
					//echo ($game->started) ;
                    echo ($game->started > 0 ? $game->started : $this->overallconfig['zero_events_value']);
					?></td>
					<td class="td_c"><?php
					$total['in'] += $game->sub_in;
					//echo ($game->sub_in) ;
                    echo ($game->sub_in > 0 ? $game->sub_in : $this->overallconfig['zero_events_value']);
					?></td>
					<td class="td_c"><?php
					$total['out'] += $game->sub_out;
					//echo ($game->sub_out) ;
                    echo ($game->sub_out > 0 ? $game->sub_out : $this->overallconfig['zero_events_value']);
					?></td>
                    
                    <td class="td_c"><?php
					$total['playedtime'] += $timePlayed;
					echo ($timePlayed) ;
					?></td>
                    
					<?php
					}
					if ($this->config['show_career_events_stats'] && isset($this->AllEvents) )
					{
						foreach($this->AllEvents as $eventtype)
						{
							?>
					<td class="td_c"><?php
					if(!isset($total_event_stats[$eventtype->id]))
					{
						$total_event_stats[$eventtype->id]=0;
					}
					if(isset($this->gamesevents[$game->id][$eventtype->id]))
					{
						$total_event_stats[$eventtype->id] += $this->gamesevents[$game->id][$eventtype->id];
						echo $this->gamesevents[$game->id][$eventtype->id];
					}
					else
					{
						// as only matches are shown here where the player was part of, output a 0 i.s.o. a '-'
						//echo 0;
                        echo $this->overallconfig['zero_events_value'];
					}
					?></td>
					<?php
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{
							//do not show statheader when there are no stats
							if (!empty($stat)) { 
							    if ($stat->showInPlayer()) {
							?>
					<td class="td_c hasTip" title="<?php echo $stat->name; ?>"><?php
								if (isset($stat->gamesstats[$game->id]))
								{
									echo $stat->gamesstats[$game->id]->value;
								}
								else
								{
									// as only matches are shown here where the player was part of, output a 0 i.s.o. a '-'
									//echo 0;
                                    echo $this->overallconfig['zero_events_value'];
								}
					?></td>
					<?php
							    }
							}
						}
					}
                    if ($this->config['show_player_market_value'] )
                    {
                    ?>
					<td class="td_r hasTip" title="<?php echo number_format($game->market_value,0, ",", "."); ?>">
                    <?php    
                    }
					?>
				</tr>
				<?php
				$k=(1-$k);
			}
			?>
				<tr class="career_stats_total">
					<td class="td_r" colspan="6"><b><?php echo JText::_('COM_SPORTSMANAGEMENT_PERSON_GAMES_TOTAL'); ?></b></td>
					<?php
					if ($this->config['show_substitution_stats'] && $this->overallconfig['use_jl_substitution']==1)
					{
					?>
					<td class="td_c"><?php echo ($total['startRoster'] > 0 ? $total['startRoster'] : $this->overallconfig['zero_events_value']); ?></td>
					<td class="td_c"><?php echo ($total['in'] > 0 ? $total['in'] : $this->overallconfig['zero_events_value']); ?></td>
					<td class="td_c"><?php echo ($total['out'] > 0 ? $total['out'] : $this->overallconfig['zero_events_value']); ?></td>
                    <td class="td_c"><?php echo ($total['playedtime'] ) ; ?></td>
					<?php
					}
					if ($this->config['show_career_events_stats'])
					{
						if (count($this->AllEvents))
						{
							foreach($this->AllEvents as $eventtype)
							{
								?>
					<td class="td_c"><?php echo $total_event_stats[$eventtype->id]; ?></td>
					<?php
							}
						}
					}
					if ($this->config['show_career_stats'] && is_array($this->gamesstats))
					{
						foreach ($this->gamesstats as $stat)
						{
							//do not show statheader when there are no stats
							if (!empty($stat)) { 
							    if ( $stat->showInPlayer() && isset($stat->gamesstats['totals']) ) {
							?>
							    
					<td class="td_c hasTip" title="<?php echo $stat->name; ?>">
					<?php 
                    //echo $stat->gamesstats['totals']->value;
                    echo ($stat->gamesstats['totals']->value > 0 ? $stat->gamesstats['totals']->value : $this->overallconfig['zero_events_value']); 
                    ?>
					</td>
					<?php
							    }
							}
						}
					}
					?>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
</table>

<?php
}
?>
