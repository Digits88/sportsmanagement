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
$templatesToLoad = array('footer');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
//Ordering allowed ?
//$ordering = ( $this->lists['order'] == 'ppl.ordering' );
$ordering = ( $this->lists['order'] == 'ppl.ordering' );

//$this->addTemplatePath( JPATH_COMPONENT . DS . 'views' . DS . 'adminmenu' );

JHtml::_('behavior.mootools');
?>
<style>
.search-item {
    font:normal 11px tahoma, arial, helvetica, sans-serif;
    padding:3px 10px 3px 10px;
    border:1px solid #fff;
    border-bottom:1px solid #eeeeee;
    white-space:normal;
    color:#555;
}
.search-item h3 {
    display:block;
    font:inherit;
    font-weight:bold;
    color:#222;
}

.search-item h3 span {
    float: right;
    font-weight:normal;
    margin:0 0 5px 5px;
    width:100px;
    display:block;
    clear:none;
}
</style>

<script>
	var quickaddsearchurl = '<?php echo JURI::root();?>administrator/index.php?option=com_sportsmanagement&task=quickadd.searchplayer&projectteam_id=<?php echo $this->teamws->id; ?>';
	function searchPlayer(val)
	{
        var s= document.getElementById("search");
        s.value = val;
        Joomla.submitform('', this.form)
	}
</script>

