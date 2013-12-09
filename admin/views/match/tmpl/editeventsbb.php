<?php
/**
* @copyright	Copyright (C) 2005-2013 JoomLeague.net. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
/**
 * EditeventsBB view
 *
 * @package	Joomleague
 * @since 0.1
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');

//JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.modal' );
?>
<div id="gamesevents">
	<form method="post" id="adminForm">
		<?php
		echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
		echo JHtml::_('tabs.panel',JText::_($this->teams->team1), 'panel1');
		echo $this->loadTemplate('home');
		
		echo JHtml::_('tabs.panel',JText::_($this->teams->team2), 'panel2');
		echo $this->loadTemplate('away');
		
		echo JHtml::_('tabs.end');
		?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="match" />
		<input type="hidden" name="option" value="" id="" />
		<input type="hidden" name="boxchecked"	value="0" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</div>
<div style="clear: both"></div>