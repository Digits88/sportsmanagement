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
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n?tzlich sein wird, aber
* OHNE JEDE GEW?HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew?hrleistung der MARKTF?HIGKEIT oder EIGNUNG F?R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f?r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
* 
* https://www.spiralscripts.co.uk/Joomla-Tips/modal-windows-in-joomla-3.html
* 
* Note : All ini files need to be saved as UTF-8 without BOM
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

//echo '<pre>'.print_r($this->config,true).'</pre>';

if ( !isset ( $this->club ) )
{
	JError::raiseWarning( 'ERROR_CODE', JText::_( 'Error: ClubID was not submitted in URL or Club was not found in database' ) );
}
else
{

?>
<div class="row">
<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <?PHP

	?>
		<!-- SHOW LOGO - START -->
		<?php
		if ( $this->config['show_club_logo'] && $this->club->logo_big != '' )
		{
			$club_emblem_title = str_replace( "%CLUBNAME%", $this->club->name, JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_EMBLEM_TITLE' ) );
			$picture = $this->club->logo_big;
           			
		}
		
        if ( !sportsmanagementHelper::existPicture($picture) )
    {
    $picture = sportsmanagementHelper::getDefaultPlaceholder('logo_big');    
    }
    
echo sportsmanagementHelperHtml::getBootstrapModalImage('clubinfo'.$this->club->id,$picture,$club_emblem_title,$this->config['club_logo_width']);        

        if ( $this->config['show_club_logo_copyright'] )
		{
		//echo JText::_( "COM_SPORTSMANAGEMENT_PAINTER_INFO" );
        echo JText::sprintf('COM_SPORTSMANAGEMENT_PAINTER_INFO','<i>'.$this->club->cr_logo_big.'</i>');
        ?> 
<!--        : &copy; -->	
        <?PHP 
        //echo $this->club->cr_logo_big;  			
		}        
        
        ?>
<br />
        
		<!-- SHOW LOGO - END -->
		<!-- SHOW SMALL LOGO - START -->
		<?php
		if (( $this->config['show_club_shirt']) && ( $this->club->logo_small != '' ))
		{
			$club_trikot_title = str_replace( "%CLUBNAME%", $this->club->name, JText::_( "COM_SPORTSMANAGEMENT_CLUBINFO_TRIKOT_TITLE" ) );
			$picture = $this->club->logo_small;
			echo sportsmanagementHelper::getPictureThumb($picture,$club_emblem_title,20,20,3);				
		}
    if ( $this->club->website )
		{
      if( $this->config['show_club_internetadress_picture'] )
      {
      echo '<img style="" src="http://www.thumbshots.de/cgi-bin/show.cgi?url='.$this->club->website.'">';
      }
      
		}
    
    
		?>
		<!-- SHOW SMALL LOGO - END -->
	</div>
    
	<?php
        if( $this->config['show_club_info'] )
        {
        ?>
	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		<?php
		if (  $this->club->address  ||  $this->club->zipcode ||  $this->club->location )
		{

//echo $this->club->name.'<br />';
//echo $this->club->address.'<br />';
//echo $this->club->state.'<br />';
//echo $this->club->zipcode.'<br />';
//echo $this->club->location.'<br />';
//echo $this->club->country.'<br />';
			$addressString = JSMCountries::convertAddressString($this->club->name,
																$this->club->address,
																$this->club->state,
																$this->club->zipcode,
																$this->club->location,
																$this->club->country,
																'COM_SPORTSMANAGEMENT_CLUBINFO_ADDRESS_FORM' );
			?>
            <address>
			<strong><?php
				echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_ADDRESS' );
				//$dummyStr = explode('<br />', $addressString);
				//for ($i = 0; $i < count($dummyStr); $i++) { echo '<br />'; }
				//echo '<br />';
				?></strong>
			<?php echo $addressString; ?>
			
			<span class="clubinfo_listing_value">
			<?php 
            if ( isset($this->clubassoc->name) )
            {
            echo JHtml::image($this->clubassoc->assocflag, $this->clubassoc->name, array('title' => $this->clubassoc->name, 'width' => $this->config['club_assoc_flag_width'] ) ); 
            echo JHtml::image($this->clubassoc->picture, $this->clubassoc->name, array('title' => $this->clubassoc->name, 'width' => $this->config['club_assoc_logo_width'] ) ).substr($this->clubassoc->name,0,30);
            }
            ?>
      <br />
      </span>
		</address>	
			
			<?php
		}

		if ( $this->club->phone )
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_PHONE' ); ?></strong>
			<?php echo $this->club->phone; ?>
            </address>
			<?php
		}

		if ( $this->club->fax)
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_FAX' ); ?></strong>
			<?php echo $this->club->fax; ?>
            </address>
			<?php
		}

		if ($this->club->email)
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_EMAIL' ); ?></strong>
			
				<?php
				// to prevent spam, crypt email display if nospam_email is selected
				//or user is a guest
				$user = JFactory::getUser();
				if ( ( $user->id ) or ( ! $this->overallconfig['nospam_email'] ) )
				{
					?><a href="mailto: <?php echo $this->club->email; ?>"><?php echo $this->club->email; ?></a><?php
				}
				else
				{
					//echo JHtml::_('email.cloak', $this->club->email );
                    echo $this->club->email;
				}
				?>
			</address>
			<?php
		}

		if ( $this->club->website )
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_WWW' ); ?></strong>
			
				<?php echo JHtml::_( 'link', $this->club->website, $this->club->website, array( "target" => "_blank" ) ); ?>
			
            </address>
			<?php
      
      
		}

		if ( $this->club->president )
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_PRESIDENT' ); ?></strong>
			<?php echo $this->club->president; ?>
            </address>
			<?php
		}

		if ( $this->club->manager )
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_MANAGER' ); ?></strong>
			<?php echo $this->club->manager; ?>
            </address>
			<?php
		}

		if ( $this->club->founded && $this->club->founded != '0000-00-00' && $this->config['show_founded'] )
		{
			?>
            <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_FOUNDED' ); ?></strong>
			<?php echo sportsmanagementHelper::convertDate($this->club->founded,1) ; ?>
            </address>
			<?php
		}
    if ( $this->club->founded_year && $this->config['show_founded_year']  )
		{    
      ?>
      <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_FOUNDED_YEAR' ); ?></strong>
			<?php echo $this->club->founded_year; ?>
            </address>
			<?php
    }
    if ( $this->club->dissolved && $this->club->dissolved != '0000-00-00' && $this->config['show_dissolved']  )
		{  
      ?>
		<address>	
      <strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_DISSOLVED' ); ?></strong>
			<?php echo sportsmanagementHelper::convertDate($this->club->dissolved,1) ?>   
            </address>   
			<?php
    }
    if ( $this->club->dissolved_year && $this->config['show_dissolved_year']  )
		{  
      ?>
      <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_DISSOLVED_YEAR' ); ?></strong>
			<?php echo $this->club->dissolved_year; ?>
            </address>
			<?php
    }
    if ( $this->club->unique_id )
		{  
      ?>
      <address>
			<strong><?php echo JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_UNIQUE_ID' ); ?></strong>
			<?php echo $this->club->unique_id; ?>
            </address>
			<?php
    }        

		if ( $this->config['show_playgrounds_of_club'] && ( isset( $this->stadiums ) ) && ( count( $this->stadiums ) > 0 ) )
		{
			?>
			<!-- SHOW PLAYGROUNDS - START -->
			<?php
				$playground_number = 1;
				foreach ( $this->playgrounds AS $playground )
				{
				    $routeparameter = array();
$routeparameter['cfg_which_database'] = JRequest::getInt('cfg_which_database',0);
$routeparameter['s'] = JRequest::getInt('s',0);
$routeparameter['p'] = $this->project->slug;
$routeparameter['pgid'] = $playground->slug;
$link = sportsmanagementHelperRoute::getSportsmanagementRoute('playground',$routeparameter);
					$pl_dummy = JText::_( 'COM_SPORTSMANAGEMENT_CLUBINFO_PLAYGROUND' );
					?>
                    <address>
					<strong>
                    <?php 
                    echo str_replace( "%NUMBER%", $playground_number, $pl_dummy ); 
                    ?>
                    </strong>
					<?php 
                    echo JHtml::link( $link, $playground->name ); 
                    if ( !sportsmanagementHelper::existPicture($playground->picture) )
    {
    $playground->picture = sportsmanagementHelper::getDefaultPlaceholder('team');    
    }
echo sportsmanagementHelperHtml::getBootstrapModalImage('playground'.$playground->id,$playground->picture,$playground->name,$this->config['playground_picture_width']);                    
                    
                    ?>
                    </address>
					<?php
					$playground_number++;
				}
			?>
			<!-- SHOW PLAYGROUNDS - END -->
			<?php
		}
        
        if ( $this->config['show_club_kunena_link'] && $this->club->sb_catid )
		{
		  ?>
<span class="clubinfo_listing_item">
</span>
<?PHP
$link = sportsmanagementHelperRoute::getKunenaRoute( $this->club->sb_catid );
$imgTitle = JText::_($this->club->name.' Forum');
$desc = JHtml::image('media/COM_SPORTSMANAGEMENT/jl_images/kunena.logo.png', $imgTitle, array('title' => $imgTitle,'width' => '100' ));
		?>
<span class="clubinfo_listing_value">
<?PHP
echo JHtml::link($link, $desc);
		?>
</span>
<?PHP
		}

//if ( $this->clubhistorysorttree )
//{
/*
echo JHtml::_('select.genericlist',$this->clubhistoryfamilytree,'division_id'.$row->id,
'class="form-control form-control-inline" size="'.sizeof($this->clubhistoryfamilytree).'"'.$append,'id','treename',$row->division_id);
*/   
		
if ( $this->familytree )
{
$class_collapse = 'collapse in';
}
else
{
$class_collapse = 'collapse';
} 

		
?>
<a href="#fusion" class="btn btn-info btn-block" data-toggle="collapse">
<strong>
<?php echo JText::_('Fusionen'); ?>
</strong>
</a>
<div id="fusion" class="<?PHP echo $class_collapse; ?>">
<div class="tree">

<ul>
<li>
<a href="#"><?PHP echo JHTML::image($this->club->logo_big, $this->club->name, 'width="30"').' '.$this->club->name; ?></a>

<?php 
echo $this->familytree; 
?>
      
</li>
</ul>
</div>
</div>
<?PHP        
//}

?>
</div>
<!-- </div> --> 
<!--    </div> -->
    
<?php
}
}
?>
