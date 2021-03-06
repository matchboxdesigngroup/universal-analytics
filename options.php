<div class="wrap">
  <h2>Universal Analytics Settings</h2>
  <br />
  <div class="col-lg-6 row">
    <form class="form-horizontal" role="form" id="google-universal-options">
      <?php wp_nonce_field( 'mdg_save_google_universal_analytics_settings', '_mdgnonce' ); ?>
      <div class="form-group">
        <label for="web_property_id" class="col-sm-3 control-label">Status</label>
        <div class="col-sm-9">
          <input id="plugin_switch" type="checkbox" name="plugin_switch" <?php if(get_option('plugin_switch')=='on'): ?> checked="checked" <?php endif; ?>>
        </div>
      </div>
      <div class="form-group">
        <label for="web_property_id" class="col-sm-3 control-label">Tracking ID</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="web_property_id" id="web_property_id" placeholder="Tracking code example: UA-30199046-1" value="<?php echo esc_attr( get_option('web_property_id') ); ?>">
          <span class="error hide"><strong>Error! </strong> match your code with this format: UA-30199046-1</span> </div>
      </div>
      <div class="form-group">
        <label for="web_property_id" class="col-sm-3 control-label"></label>
        <div class="col-sm-9"> <i>Advanced settings:</i> </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="in_footer" id="in_footer" <?php if(get_option('in_footer')=='on'): ?> checked="checked" <?php endif; ?>>
              Place code in footer </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="track_links" id="track_links" <?php if(get_option('track_links')=='on'): ?> checked="checked" <?php endif; ?>>
              Track events (Downloads, Mailto &amp; Outbound URLs) </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="enable_display" id="enable_display" <?php if(get_option('enable_display')=='on'): ?> checked="checked" <?php endif; ?>>
              Enable Display Advertising</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="anonymize_ip" id="anonymize_ip" <?php if(get_option('anonymize_ip')=='on'): ?> checked="checked" <?php endif; ?>>
              Anonymize IP</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <?php global $wp_roles;

     $roles = $wp_roles->get_names(); ?>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="tracking_off_for_role" id="tracking_off_for_role" <?php if(get_option('tracking_off_for_role')=='on'): ?> checked="checked" <?php endif; ?>>
              Disable Tracking For
              <select id="tracking_off_for_this_role">
                <?php foreach($roles as $role) { ?>
                <option value="<?php echo esc_attr( $role );?>" <?php if(get_option('tracking_off_for_this_role')== $role){echo 'selected="selected"';} ?>><?php echo esc_attr( $role );?></option>
                <?php } ?>
              </select>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
          <button type="button" class="btn btn-primary" id="save-gua-settings">Save Changes</button>
          <span class="alert alert-success hide"><strong>Options Saved</strong></span> </div>
      </div>
    </form>
  </div>
  <div class="clearfix"></div>
</div>
</br>
