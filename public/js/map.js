var testImages = [
	{position: {x: 116.057595, y: 40.061099}, url: "test.jpg"},
	{position: {x: 116.39507, y: 39.920026}, url: "test.jpg"},
	{position: {x: 116.403694, y: 39.915599}, url: "test.jpg"},
	{position: {x: 116.407143, y: 39.920912}, url: "test.jpg"},
	{position: {x: 116.417143, y: 39.920912}, url: "test.jpg"},
	{position: {x: 116.427143, y: 39.930912}, url: "test.jpg"},
	{position: {x: 116.447143, y: 39.930912}, url: "test.jpg"},
	{position: {x: 116.447143, y: 39.920912}, url: "test.jpg"},
	{position: {x: 116.437143, y: 39.910912}, url: "test.jpg"},
	{position: {x: 116.437143, y: 39.90091}, url: "test.jpg"}
];

var overlays = [];

function  clone(jsonObj)
{
	var  buf;
	if(jsonObj  instanceof  Array)
	{
		buf = [];
		var  i = jsonObj.length;
		while  (i--)
			buf[i] = clone(jsonObj[i]);
		return  buf;
	}
	else if(jsonObj instanceof Object)
	{
		buf = {};   
		for  (var k in jsonObj)
			buf[k] = clone(jsonObj[k]);
		return  buf;
	}
	else
		return  jsonObj;
}  

function giveUpImages(images)
{
	function getCircleArea(images, start, end)
	{
		var left = images[start].position.x;
		var top = images[start].position.y;
		var bottom = images[start].position.y;
		var right = images[start].position.x;
		var returnImages = [];
		for (var i = start; i < end; ++i)
		{
			if(images[i].position.x < left)
				left = images[i].position.x;
			if(images[i].position.x > right)
				right = images[i].position.x;
			if(images[i].position.y < top)
				top = images[i].position.y;
			if(images[i].position.y > bottom)
				bottom = images[i].position.y;
			returnImages.push(images[i]);
		};
		var centerX = (left + right) / 2;
		var centerY = (top + bottom) / 2;
		var radius = Math.sqrt((top - centerY) * (top - centerY) + (left - centerX) * (left - centerX));

		for (var i = images.length - 1; i >= 0; i--)
		{
			var dx = returnImages[i].position.x - centerX;
			var dy = returnImages[i].position.y - centerY;
			returnImages[i].radius = Math.sqrt(dx * dx + dy * dy);
		};
		returnImages.sort(function(a, b){return a.radius - b.radius});

		return {
			left: left, top: top, bottom: bottom,
			right: right, centerX: centerX, centerY: centerY,
			radius: radius, images: returnImages
		};
	};

	if(images.length < 3)
	{
		var tmp = clone(images);
		return {mapImages: tmp, outImages: []};
	}
	var oImages = [];
	var data = getCircleArea(images, 0, images.length);
	oImages.push(data.images.pop());
	var data1 = getCircleArea(data.images, 0, data.images.length);
	var data2;
	var data3;
	if(data1.radius / data.radius < 0.7)
		return {mapImages: data1.images, outImages: oImages};
	else
	{
		oImages.push(data1.images.pop());
		data2 = getCircleArea(data1.images, 0, data1.images.length);
		if(data2.radius / data.radius < 0.7)
			return {mapImages: data2.images, outImages: oImages};
		else
		{
			oImages.push(data2.images.pop());
			data3 = getCircleArea(data2.images, 0, data2.images.length);
			if(data3.radius / data.radius < 0.7)
				return {mapImages: data3.images, outImages: oImages};
		}
	}
	

	return {mapImages: images, outImages: []};
}

function flushView(images, map)
{
	var points = [];
	for (var i = images.length - 1; i >= 0; i--)
	{
		var point = new BMap.Point(images[i].position.x, images[i].position.y);
		points.push(point);
	};
	var size = map.getSize();
	map.setViewport(points, {margins: [200, 60, 20, 60]});
}

