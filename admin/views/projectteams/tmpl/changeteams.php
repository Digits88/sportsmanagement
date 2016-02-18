<?php 


defined('_JEXEC') or die('Restricted access');

?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
<button type="button" onclick="Joomla.submitform('projectteam.storechangeteams', this.form)">
				<?php echo JText::_('JSAVE');?></button>
			<button id="cancel" type="button" onclick="Joomla.submitform('projectteam.cancel', this.form)">
				<?php echo JText::_('JCANCEL');?></button>
	
	<fieldset class="adminform">
		<legend>
		<?php
		echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_CHANGEASSIGN_TEAMS' );
		?>
		</legend>
		<table class="<?php echo $this->table_data_class; ?>">
			<thead>
				<tr>
					<th class="title"><?PHP echo JText::_( '' ); ?>
					</th>
					<th class="title"><?PHP echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_CHANGE' ); ?>
					</th>
					<th class="title"><?PHP echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_SELECT_OLD_TEAM' ); ?>
					</th>
					<th class="title"><?PHP echo JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_SELECT_NEW_TEAM' ); ?>
					</th>
				</tr>
			</thead>

			<?PHP

			//$lfdnummer = 1;
			$k = 0;
			$i = 0;

			foreach ( $this->projectteam as $row )
			{
				$checked = JHtml::_( 'grid.id', 'oldteamid'.$i, $row->id, $row->checked_out, 'oldteamid' );
				$append=' style="background-color:#bbffff"';
				$inputappend	= '';
				$selectedvalue = 0;
				?>
			<tr class="<?php echo "row$k"; ?>">
				<td class="center"><?php
				echo $i;
				?>
				</td>
				<td class="center"><?php
				echo $checked;
				?>
				</td>
				<td><?php
				echo $row->name;
				?>
				</td>
				<td class="nowrap" class="center"><?php
				echo JHtml::_( 'select.genericlist', $this->lists['all_teams'], 'newteamid[' . $row->id . ']', $inputappend . 'class="form-control form-control-inline" size="1" onchange="document.getElementById(\'cboldteamid' . $i . '\').checked=true"' . $append, 'value', 'text', $selectedvalue );
				?>
				</td>
			</tr>
			<?php
			$i++;
			$k=(1-$k);
			}
			?>
		</table>
	</fieldset>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option"				value="com_joomleague" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
