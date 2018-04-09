<?php
global $yotuwp;

$settings = $yotuwp->data['settings'];
$data = $yotuwp->data['data'];
$classes = apply_filters( 'yotu_classes', array("yotu-videos yotu-mode-grid"), $settings);
?>
<div class="<?php echo implode(" ", $classes);?>">
	<ul>
		<?php
		$total = count($data->items);
		if(is_object($data) && $total >0):

			$count = 0;

			foreach($data->items as $video){
				$thumb = $yotuwp->get_thumb($video);
				$videoId = $yotuwp->getVideoId($video);
			?>
			<li class="<?php echo $count==0?' yotu-first':''; echo ($count+1)==$total?' yotu-last':'';?>">
				<a href="#" class="yotu-video" data-videoid="<?php echo $videoId;?>" data-title="<?php echo $yotuwp->encode($video->snippet->title);?>">
					<div class="yotu-video-thumb-wrp">
						<div>
							<img class="yotu-video-thumb" src="<?php echo $thumb;?>" alt="<?php echo $video->snippet->title;?>">	
						</div>
					</div>
					<?php if(isset($settings['title']) && $settings['title'] == 'on'):?>
						<h3 class="yotu-video-title"><?php echo $video->snippet->title;?></h3>
					<?php endif;?>
					<?php if(isset($settings['description']) && $settings['description'] == 'on'):?>
						<p class="yotu-video-description"><?php echo $yotuwp->description($video);?></p>
					<?php endif;?>
				</a>
			</li>
				
			<?php
			$count++;
			}
		endif;	
		?>
	</ul>
</div>