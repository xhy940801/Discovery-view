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

	<div id="container"></div>

	<script type="text/javascript">
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

		var onePict = "http://localhost/" + "<?php echo $this->Html->url(array('controller' => 'Files', 'action' => 'getImg'), array($pictureInfo['fileId'])); ?>";

		if(windowHeight <= 500){
        	$("#centerDiv").height("500px");
		}else{
			$("#centerDiv").height(windowHeight + "px");
		}

		var ni = new Image();
        ni.src = onePict;
        ni.onload = function(){
            pictWidth = ni.width;
            pictHeight = ni.height;
            if(pictHeight > pictWidth){
                isVertical = true;
            }else{
                isVertical = false;
            }
        }

		function resetTL(){
            pictLeft=0;
            pictTop=standardTop;
        }

        function initModal(){
        	$('#centerDiv').css("background-image","url("+onePict+")");
        	$("#pictUserName").html("<p><?php echo $pictureInfo['userInfo']['nickname']; ?></p>");
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

        $("#img1").attr("src",onePict);

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
			var div = $(".pictBlock").get();
			$(".pictBlock").children("img")[0].style.width = "100%";
			$(".pictBlock").children("img")[0].style.height = "0%";
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
			this._div.style.top = position.y + this._length / 2 + "px";
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

		$(myPictOverlay.F).click(function(){
			initModal();
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

		
		$(".image").animate({height:'100%',top:'-=' + myPictOverlay._length + 'px'},300,"swing");

	</script>  
</body> 