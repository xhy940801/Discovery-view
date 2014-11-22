function SmallTriangleOverlay(point)
{
	this._point = point;
};

SmallTriangleOverlay.prototype = new BMap.Overlay();
SmallTriangleOverlay.prototype.initialize = function(map)
{
	this._map = map;
	this._div = document.createElement("div");
	this._div.className = "overlay-triangle";
	map.getPanes().labelPane.appendChild(this._div);
	return this._div;
};
SmallTriangleOverlay.prototype.draw = function()
{
	var pixel = this._map.pointToOverlayPixel(this._point);
	var height = 10;
	var width = 10;
	this._div.style.left = (pixel.x - width / 2) + "px";
	this._div.style.top = (pixel.y - height) + "px";
};

function ImageDivOverlay(point, imgUrl, pixel, info)
{
	this._point = point;
	this._pixel = pixel;
	this._imgUrl = imgUrl;
	this._info = info
}

ImageDivOverlay.prototype = new BMap.Overlay();
ImageDivOverlay.prototype.initialize = function(map)
{
	this._map = map;
	this._div = document.createElement("div");
	this._div.className = "overlay-img-block";
	this._img = document.createElement("img");
	this._img.src = this._imgUrl;
	this._div.appendChild(this._img);
	this._div.setAttribute("data-info", JSON.stringify(this._info));
	map.getPanes().labelPane.appendChild(this._div);
	return this._div;
};

ImageDivOverlay.prototype.draw = function()
{
	var pixel = this._map.pointToOverlayPixel(this._point);
	if(this._pixel)
		pixel = this._pixel;
	var height = 180;
	var width = 112;
	this._div.style.left = (pixel.x - width / 2) + "px";
	this._div.style.top = (pixel.y - height) + "px";
};