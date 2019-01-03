
(function(){
	var Loader = {
		base: "http://api4.mapy.cz",
		mode: "single",
		version: "113",
		async: false,

		_callback: false,
		_files: {
			css: {
				api: ["/css/api/api.css", "/css/api/card.css"],
				poi: "/css/api/poi.css"
			},

			single: {
				jak: "/js/api/jak.js",
				api: ["/js/api/smap.js", "/config.js?key={key}"],
				poi: "/js/api/poi.js",
				"api-simple": ["/js/api/smap-simple.js", "/config.js?key={key}"]
			},
			
			multi: {
				jak: "/js/api/jak/jak.js",
				api: [
					
						"/js/api/jak/graphics.js",
					
						"/js/api/jak/window.js",
					
						"/js/api/jak/xml.js",
					
						"/js/api/jak/interpolator.js",
					
						"/js/api/jak/rpc.js",
					
						"/js/api/jak/frpc.js",
					
						"/js/api/jak/base64.js",
					
						"/js/api/api/map.js",
					
						"/js/api/api/map-iowned.js",
					
						"/js/api/api/util.js",
					
						"/js/api/api/projection.js",
					
						"/js/api/api/projection-oblique.js",
					
						"/js/api/api/layer.js",
					
						"/js/api/api/layer-tile.js",
					
						"/js/api/api/layer-tile-oblique.js",
					
						"/js/api/api/layer-wms.js",
					
						"/js/api/api/layer-smart.js",
					
						"/js/api/api/layer-turist.js",
					
						"/js/api/api/layer-marker.js",
					
						"/js/api/api/layer-geometry.js",
					
						"/js/api/api/geometry.js",
					
						"/js/api/api/geometry-multi.js",
					
						"/js/api/api/marker.js",
					
						"/js/api/api/marker-poi.js",
					
						"/js/api/api/marker-repositioner.js",
					
						"/js/api/api/marker-cluster.js",
					
						"/js/api/api/marker-clusterer.js",
					
						"/js/api/api/card.js",
					
						"/js/api/api/control.js",
					
						"/js/api/api/control-keyboard.js",
					
						"/js/api/api/control-mouse.js",
					
						"/js/api/api/control-orientation.js",
					
						"/js/api/api/control-overview.js",
					
						"/js/api/api/control-layer.js",
					
						"/js/api/api/control-zoom.js",
					
						"/js/api/api/control-copyright.js",
					
						"/js/api/api/control-minimap.js",
					
						"/js/api/api/control-rosette.js",
					
						"/js/api/api/contextmenu.js",
					
						"/js/api/api/contextmenu-item.js",
					
						"/js/api/util/gpx.js",
					
						"/js/api/util/kml.js",
					
						"/js/api/util/geocoder.js",
					
						"/js/api/util/logger.js",
					
						"/js/api/util/route.js",
					
						"/js/api/api/eggs.js",
					
					 "/config.js?key={key}"
				],
				poi: [
					
						
						"/js/api/poi/poiserver.js"
					
						,
						"/js/api/poi/poiserver-xml.js"
					
						,
						"/js/api/poi/poiserver-frpc.js"
					
						,
						"/js/api/poi/dataprovider.js"
					
						,
						"/js/api/poi/layer-lookup.js"
					
						,
						"/js/api/poi/marker-fotopoi.js"
					
						,
						"/js/api/poi/marker-trafficpoi.js"
					
						,
						"/js/api/poi/geometry-trafficpoi.js"
					
						,
						"/js/api/poi/marker-trafficdetail.js"
					
						,
						"/js/api/poi/geometry-traffic.js"
					
						,
						"/js/api/poi/detail.js"
					
						,
						"/js/api/poi/detail-card.js"
					
						,
						"/js/api/poi/detail-builder.js"
					
						,
						"/js/api/poi/detail-builder-base.js"
					
						,
						"/js/api/poi/detail-builder-traffic.js"
					
						,
						"/js/api/poi/detail-builder-foto.js"
					
						,
						"/js/api/poi/detail-builder-region.js"
					
						,
						"/js/api/poi/detail-builder-premise.js"
					
						,
						"/js/api/poi/detail-builder-transport.js"
					
						,
						"/js/api/poi/detail-content.js"
					
						,
						"/js/api/poi/detail-content-title.js"
					
						,
						"/js/api/poi/detail-content-media.js"
					
						,
						"/js/api/poi/detail-content-typename.js"
					
						,
						"/js/api/poi/detail-content-link.js"
					
						,
						"/js/api/poi/detail-content-description.js"
					
						,
						"/js/api/poi/detail-content-gallery.js"
					
						,
						"/js/api/poi/detail-content-infolinks.js"
					
						,
						"/js/api/poi/detail-content-image.js"
					
						,
						"/js/api/poi/detail-content-actions.js"
					
						,
						"/js/api/poi/detail-content-transport.js"
					
						,
						"/js/api/poi/detail-content-transportlines.js"
					
						,
						"/js/api/poi/detail-content-departuresnext.js"
					
						,
						"/js/api/poi/detail-content-arrivalsnext.js"
					
						,
						"/js/api/poi/detail-content-departureslast.js"
					
						,
						"/js/api/poi/detail-content-keyvalue.js"
					
						,
						"/js/api/poi/detail-content-tzn.js"
					
						,
						"/js/api/poi/detail-content-contact.js"
					
						,
						"/js/api/poi/detail-content-additionalinfo.js"
					
						,
						"/js/api/poi/detail-content-address.js"
					
						,
						"/js/api/poi/detail-content-parking.js"
					
						,
						"/js/api/poi/detail-content-opening.js"
					
						,
						"/js/api/poi/detail-content-partner.js"
					
						,
						"/js/api/poi/detail-content-person.js"
					
						,
						"/js/api/poi/detail-content-photo.js"
					
						,
						"/js/api/poi/detail-content-admission.js"
					
						,
						"/js/api/poi/detail-content-lifetime.js"
					
						,
						"/js/api/poi/detail-content-map.js"
					
						,
						"/js/api/poi/detail-content-price.js"
					
						,
						"/js/api/poi/detail-content-poilink.js"
					
						,
						"/js/api/poi/poi.js"
					
						,
						"/js/api/poi/poi-builder.js"
					
						,
						"/js/api/poi/poi-content.js"
					
						,
						"/js/api/poi/def.js"
					
				],
				"api-simple": [
					
						"/js/api/api/map-simple.js",
					
						"/js/api/api/util.js",
					
						"/js/api/api/projection.js",
					
					 "/config.js?key={key}"
				]
			}
		},
		
		load: function(key_, what_, callback) {
			var key = key_ || "";
			var what = {
				jak: true,
				poi: false,
				api: "full"
			};
			for (var p in what_) { what[p] = what_[p]; }
			if (callback) { this._callback = callback; }
			
			/* soupis souboru k naloadovani */
			var list = [];
			var css = [];
			var files = this._files[this.mode];
			if (what.jak && !window.JAK) { list = list.concat(files.jak); }
			if (what.api == "simple" && !window.SMap) { list = list.concat(files["api-simple"]); }
			if (what.api == "full" && !window.SMap) {
				list = list.concat(files.api);
				css = css.concat(this._files.css.api);
			}
			if (what.poi && !(window.SMap && window.SMap.Detail)) {
				list = list.concat(files.poi);
				css = css.concat(this._files.css.poi);
			}

			/* mozna neni co delat? */
			if (!list.length) { 
				if (this._callback) { this._callback(); }
				return;
			}
			/* vyrobit celou cestu */
			for (var i=0;i<list.length;i++) { 
				var value = list[i];
				value = value.replace(/{key}/, key);
				if (value.indexOf("?") != -1) {
					value += "&";
				} else {
					value += "?";
				}
				value += "v=" + (this.version == 0 ? Math.random() : this.version);
				list[i] = this.base + value; 
			}
			this._loadList(list);

			/* nacist css */
			var parent = (document.getElementsByTagName("head")[0] || document.documentElement);
			while (css.length) {
				var link = document.createElement("link");
				link.rel = "stylesheet";
				link.type = "text/css";
				link.href = this.base + css.shift() + "?v" + (this.version == 0 ? Math.random() : this.version);
				parent.appendChild(link);
			}
		},

		_onLoad: function() {
			this.async = true;
		},
		
		_loadAsync: function(list) {
			var head = document.getElementsByTagName("head")[0];
			
			function readyStateChange(e) {
				var elm = e.srcElement;
				if (elm.readyState == 'loaded' || elm.readyState == 'complete') { loadNext(); }
			}
			
			function loadNext() {
				if (!list.length) {
					if (Loader._callback) { Loader._callback();  }
					return;
				}
				
				var name = list.shift();
				var script = document.createElement("script");
				script.charset = "utf-8";
				
				if (script.attachEvent) {
					script.attachEvent("onreadystatechange", readyStateChange);
				} else {
					script.addEventListener("load", loadNext, false);
				}
				
				script.type = "text/javascript";
				script.src = name;
				head.appendChild(script);
			}
			
			loadNext();
		},
		
		_loadSync: function(list) {
			for (var i=0;i<list.length;i++) {
				document.write('<script charset="utf-8" type="text/javascript" src="'+list[i]+'"></script>');
			}
			if (this._callback) { setTimeout(this._callback, 0); }
		},
		
		_loadList: function(list) {
			if (this.async) {
				this._loadAsync(list);
			} else {
				this._loadSync(list);
			}
		}		
	};
	
	window.Loader = Loader;
	window.onload = function() { Loader._onLoad(); };
})();




