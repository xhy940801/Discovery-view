<head>  
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />  
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
	<title>User Push</title>  
	
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=G6t1cqDFWQBUr5GODv2naEqS"></script>
</head>  
   
<body>
	<div id="one" class="pictBlock">
		<img id="img1" class="image">
	</div>
	<nav class="top-bar mainNav" data-topbar role="navigation" data-options="sticky_on: large">
	</nav>

	<div id="firstModal" class="reveal-modal large modalBg " data-reveal >
        <div id="centerDiv" class="left" >
        </div>
        <div class="testDiv right">
            <div id="pictUserInfo" style="height:20%">
                <div style="height:100%;width:30%;background-color:blue;float:left"></div>
                <div id="pictUserName" style="height:50%;width:70%;float:left"></div>
                <div style="height:50%;width:30%;background-color:black;float:left"></div>
            </div>
            <div id="pictRemark" style="height:30%;background-color:green"></div>
            <div id="like" style="height:50%;background-color:purple"></div>
        </div>
      <a id="closeModal" class="close-reveal-modal">&#215;</a>
    </div>
    <div id="bg" class="reveal-modal-bg" style="display: none"></div>

	<div id="container" data-pictures='<?php echo json_encode($pictureInfo); ?>'></div>
	<?php
		echo $this->Html->css('overlay');
		echo $this->Html->script('overlay');
		echo $this->Html->script('map');
	?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			var imgbaseurl = "<?php echo $this->Html->url(array('controller' => 'Files', 'action' => 'getImg')); ?>";
			var images = [];
			var pinfos = $("#container").data("pictures");
			for (var i = pinfos.length - 1; i >= 0; i--) {
				var info = pinfos[i];
				images.push({position: {x: info.longitude, y: info.latitude}, url: imgbaseurl + "/" + info.fileId, otherInfo: info});
			};

			var map = new BMap.Map("container",{mapType:BMAP_SATELLITE_MAP});          // 创建地图实例

			var point = new BMap.Point(113.25, 23.10);  // 创建点坐标  
			map.centerAndZoom(point, 14);                 // 初始化地图，设置中心点坐标和地图级别  
			map.enableScrollWheelZoom();

			//map.addControl(new BMap.NavigationControl());
			//map.addControl(new BMap.ScaleControl());
			//map.addControl(new BMap.OverviewMapControl());
			var opts = {anchor:BMAP_ANCHOR_BOTTOM_LEFT};
			//var opt2 = {type:BMAP_MAPTYPE_CONTROL_MAP};
			map.addControl(new BMap.MapTypeControl(opts));

			map.addEventListener("zoomend", function()
			{
				while(overlays.length > 0)
					map.removeOverlay(overlays.pop());
				run(resultImages, map);
			});

			var resultImages = giveUpImages(images);
			flushView(resultImages.mapImages, map);
		});
	</script>

</body> 