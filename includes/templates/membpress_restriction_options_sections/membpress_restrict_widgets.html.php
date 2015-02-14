<?php
/**
* Contains the html for Restrict Sidebar widgets section in Restriction Options Page

* Copyright: © 2014
* {@link http://www.membpress.com, MembPress Inc.}
* {@author Waseem Khan}
*
* Released under the terms of the GNU General Public License.
* See the directory /license/
*
* @package membpress
* @since 1.0
*/
?>
<?php
if (isset($_GET['section']) && $_GET['section'] == 'membpress_restrict_widgets'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?>

<div id="membpress_restrict_widgets" class="postbox<?php if(!isset($_COOKIE['membpress_restrict_widgets']) || !$_COOKIE['membpress_restrict_widgets']): ?> closed<?php endif; ?>">
  <div class="handlediv" title="Click to toggle"><br>
  </div>
  <h3 class="hndle"><span><?php echo _x('Restrict Widgets', 'general', 'membpress'); ?></span></h3>
  <div class="inside">
    <p> <?php echo _x('Need to restrict some sidebars and widgets via membership plans? MembPress lets you restrict sidebars and individual widgets in sidebars according to the membership levels. Check the sidebars and widgets you want to restrict against each membership level.', 'membpress_restrict', 'membpress'); ?> </p>
    <?php
	    // get sidebar widgets
		global $sidebars_widgets;
		
		// get the registered widgets
		global $wp_registered_widgets;
		
		// get all registered sidebars
		global $wp_registered_sidebars;
		
		// iterate through all membpress membership levels
		// and show the sidebar widget restrict option for each of them
		foreach ($mp_levels as $mp_level):
     ?>
     <div class="membpress_restrict_sidebars_widgets membpress_clear">
    <p> <strong><?php echo sprintf(_x('Restrict Sidebar and Widgets by Membership Level %s (%s):', 'membpress_restrict', 'membpress'), $mp_level['level_no'], $mp_level['display_name']); ?></strong> </p>
    <p> <?php echo _x('Check the Sidebar(s) and individual Widget(s) inside a sidebar you want to restrict for this level. Checking a sidebar restricts all widgets inside that sidebar.', 'membpress_restrict', 'membpress'); ?> </p>
      <?php foreach ( $wp_registered_sidebars as $sidebar ): ?>
    <ul>
      <li>
        <p>
         <?php
		     $sidebar_key = 'membpress_restrict_sidebar_' . $mp_level['level_no'] . '_sidebar_' . $sidebar['id'];
		 ?>
          <input type="checkbox" name="<?php echo $sidebar_key; ?>" value="1" id="<?php echo $sidebar_key; ?>" <?php if((bool)get_option($sidebar_key)): ?>checked<?php endif; ?>>
          <label for="<?php echo $sidebar_key; ?>"> <?php echo $sidebar['name']; ?></label>
        </p>
      </li>
      <?php
	     // get widgets for this sidebar
		 $widgets = $sidebars_widgets[$sidebar['id']];
		 
		 // iterate through each widget of this sidebar
		 foreach ($widgets as $widget):
		 ?>
		    <li>
            <p>
           <?php
		     $sidebar_widget_key = 'membpress_restrict_sidebar_' . $mp_level['level_no'] . '_' . $sidebar['id'] . '_' . $widget;
		   ?>
			<input type="checkbox" name="<?php echo $sidebar_widget_key ?>" value="1" id="<?php echo $sidebar_widget_key ?>" <?php if((bool)get_option($sidebar_widget_key)): ?>checked<?php endif; ?>>
            <label for="<?php echo $sidebar_widget_key ?>"> <?php echo $wp_registered_widgets[$widget]['name']; ?> </label>
            </p>
			</li>   
		 <?php
         endforeach;
		 // end for each for sidebar widgets
		 ?>
    </ul>
       <?php endforeach; ?>
       <div class="membpress_clear"></div>
       <hr>
       </div>
    <?php
    endforeach;
	?>
    <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_restrict_submit-membpress_restrict_widgets_section" name="membpress_restrict_submit-membpress_restrict_widgets_section">
  </div>
</div>