<fieldset class="adminform">
	<legend>
	<?php
	echo JText::_("COM_SPORTSMANAGEMENT_ADMIN_TEAMPLAYERS_QUICKADD_PLAYER");
	?>
	</legend>
	<form id="quickaddForm" action="<?php echo JURI::root(); ?>administrator/index.php?option=com_sportsmanagement&task=quickadd.addplayer" method="post">
	<input type="hidden" name="projectteam_id" id="projectteam_id" value="<?php echo $this->teamws->id; ?>" />
	<input type="hidden" id="cpersonid" name="cpersonid" value="">
	<table>
		<tr>
			<td><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_TEAMPLAYERS_QUICKADD_DESCR');?>:</td>
			<td><input type="text" name="quickadd" id="quickadd"  size="50"  /></td>
			<td><input type="submit" name="submit" id="submit" value="<?php echo JText::_('Add');?>" /></td>
		</tr>
	</table>
	<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</fieldset>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<fieldset class="adminform">
		<legend>
			<?php
			echo JText::sprintf(	'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_TITLE2',
									'<i>' . $this->project_team->name . '</i>', '<i>' . $this->project->name . '</i>' );
			?>
		</legend>
		<table>
			<tr>
				<td align="left" width="100%">
					<?php
					echo JText::_( 'JSEARCH_FILTER_LABEL' );
					?>
					<input	type="text" name="search" id="search"
							value="<?php echo $this->lists['search']; ?>" class="text_area"
							onchange="document.getElementById('search_mode').value=''; $('adminForm').submit(); " />
					<button onclick="document.getElementById('search_mode').value=''; this.form.submit(); ">
						<?php
						echo JText::_( 'JSEARCH_FILTER_SUBMIT' );
						?>
					</button>
					<button onclick="document.getElementById('search').value=''; document.getElementById('search_mode').value=''; this.form.submit(); ">
						<?php
						echo JText::_( 'JSEARCH_FILTER_CLEAR' );
						?>
					</button>
				</td>
				<td align="center" colspan="4">
					<?php
					for ( $i = 65; $i < 91; $i++ )
					{
						printf( "<a href=\"javascript:searchPlayer('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;", chr($i), chr($i) );
					}
					?>
				 </td>
			</tr>
		</table>
		<div id="editcell">
			<table class="adminlist">
				<thead>
					<tr>
						<th width="5">
							<?php
							echo JText::_( 'COM_SPORTSMANAGEMENT_GLOBAL_NUM' );
							?>
						</th>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>
						<th width="20">
							&nbsp;
						</th>
						<th>
							<?php
							echo JHtml::_( 'grid.sort', 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_NAME', 'ppl.lastname', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th>
							<?php
							echo JHtml::_( 'grid.sort', 'PID', 'ppl.person_id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th>
							<?php
							echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_IMAGE' );
							?>
						</th>
            <th width="20">
							<?php
							echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_MARKET_VALUE' );
							?>
						</th>
						<th width="20">
							<?php
							echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_SHIRTNR' );
							?>
						</th>
						<th width="20">
							<?php
							echo JHtml::_( 'grid.sort', 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_POS', 'ppl.project_position_id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th>
							<?php
							echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_STATUS' );
							?>
						</th>
						<th>
						<?php
						echo JHtml::_('grid.sort','JSTATUS','ppl.published',$this->lists['order_Dir'],$this->lists['order']);
						?></th>
						<th width="10%">
							<?php
							//echo JHtml::_( 'grid.sort', 'COM_SPORTSMANAGEMENT_GLOBAL_ORDER', 'ppl.ordering', $this->lists['order_Dir'], $this->lists['order'] );
                            echo JHtml::_( 'grid.sort', 'JGRID_HEADING_ORDERING', 'ppl.ordering', $this->lists['order_Dir'], $this->lists['order'] );
							echo JHtml::_( 'grid.order', $this->items, 'filesave.png', 'teamplayers.saveorder' );
							?>
						</th>
						<th width="5%">
							<?php
							echo JHtml::_( 'grid.sort', 'JGRID_HEADING_ID', 'ppl.id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="13">
							<?php
							echo $this->pagination->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php

					$k = 0;
					for ( $i = 0, $n = count( $this->items ); $i < $n; $i++ )
					{
						$row =& $this->items[$i];
						$link			= JRoute::_('index.php?option=com_sportsmanagement&task=teamperson.edit&project_team_id=' .
													$row->projectteam_id . '&person_id=' . $row->id. '&id=' . $row->tpid. '&pid=' .$this->project->id.'&team_id='.$this->team_id.'&persontype='.$this->_persontype);
						$checked		= JHtml::_( 'grid.checkedout', $row, $i );
						$inputappend	= '';
						?>
						<tr class="<?php echo "row$k"; ?>">
							<td class="center">
								<?php
								echo $this->pagination->getRowOffset( $i );
								?>
							</td>
							<td class="center">
								<?php
								echo $checked;
								?>
							</td>
							<?php
							if ( JTable::isCheckedOut( $this->user->get ('id'), $row->checked_out ) )
							{
								$inputappend = ' disabled="disabled"';
								?>
								<td>
									&nbsp;
								</td>
								<?php
							}
							else
							{
								?>
								<td class="center">
									<a href="<?php echo $link; ?>">
										<?php
										$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_EDIT_DETAILS' );
										echo JHtml::_(	'image', 'administrator/components/com_sportsmanagement/assets/images/edit.png',
														$imageTitle,
														'title= "' . $imageTitle . '"' );
										?>
									</a>
								</td>
								<?php
							}
							?>
							<td>
								<?php echo sportsmanagementHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, 0) ?>
							</td>
							<td class="center">
								<?php
								echo $row->person_id;
								?>
							</td>
							<td class="center">
								<?php
								if ( $row->picture == '' )
								{
									$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_NO_IMAGE' );
									echo JHtml::_(	'image',
													'administrator/components/com_sportsmanagement/assets/images/delete.png',
													$imageTitle,
													'title= "' . $imageTitle . '"' );

								}
								elseif ( $row->picture == sportsmanagementHelper::getDefaultPlaceholder("player") )
								{
										$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_DEFAULT_IMAGE' );
										echo JHtml::_(	'image',
														'administrator/components/com_sportsmanagement/assets/images/information.png',
														$imageTitle,
														'title= "' . $imageTitle . '"' );
								}
								elseif ( $row->picture == !'')
								{
									$playerName = sportsmanagementHelper::formatName(null ,$row->firstname, $row->nickname, $row->lastname, 0);
									echo sportsmanagementHelper::getPictureThumb($row->picture, $playerName, 0, 21, 4);
								}
								?>
							</td>
              <td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="4" class="inputbox"
																	name="market_value<?php echo $row->id; ?>"
																	value="<?php echo $row->market_value; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="4" class="inputbox"
																	name="jerseynumber<?php echo $row->id; ?>"
																	value="<?php echo $row->jerseynumber; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="nowrap" class="center">
								<?php
								if ( $row->project_position_id != 0 )
								{
									$selectedvalue = $row->project_position_id;
									$append = '';
								}
								else
								{
									$selectedvalue = 0;
									$append = ' style="background-color:#FFCCCC"';
								}
								if ( $append != '' )
								{
									?>
									<script language="javascript">document.getElementById('cb<?php echo $i; ?>').checked=true;</script>
									<?php
								}
								if ( $row->project_position_id == 0 )
								{
									$append=' style="background-color:#FFCCCC"';
								}
								echo JHtml::_( 'select.genericlist', $this->lists['project_position_id'], 'project_position_id' . $row->id, $inputappend . 'class="inputbox" size="1" onchange="document.getElementById(\'cb' . $i . '\').checked=true"' . $append, 'value', 'text', $selectedvalue );
								?>
							</td>
							<td class="nowrap" class="center">
								<?php
								//$row->injury = 1;
								//$row->suspension = 1;
								//$row->away = 1;
								if ( $row->injury > 0 )
								{
									$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_INJURED' );
									echo JHtml::_(	'image', 'administrator/components/com_sportsmanagement/assets/images/injured.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								if ( $row->suspension > 0 )
								{
									$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_SUSPENDED' );
									echo JHtml::_(	'image', 'administrator/components/com_sportsmanagement/assets/images/suspension.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								if ( $row->away > 0 )
								{
									$imageTitle = JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_TPLAYERS_AWAY' );
									echo JHtml::_(	'image', 'administrator/components/com_sportsmanagement/assets/images/away.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								?>
								&nbsp;
							</td>
							<td class="center">
								<?php
								echo JHtml::_('grid.published',$row,$i, 'tick.png','publish_x.png','teampersons.');
								?>
							</td>
							<td class="order">
								<span>
									<?php
									echo $this->pagination->orderUpIcon( $i, $i > 0, 'teampersons.orderup', 'JLIB_HTML_MOVE_UP', true );
									?>
								</span>
								<span>
									<?php
									echo $this->pagination->orderDownIcon( $i, $n, $i < $n, 'teampersons.orderdown', 'JLIB_HTML_MOVE_DOWN', true );
									?>
								</span>
								<?php
								$disabled = true ?	'' : 'disabled="disabled"';
								?>
								<input	type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?>
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true"
                                        class="text_area" style="text-align: center; " />
							</td>
							<td class="center">
								<?php
								echo $row->tpid;
								?>
							</td>
						</tr>
						<?php
						$k = 1 - $k;
					}
					?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<input type="hidden" name="project_team_id"		value="<?php echo $this->project_team_id; ?>" />
    <input type="hidden" name="team_id"		value="<?php echo $this->team_id; ?>" />
    <input type="hidden" name="pid"		value="<?php echo $this->project_id; ?>" />
	<input type="hidden" name="search_mode"			value="<?php echo $this->lists['search_mode'];?>" id="search_mode" />
	<input type="hidden" name="task"				value="" />
	<input type="hidden" name="boxchecked"			value="0" />
	<input type="hidden" name="filter_order"		value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir"	value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
<?PHP
echo "<div>";
echo $this->loadTemplate('footer');
echo "</div>";
?>   