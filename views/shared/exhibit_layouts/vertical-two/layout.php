<head>
<link href="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/css/OpenLayersZoom.css" media="all" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayers.js"></script>
<script type="text/javascript" src="http://localhost/omeka-2.3/plugins/OpenLayersZoom/views/shared/javascripts/OpenLayersZoom.js"></script>
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
     <?php echo $this->exhibitAttachmentGallery($attachments, array(), array(), false, true); ?>
</div>
