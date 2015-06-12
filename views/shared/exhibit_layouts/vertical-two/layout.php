<head>
<link href="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/css/OpenLayersZoom.css" media="all" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayers.js"></script>
<script type="text/javascript" src="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayersZoom.js"></script>
</head>

<?php
$galleryFileSize = isset($options['file-size'])
    ? html_escape($options['file-size'])
    : 'fullsize';
$captionPosition = isset($options['captions-position'])
    ? html_escape($options['captions-position'])
    : 'center';
	?>

<!--once the attachments are differentiated, $attachments would become $attachment1 and $attachment2-->
<div class="gallery <?php echo "$galleryPosition"; ?> captions-<?php echo $captionPosition; ?>">
	<table style="width:100%;"
		<tr>
			<td>
				<div id="map1" class="map">
					<?php echo $this->exhibitAttachmentGallery($attachment1, array(), array(), false, true); ?>
				</div>
			</td>
			<td>
				<div id="map2" class="map">
					<?php echo $this->exhibitAttachmentGallery($attachment2, array(), array(), false, true); ?>
				</div>
			</td>
		</tr>
	</table>