function flushOverlay(tpimages, map, opt)
{
	var images = clone(tpimages);
	if(typeof(opt) != "object")
		opt = {};
	var protoOpt = {imageHeight: 180, imageWidth: 112, maxMoveHeight: 180, maxMoveWidth: 20};
	if(!opt.imageHeight)
		opt.imageHeight = protoOpt.imageHeight;
	if(!opt.imageWidth)
		opt.imageWidth = protoOpt.imageWidth;
	if(!opt.maxMoveHeight)
		opt.maxMoveHeight = protoOpt.maxMoveHeight;
	if(!opt.maxMoveWidth)
		opt.maxMoveWidth = protoOpt.maxMoveWidth;

	for (var i = images.length - 1; i >= 0; i--)
	{
		var point = new BMap.Point(images[i].position.x, images[i].position.y);
		var pixel = map.pointToOverlayPixel(point);
		pixel.y -= 15;
		images[i].pixel = pixel;
		images[i].point = point;
		var oly = new SmallTriangleOverlay(point);
		overlays.push(oly);
		map.addOverlay(oly);
	}

	function Vector(px, py)
	{
		this.x = px;
		this.y = py;
		this.add = function(vector)
		{
			return new Vector(this.x + vector.x, this.y + vector.y);
		};
		this.minus = function(vector)
		{
			return new Vector(this.x - vector.x, this.y - vector.y);
		};
		this.negate = function()
		{
			return new Vector(- this.x, - this.y);
		};
		this.model = function()
		{
			return Math.sqrt(this.x * this.x + this.y * this.y);
		};
		this.clone = function()
		{
			return new Vector(this.x, this.y);
		}
	}

	function getPowerVector(image, fromImage)
	{
		var vector = new Vector(0, 0);
		if(Math.abs(image.pixel.x - fromImage.pixel.x) < opt.imageWidth && Math.abs(image.pixel.y - fromImage.pixel.y) < opt.imageHeight)
		{
			if(Math.abs(image.pixel.x - fromImage.pixel.x) < opt.imageWidth)
				vector.x = opt.imageWidth - Math.abs(image.pixel.x - fromImage.pixel.x);
			if(Math.abs(image.pixel.y - fromImage.pixel.y) < opt.imageHeight)
				vector.y = opt.imageHeight - Math.abs(image.pixel.y - fromImage.pixel.y);
			if(image.pixel.x < fromImage.pixel.x)
				vector.x = - vector.x;
			if(image.pixel.y < fromImage.pixel.y)
				vector.y = - vector.y;
		}
		return vector;
	}
	
	function getTotalVector(image, otherImages)
	{
		var result = new Vector(0, 0);
		var hasPower = false;
		for(var i = 0; i < otherImages.length; ++i)
		{
			var vector = getPowerVector(image, otherImages[i]);
			if(vector.model() > 0)
				hasPower = true;
			result = result.add(vector);
		};
		// if(otherImages.length > 1 && result.y == 0 && hasPower)
		// 	result.y = -1;
		return result;
	}

	function move(image, mvector)
	{
		var newImage = clone(image);
		var vector = mvector.clone();
		newImage.pixel.x += vector.x;
		newImage.pixel.y += vector.y;

		if(newImage.pixel.x - newImage.oldPixel.x > opt.maxMoveWidth)
			newImage.pixel.x = opt.maxMoveWidth + newImage.oldPixel.x;
		if(newImage.pixel.x - newImage.oldPixel.x < - opt.maxMoveWidth)
			newImage.pixel.x = newImage.oldPixel.x - opt.maxMoveWidth;
		if(newImage.oldPixel.y - newImage.pixel.y > opt.maxMoveHeight)
			newImage.pixel.y = newImage.oldPixel.y - opt.maxMoveHeight;
		if(newImage.pixel.y > newImage.oldPixel.y)
			newImage.pixel.y = newImage.oldPixel.y;
		return newImage;
	}

	function correspond(images)
	{
		for(var i = 0; i < images.length; ++i)
			images[i].point = map.overlayPixelToPoint(new BMap.Pixel(images[i].pixel.x, images[i].pixel.y));
		return images;
	}

	images.sort(function(a, b){ return a.pixel.y - b.pixel.y});

	var finishImages = [];

	while(images.length > 0)
	{
		var image = images.pop();
	//	image.pixel.y -= 15;
		image.oldPixel = {x: image.pixel.x, y:image.pixel.y}
		var t = 20;
		while(t--)
		{
			var vector = getTotalVector(image, finishImages);
			var newImage = move(image, vector);
			var poVector = new Vector(newImage.pixel.x - image.pixel.x, newImage.pixel.y - image.pixel.y);
			image = newImage;
			if(poVector.model() < 1)
				break;
		};
		finishImages.push(image);
	};

	var finalImages = correspond(finishImages);
	finishImages.sort(function(a, b){return a.pixel.y - b.pixel.y});
	for (var i = 0; i < finalImages.length; ++i)
	{
		var oly = new ImageDivOverlay(finalImages[i].point, finalImages[i].url, finalImages[i].pixel, finalImages[i]);
		overlays.push(oly);
		map.addOverlay(oly);
	}
}

function run(resultImages, map)
{
	flushOverlay(resultImages.mapImages, map);
	flushOverlay(resultImages.outImages, map);
}