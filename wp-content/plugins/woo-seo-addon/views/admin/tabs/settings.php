<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>

	<?php if(!is_plugin_active('premmerce-woocommerce-brands/premmerce-brands.php') && is_plugin_active('woocommerce/woocommerce.php')) : ?>

        <table class="form-table">

            <tr valign="top">
                <th><?php _e('Brand', 'woo-seo-addon') ?></th>
                <td>
                    <select name="premmerce_seo_brand_field">
                        <option value="0" <?php selected(!get_option('premmerce_seo_brand_field')); ?>><?php _e('Not specified', 'woo-seo-addon'); ?></option>

						<?php foreach(wc_get_attribute_taxonomies() as $attribute) : ?>

                            <option value="<?=$attribute->attribute_name;?>" <?php selected($attribute->attribute_name == get_option('premmerce_seo_brand_field')); ?>><?=$attribute->attribute_label;?></option>

						<?php endforeach ?>
                    </select>
                    <p class="description"><?php _e('Select the attribute of the product that is used as a brand', 'woo-seo-addon') ?></p>
                </td>
            </tr>

        </table>

	<?php endif ?>

    <hr>

    <table class="form-table">

        <tr valign="top">
            <th><?php _e('Address', 'woo-seo-addon') ?></th>
            <td>
                <input name="premmerce_seo_address" value="<?=get_option('premmerce_seo_address');?>"/>
                <p class="description"><?php _e('For example', 'woo-seo-addon') ?>: 20341 Whitworth Institute
                    405 N. Whitworth</p>
            </td>
        </tr>

        <tr valign="top">
            <th><?php _e('Email', 'woo-seo-addon') ?></th>
            <td>
                <input name="premmerce_seo_email" value="<?=get_option('premmerce_seo_email');?>"/>
                <p class="description"><?php _e('For example', 'woo-seo-addon') ?>: jane-doe@xyz.ed</p>
            </td>
        </tr>

        <tr valign="top">
            <th><?php _e('Phone', 'woo-seo-addon') ?></th>
            <td>
                <input name="premmerce_seo_telephone" value="<?=get_option('premmerce_seo_telephone');?>"/>
                <p class="description"><?php _e('For example', 'woo-seo-addon') ?>: +18005551234</p>
            </td>
        </tr>

        <tr valign="top">
            <th><?php _e('Opening hours', 'woo-seo-addon') ?></th>
            <td>
                <input name="premmerce_seo_openingHours" value="<?=get_option('premmerce_seo_openingHours');?>"/>
                <p class="description"><?php _e('For example', 'woo-seo-addon') ?>: Mo,Tu,We,Th
                    09:00-12:00</p>
            </td>
        </tr>

        <tr valign="top">
            <th><?php _e('Payment accepted', 'woo-seo-addon') ?></th>
            <td>
                <input name="premmerce_seo_paymentAccepted"
                       value="<?=get_option('premmerce_seo_paymentAccepted');?>"/>
                <p class="description"><?php _e('For example', 'woo-seo-addon') ?>: Cash, Credit Card</p>
            </td>
        </tr>

    </table>

    <input type="hidden" name="action" value="update"/>
    <input type="hidden" name="page_options"
           value="premmerce_seo_brand_field,premmerce_seo_address,premmerce_seo_email,premmerce_seo_telephone,premmerce_seo_openingHours,premmerce_seo_paymentAccepted"/>

	<?php submit_button(__('Save Changes')); ?>
</form>