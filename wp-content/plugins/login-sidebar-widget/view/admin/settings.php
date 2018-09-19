<table width="100%" class="ap-table">
  <tr>
    <td colspan="2"><h3><?php _e('Login Widget Settings','login-sidebar-widget');?></h3></td>
  </tr>
  <tr>
    <td colspan="2">
    <div class="ap-tabs">
        <div class="ap-tab"><?php _e('General','login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Security','login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Error Message','login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Styling','login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Email Settings','login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Shortcode','login-sidebar-widget');?></div>
        <?php do_action('lwws_custom_settings_tab');?>
    </div>

    <div class="ap-tabs-content">
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td colspan="2"><h3><?php _e('General','login-sidebar-widget');?></h3></td>
          </tr>
          <tr>
            <td width="300"><strong><?php _e('Login Redirect Page','login-sidebar-widget');?>:</strong></td>
            <td><?php
                    $args = array(
                    'depth'            => 0,
                    'selected'         => $redirect_page,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'redirect_page',
                    'name'             => 'redirect_page'
                    );
                    wp_dropdown_pages( $args ); 
                ?> <?php _e('Or','login-sidebar-widget');?> 
                 <?php form_class::form_input('text','redirect_page_url','',esc_url( $redirect_page_url ),'','','','','','',false,'URL');?>
                </td>
          </tr>
          <tr>
            <td><strong><?php _e('Logout Redirect Page','login-sidebar-widget');?>:</strong></td>
             <td><?php
                    $args1 = array(
                    'depth'            => 0,
                    'selected'         => $logout_redirect_page,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'logout_redirect_page',
                    'name'             => 'logout_redirect_page'
                    );
                    wp_dropdown_pages( $args1 ); 
                ?></td>
          </tr>
          <tr>
            <td><strong><?php _e('Link in Username','login-sidebar-widget');?></strong></td>
            <td><?php
                    $args2 = array(
                    'depth'            => 0,
                    'selected'         => $link_in_username,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'link_in_username',
                    'name'             => 'link_in_username'
                    );
                    wp_dropdown_pages( $args2 ); 
                ?></td>
          </tr>
          <tr>
            <td><strong><?php _e('Add Remember Me','login-sidebar-widget');?></strong></td>
            <td>
            <?php 
            $login_afo_rem_status = ($login_afo_rem == 'Yes'?true:false);
            form_class::form_checkbox('login_afo_rem','',"Yes",'','','',$login_afo_rem_status);
            ?>
            </td>
          </tr>
          <tr>
            <td><strong><?php _e('Forgot Password Link','login-sidebar-widget');?></strong></td>
            <td>
                <?php
                    $args3 = array(
                    'depth'            => 0,
                    'selected'         => $login_afo_forgot_pass_link,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'login_afo_forgot_pass_link',
                    'name'             => 'login_afo_forgot_pass_link'
                    );
                    wp_dropdown_pages( $args3 ); 
                ?>
                <?php _e('Or','login-sidebar-widget');?> 
                <?php form_class::form_input('text','login_afo_forgot_pass_page_url','',esc_url( $login_afo_forgot_pass_page_url ),'','','','','','',false,'URL');?>
                <i><?php _e('Leave blank to not include the link','login-sidebar-widget');?></i>
                </td>
          </tr>
          <tr>
            <td><strong><?php _e('Register Link','login-sidebar-widget');?></strong></td>
            <td>
                <?php
                    $args4 = array(
                    'depth'            => 0,
                    'selected'         => $login_afo_register_link,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'login_afo_register_link',
                    'name'             => 'login_afo_register_link'
                    );
                    wp_dropdown_pages( $args4 ); 
                ?>
                <?php _e('Or','login-sidebar-widget');?> 
                <?php form_class::form_input('text','login_afo_register_page_url','',esc_url( $login_afo_register_page_url ),'','','','','','',false,'URL');?>
                <i><?php _e('Leave blank to not include the link','login-sidebar-widget');?></i>
                </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
        <tr>
        <td colspan="2"><h3><?php _e('Security','login-sidebar-widget');?></h3></td>
        </tr>
        <tr>
        <td width="300"><strong><?php _e('Captcha on Admin Login','login-sidebar-widget');?></strong></td>
        <td>
        <?php 
        $captcha_on_admin_login_status = ($captcha_on_admin_login == 'Yes'?true:false);
        form_class::form_checkbox('captcha_on_admin_login','',"Yes",'','','',$captcha_on_admin_login_status);
        ?>
        <i><?php _e('Check to enable captcha on admin login form','login-sidebar-widget');?></i></td>
        </tr>
        <tr>
        <td><strong><?php _e('Captcha on User Login','login-sidebar-widget');?></strong></td>
        <td>
        <?php 
        $captcha_on_user_login_status = ($captcha_on_user_login == 'Yes'?true:false);
        form_class::form_checkbox('captcha_on_user_login','',"Yes",'','','',$captcha_on_user_login_status);
        ?>
        <i><?php _e('Check to enable captcha on user login form','login-sidebar-widget');?></i></td>
        </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2">
        <div style="border:1px solid #AEAE00; width:94%; background-color:#FFF; margin:0px auto; padding:10px;">
        Click <a href="admin.php?page=login_log_afo">here</a> to check the user <strong>Login Log</strong>. Use <strong><a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">PRO</a></strong> version that has added security with <strong>Blocking IP</strong> after 5 wrong login attempts. <strong>Blocked IPs</strong> can be <strong>Whitelisted</strong> from admin panel or the <strong>Block</strong> gets automatically removed after <strong>1 Day</strong>.
        </div>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
            </td>
          </tr>
        </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td colspan="2"><h3><?php _e('Error Message','login-sidebar-widget');?></h3></td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Invalid Username Message','login-sidebar-widget');?></strong></td>
            <td><?php form_class::form_input('text','lafo_invalid_username','',$lafo_invalid_username,'','','','','50','',false,__('Error: Invalid Username','login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Username','login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Invalid Email Message','login-sidebar-widget');?></strong></td>
            <td><?php form_class::form_input('text','lafo_invalid_email','',$lafo_invalid_email,'','','','','50','',false,__('Error: Invalid email address','login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Email address','login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Invalid Password Message','login-sidebar-widget');?></strong></td>
            <td><?php form_class::form_input('text','lafo_invalid_password','',$lafo_invalid_password,'','','','','50','',false,__('Error: Invalid Username & Password','login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Password','login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
           <tr>
                <td colspan="2"><h3><?php _e('Styling','login-sidebar-widget');?></h3></td>
              </tr>
           <tr>
                <td valign="top" width="300">
                <?php 
                form_class::form_checkbox('load_default_style','',"Yes",'','','','');
                ?>
                <strong> <?php _e('Load Default Styles','login-sidebar-widget');?></strong><br />
                <?php _e('Check this and hit the save button to go back to default styling.','login-sidebar-widget');?>
                </td>
                <td><?php form_class::form_textarea('custom_style_afo','',$custom_style_afo,'','','','','','','','','height:200px; width:80%;');?></td>
              </tr>
              <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
              <tr>
            <td>&nbsp;</td>
            <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
        <tr>
        <td colspan="2"><h3><?php _e('Email Settings','login-sidebar-widget');?></h3></td>
        </tr>
        <tr>
        <td valign="top" width="300"><strong><?php _e('From Email','login-sidebar-widget');?></strong></td>
        <td><?php form_class::form_input('text','login_sidebar_widget_from_email','',$login_sidebar_widget_from_email,'','','','','50','',false,'no-reply@example.com');?>
        <i><?php _e('This will be the from email address in the emails. This will make sure that the emails do not go to a spam folder.','login-sidebar-widget');?></i>
        </td>
        </tr>
        <tr>
        <td><strong><?php _e('Reset Password Link Mail Subject','login-sidebar-widget');?></strong></td>
        <td>
        <?php form_class::form_input('text','forgot_password_link_mail_subject','',$forgot_password_link_mail_subject,'','','','','50','',false,'');?>
        </td>
        </tr>
        <tr>
        <td valign="top"><strong><?php _e('Reset Password Link Mail Body','login-sidebar-widget');?></strong>
        <p><i><?php _e('This mail will fire when a user request for a new password.','login-sidebar-widget');?></i></p>
        </td>
        <td><?php form_class::form_textarea('forgot_password_link_mail_body','',$forgot_password_link_mail_body,'','','','','','50','','','height:200px; width:100%;');?>
        <p>Shortcodes: #site_url#, #user_name#, #resetlink#</p>
        </td>
        </tr>
        <tr>
        <td><strong><?php _e('New Password Mail Subject','login-sidebar-widget');?></strong></td>
        <td>
        <?php form_class::form_input('text','new_password_mail_subject','',$new_password_mail_subject,'','','','','50','',false,'');?>
        </td>
        </tr>
        <tr>
        <td valign="top"><strong><?php _e('New Password Mail Subject Body','login-sidebar-widget');?></strong>
        <p><i><?php _e('This mail will fire when a user clicks on the password reset link provided in the above mail.','login-sidebar-widget');?></i></p>
        </td>
        <td><?php form_class::form_textarea('new_password_mail_body','',$new_password_mail_body,'','','','','','','','','height:200px; width:100%;');?>
        <p>Shortcodes: #site_url#, #user_name#, #user_password#</p>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
            </td>
          </tr>
        </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
         <tr>
    <td colspan="2"><h3><?php _e('Shortcode','login-sidebar-widget');?></h3></td>
  </tr>
          <tr>
            <td colspan="2">Use <span style="color:#000066;">[login_widget]</span> shortcode to display login form in post or page.<br />
             Example: <span style="color:#000066;">[login_widget title="Login Here"]</span></td>
          </tr>
          <tr>
            <td colspan="2">Use <span style="color:#000066;">[forgot_password]</span> shortcode to display forgot password form in post or page.<br />
             Example: <span style="color:#000066;">[forgot_password title="Forgot Password?"]</span></td>
          </tr>
        </table>
        </div>
        <?php do_action('lwws_custom_settings_tab_content');?>
    </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>