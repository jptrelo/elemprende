<?php
/**
 * Field system by YotuWP
 */
class YotuFields{
    
    public function __construct()
    {

    }

    public function render_field($data){

    	ob_start();
		?>
		<div class="yotu-field">
			<label for="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>"><?php echo esc_attr($data['label']);?></label>
			<div class="yotu-field-input">
				<?php call_user_func_array(array($this, $data['type']), array($data));?>
				<label for="yotu-<?php echo esc_html($data['group']) . '-'. esc_attr($data['name']);?>"><?php echo $data['description'];?></label>
			</div>
			
		</div>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
    }
    public function color($data){
    ?>
        <input type="text" id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param yotu-colorpicker" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" data-css="<?php echo (isset( $data['css'] ) ? $data['css'] : '');?>" value="<?php echo (isset( $data['value'] ) ? $data['value'] : $data['default']);?>" />
    <?php
	}

    public function text($data){
    ?>
        <input type="text" id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" value="<?php echo (isset( $data['value'] ) ? $data['value'] : $data['default']);?>" />
    <?php
	}

	public function select($data){
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];
	?>
    <select id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]">
        <?php
            foreach ($data['options'] as $key => $val) {
            ?>
            <option value="<?php echo $key;?>"<?php echo ($value == $key)? ' selected="selected"' : '';?>><?php echo $val;?></option>
            <?php
            }
        ?>
    </select>
	<?php
	}

	public function toggle($data){
        global $yotuwp;
	?>
	<label class="yotu-switch">
		<input type="checkbox" id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" <?php echo ($data['value'] == 'on' ) ? 'checked="checked"' : '';?> />
		<span class="yotu-slider yotu-round"></span>
	</label>
	<?php
	}

	public function radios($data){
        global $yotuwp;
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];

	?>
	<div class="yotu-radios-img yotu-radios-img-<?php echo isset($data['class'])? $data['class']:'full';?>">
		<?php
            foreach ($data['options'] as $key => $val) {
            	$id = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $key;
            	$selected = ($value == $key)? ' yotu-field-radios-selected' : ''
            ?>
            <label class="yotu-field-radios<?php echo $selected;?>" for="<?php echo $id;?>">
            	<input class="yotu-param" value="<?php echo $key;?>" type="radio"<?php echo ($value == $key) ? ' checked="checked"' : '';?> id="<?php echo $id;?>" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" />
            	<img src="<?php echo $yotuwp->assets_url . $val['img'];?>" alt="<?php echo $val['title'];?>" title="<?php echo $val['title'];?>"/>
            	<br/><span><?php echo $val['title'];?></span>
            </label>
            <?php
            }
        ?>
	</div>
	<?php
	}

	public function buttons($data){
        global $yotuwp;
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];

	?>
	<div class="yotu-radios-img-buttons yotu-radios-img yotu-radios-img-<?php echo isset($data['class'])? $data['class']:'full';?>">
		<?php
            for ($i=1; $i<=4; $i++) {
            	$id = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $i;
            	$selected = ($value == $i)? ' yotu-field-radios-selected' : ''
            ?>
            <label class="yotu-field-radios<?php echo $selected;?>" for="<?php echo $id;?>">
            	<input value="<?php echo $i;?>" type="radio"<?php echo ($value == $i) ? ' checked="checked"' : '';?> id="<?php echo $id;?>" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" class="yotu-param" />
            	<a href="#" class="yotu-button-prs yotu-button-prs-<?php echo $i;?>"><?php echo __('Prev', 'yotuwp-easy-youtube-embed');?></a>
            	<a href="#" class="yotu-button-prs yotu-button-prs-<?php echo $i;?>"><?php echo __('Next', 'yotuwp-easy-youtube-embed');?></a>
                <br/>
                <span><?php echo sprintf( __('Style %s', 'yotuwp-easy-youtube-embed'), $i);?></span>
            </label>
            <?php
            }
        ?>
	</div>
	<?php
	}
}