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
			var isBigImage=false;
			var isVertical=false;
			var scaleCount=1.0;
			var windowHeight=window.innerHeight;

			var revealTop=100;
			var standardTop=revealTop;
			var standardBottom=0;
			var pictLeft=0;
			var pictTop=standardTop;

			var modalHeight=400;
			var pictHeight=0;
			var pictWidth=0;

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

			function resetTL(){
				pictLeft=0;
				pictTop=standardTop;
			}

			function initModal(info){
				$('#centerDiv').css("background-image","url("+info.url+")");
				$("#pictUserName").html("<p>" + info.otherInfo.userInfo.nickname + "</p>");

				var ni = new Image();
				ni.src = info.url;
				ni.onload = function(){
					pictWidth = ni.width;
					pictHeight = ni.height;
					if(pictHeight > pictWidth){
						isVertical = true;
					}else{
						isVertical = false;
					}
				}
			}

			function adjust(isFirst){
				windowHeight = window.innerHeight;
				if(windowHeight <= 500){
					$("#centerDiv").height("500px");
				}else{
					$("#centerDiv").height(windowHeight + "px");
				}

				if(isBigImage){
					var currDivWidth = $("#centerDiv").width();
				}else{
					var currDivWidth = $("#firstModal").width() * $("#centerDiv").width() / 100;
				}

				if(isBigImage || isFirst){

					standardBottom = 0;
					if(isVertical){
						if(pictHeight > modalHeight){
							if(pictWidth <= currDivWidth){
								pictLeft = (currDivWidth - pictWidth) / 2;
								scaleCount = 1.0;
							}else{
								pictLeft = 0;
								scaleCount =  currDivWidth / pictWidth;
							}
							$("#centerDiv").css("background-size",pictWidth *scaleCount + "px");
						}else{
							scaleCount = modalHeight / pictHeight;
							$("#centerDiv").css("background-size",(pictWidth * scaleCount) + "px " + (pictHeight * scaleCount) + "px");

							pictLeft = (currDivWidth - pictWidth * scaleCount) / 2; 
						}
					}else{
						scaleCount = currDivWidth / pictWidth;

						if(scaleCount >= 1.0){
							scaleCount = 1.0;
							
							pictLeft = (currDivWidth - pictWidth * scaleCount) / 2; 

							standardBottom = (modalHeight - pictHeight * scaleCount) / 2;
							standardTop = revealTop + standardBottom;  
							pictTop = standardTop; 

						}else{
							pictLeft = 0;
							if(pictHeight > modalHeight){
								standardTop = revealTop;
								$("#centerDiv").css("background-size",pictWidth * scaleCount + "px");
							}else{
								standardBottom = (modalHeight - pictHeight * scaleCount) / 2;
								standardTop = revealTop + standardBottom;
								pictTop = standardTop;
								$("#centerDiv").css("background-size",(pictWidth * scaleCount) + "px " + (pictHeight * scaleCount) + "px");
							}
						}
					}
				
					if(pictTop > standardTop){
						pictTop = standardTop;
					}
					if(pictTop + pictHeight * scaleCount < modalHeight + revealTop - standardBottom){
							pictTop = modalHeight + revealTop - standardBottom- pictHeight * scaleCount;
					}
				}
				$("#centerDiv").css("background-position",pictLeft + "px "+pictTop+"px");
			}

			 function computeScroll(e){
				pictTop += e.wheelDelta / 3;
				if(pictTop > standardTop){
					pictTop = standardTop;
				}
				if(pictTop + pictHeight * scaleCount < modalHeight + revealTop - standardBottom){
						pictTop = modalHeight + revealTop - standardBottom - pictHeight * scaleCount;
				}
				$("#centerDiv").css("background-position",pictLeft + "px "+pictTop+"px");

				if(e && e.stopPropagation){
					e.stopPropagation();
				}
			}
			
			function bind(){
				document.addEventListener("mousewheel",computeScroll, false);
			}

			function unBind(){
				document.removeEventListener("mousewheel",computeScroll,false);
			}

			window.addEventListener("resize",function(){
				
				adjust(false);
				
			});

			function computeScroll(e){
				pictTop += e.wheelDelta / 3;
				if(pictTop > standardTop){
					pictTop = standardTop;
				}
				if(pictTop + pictHeight * scaleCount < modalHeight + revealTop - standardBottom){
						pictTop = modalHeight + revealTop - standardBottom - pictHeight * scaleCount;
				}
				$("#centerDiv").css("background-position",pictLeft + "px "+pictTop+"px");

				if(e && e.stopPropagation){
					e.stopPropagation();
				}
			}

			$(".overlay-img-block").click(function(){
				initModal($(this).data("info"));
				adjust(true);
				isBigImage = true;
				bind();
				$('#firstModal').foundation('reveal', 'open');
				$("#bg").fadeIn(250);
				
			});

			$("#closeModal").click(function(){
				isBigImage = false;
				unBind();
				$('#firstModal').foundation('reveal', 'close');
				$("#bg").fadeOut(250);
				resetTL();
			});

			$("#centerDiv").click(function(){
				isBigImage = false;
				unBind();
				$('#firstModal').foundation('reveal', 'close');
				$("#bg").fadeOut(250);
				resetTL();
			});

			$("#bg").click(function(){
				isBigImage = false;
				unBind();
				$('#firstModal').foundation('reveal', 'close');
				$("#bg").fadeOut(250);
				resetTL();
			});
		});
	</script>

</body> 