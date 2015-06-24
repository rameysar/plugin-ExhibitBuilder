<head>
<link href="/Classics/plugins/OpenLayersZoom/views/shared/css/OpenLayersZoom.css" media="all" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="/Classics/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayers.js"></script>
<script type="text/javascript" src="/Classics/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayersZoomCompare.js"></script>
</head>

<?php
$galleryFileSize = isset($options['gallery-file-size'])
    ? html_escape($options['gallery-file-size'])
    : 'fullsize';
$captionPosition = isset($options['captions-position'])
    ? html_escape($options['captions-position'])
    : 'center';
	?>
	
<div class="gallery <?php if ($showcaseFile || !empty($text)) echo "$galleryPosition"; ?> captions-<?php echo $captionPosition; ?>">
     <?php echo $this->exhibitAttachmentZoom($attachments, array(), array(), false, true); ?>
</div>
