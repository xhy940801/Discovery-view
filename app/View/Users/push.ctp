<head>  
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />  
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
	<title>User Push</title>  
	<style type="text/css">  
		html{height:100%}  
		body{height:100%;margin:0px;padding:0px}  
		#container{height:100%;z-index: 99;}
		#pictBlock{position: absolute;background-color: red;height: 100px;width: 100px}
		.test{background:-webkit-gradient(linear,0 0,0 100%, color-stop(0.0,rgba(11,22,30,0.8)), color-stop(1.0,rgba(10,37,51,0.5)));box-shadow: 0px 0px 70px rgba(0,255,255,0.3);z-index: 100;position: absolute;left: 0px;top: 0px;width: 100%;border-bottom:1px solid rgba(0,255,255,0.8)}
	</style>  
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=G6t1cqDFWQBUr5GODv2naEqS"></script>
</head>  
   
<body>
	<div id="pictBlock">
		<img src="<?php echo $this->Html->publicUrl("forTest2.jpg")?>">
	</div>  
	<nav class="top-bar test" data-topbar role="navigation" data-options="sticky_on: large"></nav>
	
	<div id="container"></div>
	<script type="text/javascript">
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
		//map.addControl(new BMap.CopyrightControl());

		function PictureOverlay(center,length,color){
			this._center = center;
			this._length = length;
			this._color = color;
		}
		PictureOverlay.prototype = new BMap.Overlay();
		PictureOverlay.prototype.initialize  = function(map){
			this._map = map;
			var div = $("#pictBlock").get();
			$("#pictBlock").children("img")[0].style.width = "100%";
			$("#pictBlock").children("img")[0].style.height = "100%";
			/*var div = document.createElement("div");
			div.style.position = "absolute";
			div.style.backgroundColor = this._color;
		    div.style.height = this._length + "px";
		    div.style.width = this._length + "px";*/
		    map.getPanes().mapPane.appendChild(div[0]);
		    this._div = div[0];
		    return this._div;
		}
		PictureOverlay.prototype.draw = function(){
			var position = this._map.pointToOverlayPixel(this._center);
			this._div.style.left = position.x - this._length / 2 + "px";
			this._div.style.top = position.y - this._length / 2 + "px";
		}
		PictureOverlay.prototype.show = function(){    
			if(this._div){    
			   this._div.style.display = "";    
			}
		}      
		PictureOverlay.prototype.hide = function(){    
			if (this._div){    
			   this._div.style.display = "none";    
			}    
		}
		var myPictOverlay = new PictureOverlay(map.getCenter(),100,"red");
		map.addOverlay(myPictOverlay);

		var polygon = new BMap.Polygon([
			new BMap.Point(113.25, 23.10),
			new BMap.Point(113.26,23.11),
			new BMap.Point(113.26,23.09)
		], {strokeColor:"blue", strokeWeight:2, fillOpacity:1});  //创建多边形
		//map.addOverlay(polygon);
	</script>  
</body> 