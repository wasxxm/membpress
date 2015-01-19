<?php
/**
* Contains the html for Restrict URI in Restriction Options Page

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
if (isset($_GET['section']) && $_GET['section'] == 'membpress_restrict_uris'):
   $this->membpress_show_update_notice((isset($_GET['notice'])) ? $_GET['notice'] : 1, (isset($_GET['error'])) ? 'error' : 'success', (isset($_GET['n_vars'])) ? $_GET['n_vars'] : '');
endif;
?> 
<div id="membpress_restrict_uris" class="postbox<?php if(!isset($_COOKIE['membpress_restrict_uris']) || !$_COOKIE['membpress_restrict_uris']): ?> closed<?php endif; ?>">
          <div class="handlediv" title="Click to toggle"><br>
          </div>
          <h3 class="hndle"><span><?php echo _x('Restrict URIs', 'general', 'membpress'); ?></span></h3>
          <div class="inside">
            <p> <?php echo _x('You can restrict a URL by specifying a URI pattern. Any URL containing the matched URI pattern will be restricted according to the membership level. For example, if you want to restrict all URLs of the form "http://www.example.com/private/post_1", "http://www.example.com/private/post_2" etc, you can enter a pattern like "/private/post_{{ .+ }}". The pattern will be treated as a regular expression so any URI like "/private/post_2312, /private/post_543, /private/post_yh67" will be restricted. If you want to explicitly include regular expression syntax you can do so by wrapping it in double curly braces like {{ [0-9]+ }}. Let\'s say you only want to have integers after the "post_" part in our example, then you can write enter a pattern like: "/private/post_{{ [0-9]+ }}".<br><br>Please note that the pattern will be treated as case-sensitive. So "/members/" and "/Members/" are not same. If you want to make it case-insensitive, you can do so by using the regex: "/{{ (?i)members }}/". The base URL of your web site will be joined with the URI to make the full URL. Of course, you cannot restrict external URLs.', 'membpress_restrict', 'membpress'); ?> </p>
            <?php
            // iterate through all membpress membership levels
			// and show the uri restrict option for each of them
			foreach ($mp_levels as $mp_level):
			
			// get the list of URIs restricted by the current membership level
			$mp_restrict_uris_by_curr_level = get_option('membpress_restrict_uris_level_' . $mp_level['level_no']);
			$mp_restrict_uris_by_curr_level = implode("\n", (array)$mp_restrict_uris_by_curr_level);
			
			?>
            <p>
            <strong><?php echo sprintf(_x('Restrict URI by Membership Level %s (%s):', 'membpress_restrict', 'membpress'), $mp_level['level_no'], $mp_level['display_name']); ?></strong>
            </p>
            <p>
            <label for="membpress_restrict_uris_level_<?php echo $mp_level['level_no']; ?>"><?php echo _x('Enter the URI patterns, one per line, you want to restrict by this membership level.', 'membpress_restrict', 'membpress'); ?></label><br><textarea style="width:100%" id="membpress_restrict_uris_level_<?php echo $mp_level['level_no']; ?>" rows="5" name="membpress_restrict_uris_level_<?php echo $mp_level['level_no']; ?>"><?php echo $mp_restrict_uris_by_curr_level; ?></textarea>
            </p>
            <?php
            endforeach;
			?>
            <hr>
            <input type="submit" value="<?php echo _x('Save Settings', 'general', 'membpress'); ?>" class="button button-primary" id="membpress_restrict_submit-membpress_restrict_uri_section" name="membpress_restrict_submit-membpress_restrict_uri_section">
          </div>
        </div>