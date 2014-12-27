<?php
/**
* Contains the html for Restrict HTML section in Restriction Options Page

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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_restrict_section'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?> 
<div id="membpress_restrict_section" class="postbox<?php if(!isset($_COOKIE['membpress_restrict_section']) || !$_COOKIE['membpress_restrict_section']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Restrict Section', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('You can also restrict a section of a post/page rather than restricting the whole post/page itself by using the shortcodes below.', 'membpress_restrict', 'membpress'); ?> </p>
            <?php
            // iterate through all membpress membership levels
			// and show the post restrict option for each of them
			foreach ($mp_levels as $mp_level):
			?>
            <p>
            <strong><?php echo sprintf(_x('Restrict Section by Membership Level %s (%s):', 'membpress_restrict', 'membpress'), $mp_level['level_no'], $mp_level['display_name']); ?></strong>
            </p>
            <p>
            <label for="membpress_restrict_section_level_<?php echo $mp_level['level_no']; ?>"><?php echo _x('Enclose the content you want to restrict by the following shortcode', 'membpress_restrict', 'membpress'); ?></label><br><textarea style="width:100%" id="membpress_restrict_section_level_<?php echo $mp_level['level_no']; ?>" rows="5" readonly>[membpress <?php echo $this->membpress_get_shortcode_attrs('restrict_by'); ?>=<?php echo $mp_level['level_no']; ?>]

The content/section you want to restrict goes here

[/membpress]</textarea>
            </p>
            <?php
            endforeach;
			?>
          </div>
        </div>