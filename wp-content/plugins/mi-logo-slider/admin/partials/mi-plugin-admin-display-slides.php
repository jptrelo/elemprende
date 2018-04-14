<div class="wrap mi-plugin-wrapper mi-plugin-display-main">
	<div class="mi-plugin-container">

		<?php
		$created_row_values = get_option('logo_items');
		if(gettype($created_row_values) === 'array'):
		?>
		<table class="wp-list-table widefat fixed striped mi-plugin-table">
			<thead>
				<tr>
					<th scope="col" class="column-title"><?php esc_html_e('Title', 'mi_logo'); ?></th>
					<th scope="col" class="column-shortcode"><?php esc_html_e('Shortcode', 'mi_logo'); ?></th>
					<th scope="col" class="column-action"><?php esc_html_e('Edit', 'mi_logo'); ?></th>
				</tr>
			</thead>

			<?php foreach ($created_row_values as $created_row_key => $created_row_value ):?>
				
			<?php
			$id			= array_search($created_row_value, $created_row_values);
			$title		= $created_row_values[$id]['title'];
			$shortcode	= "[mi-logo id=".$created_row_key."]";

			?>

			<tbody id="the-list">
				<tr>
					<td class="column-title" data-colname="Title">
						<strong><a class="row-title" href="?page=<?php echo $this->plugin_name; ?>&new_logo_id=<?php echo $id ;?>&action=edit" aria-label="“<?php echo $title ?>” (Edit)"><?php echo $title ?></a></strong>
					</td>
					<td class="column-shortcode">
						<input type="text" onfocus="this.select();" readonly="readonly" value="<?php  echo $shortcode ?>" class="mi-plugin-shortcode code">
					</td>
					<td class="column-action">
						<a href="?page=<?php echo $this->plugin_name; ?>&new_logo_id=<?php echo $id; ?>&action=edit" class=""><span class="dashicons dashicons-edit"></span></a>

					</td>

				</tr>
				<?php
				endforeach;
				endif;
				?>
			</tbody>
		</table>

		<a id="mi-plugin-add-more-slide"  class="button-primary"><?php esc_html_e('Add New', 'mi_logo'); ?></a>


	</div>

