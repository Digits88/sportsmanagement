<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sportsmanagement&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" >
 
	
 
<div class="col50">
<?php
echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
echo JHtml::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_DETAILS'), 'panel1');
echo $this->loadTemplate('details');

echo JHtml::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_EVENTS'), 'panel2');
echo $this->loadTemplate('events');

echo JHtml::_('tabs.panel',JText::_('COM_SPORTSMANAGEMENT_TABS_STATISTICS'), 'panel3');
echo $this->loadTemplate('statistics');

echo JHtml::_('tabs.end');
?>	
</div> 
	<div>
		<input type="hidden" name="task" value="position.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
