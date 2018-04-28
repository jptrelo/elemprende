<table class="wp-list-table widefat fixed striped mi-plugin-table">
    <thead>
    <tr>
        <th scope="col" class="column-ID"><?php esc_html_e('ID', 'mi_logo'); ?></th>
        <th scope="col" class="column-title"><?php esc_html_e('Title', 'mi_logo'); ?></th>
        <th scope="col" class="column-shortcode"><?php esc_html_e('Shortcode', 'mi_logo'); ?></th>

    </tr>
    </thead>


    <tbody id="the-list">
    <tr>
        <td class="column-title" data-colname="Title">
            <strong><input type='text' name=<?php echo "new_logo_id"; ?> placeholder="Title"
                           value='<?php echo $unique_id; ?>'/></strong>
        </td>
        <td class="column-shortcode">
            <input type='text' class="v" name=<?php echo "new_logo_title"; ?> placeholder="Title"
                   value='<?php echo $title; ?>'/>
        </td>

        <td class="column-shortcode">
            <input type='text'
                   name=<?php echo "mi_logo_shortcode"; ?> value='<?php echo $genarated_shortcode; ?>'/>
        </td>

    </tr>

    </tbody>
</table>

