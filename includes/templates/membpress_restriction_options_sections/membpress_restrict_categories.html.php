<?php
/**
* Contains the html for Restrict Categories section in Restriction Options Page

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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_restrict_categories'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?> 
<div id="membpress_restrict_categories" class="postbox<?php if(!isset($_COOKIE['membpress_restrict_categories']) || !$_COOKIE['membpress_restrict_categories']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Restrict Categories', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('MembPress lets you restrict categories (and sub-categories) by binding them to different membership levels. You can enter the IDs of the categories (or sub-categories) (in a comma separated way like 12,10,5) you want to restrict against each membership level. MembPress will make those categories restricted and only the user with the required membership level will be able to access them. Any such attempt without required membership level will redirect the user to MemberShip Options Page (can be configured in \'Basic Setup -> Membership Options Page\').<br>A category restricted by a higher membership level will take precedence over all the lower membership levels. For example, Category ID 2 set for membership level 1 and membership level 4 will be restricted by membership levels 4, 3, 2, 1, 0.', 'membpress_restrict', 'membpress'); ?> </p>
            <p>
            <?php echo _x('Note that if a parent category is restricted, all the sub-categories will be restricted automatically by the same membership level unless any sub-category is restricted explicitly. All the posts under a restricted category will be restricted by the respective level. But if a post is in multiple categories, the category with higher membership level assigned will take precedence.', 'membpress_restrict', 'membpress'); ?>
            </p>
            <?php
            // iterate through all membpress membership levels
			// and show the post restrict option for each of them
			foreach ($mp_levels as $mp_level):
			
			// get the list of categories restricted by the current membership level
			$mp_restrict_categories_by_curr_level = get_option('membpress_restrict_categories_level_' . $mp_level['level_no']);
			$mp_restrict_categories_by_curr_level = implode(',', (array)$mp_restrict_categories_by_curr_level);
			
			?>
            <p>
            <strong><?php echo sprintf(_x('Restrict Categories by Membership Level %s (%s):', 'membpress_restrict', 'membpress'), $mp_level['level_no'], $mp_level['display_name']); ?></strong>
            </p>
            <p>
            <label for="membpress_restrict_categories_level_<?php echo $mp_level['level_no']; ?>"><?php echo _x('Provide the Category IDs, separated by a comma(,):', 'membpress_restrict', 'membpress'); ?></label><br><input type="text" name="membpress_restrict_categories_level_<?php echo $mp_level['level_no']; ?>" value="<?php echo $mp_restrict_categories_by_curr_level; ?>" style="width:100%" id="membpress_restrict_categories_level_<?php echo $mp_level['level_no']; ?>">
            </p>
            <?php
            endforeach;
			?>
            <hr>
            <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_restrict_submit-membpress_restrict_category_section" name="membpress_restrict_submit-membpress_restrict_category_section">
          </div>
        </div>