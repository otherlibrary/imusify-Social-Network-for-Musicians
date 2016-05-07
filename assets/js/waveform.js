(function() {
  var JSONP, Waveform,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  Array.prototype.max = function() {
    return Math.max.apply(null, this);
  };

  window.Waveform = Waveform = (function() {

    Waveform.name = 'Waveform';

    function Waveform(options) {
      this.redraw = __bind(this.redraw, this);
      this.container = options.container;
      this.canvas = options.canvas;
      this.data = options.data || [];
      this.outerColor = options.outerColor || "transparent";
      this.innerColor = options.innerColor || "#666666";
      this.interpolate = true;
      this.bar_width= options.bar_width || 3;
      this.bar_gap = options.bar_gap || 0.2;
      this.interpolate = true;
      this.loaded_draw=false;
      if (options.interpolate === false) {
        this.interpolate = false;
      }
      if (this.canvas == null) {
        if (this.container) {
          this.canvas = this.createCanvas(this.container, options.width || this.container.clientWidth, options.height || this.container.clientHeight);
        } else {
          throw "Either canvas or container option must be passed";
        }
      }
      this.patchCanvasForIE(this.canvas);
      this.context = this.canvas.getContext("2d");
      this.width = parseInt(this.context.canvas.width, 10);
      this.height = parseInt(this.context.canvas.height, 10);
      if (options.data) {
        this.update(options);
      }
    }

    Waveform.prototype.setData = function(data) {
      return this.data = data;
    };

    Waveform.prototype.setDataInterpolated = function(data) {
      return this.setData(this.interpolateArray(data, this.width));
    };

    Waveform.prototype.setDataCropped = function(data) {
      return this.setData(this.expandArray(data, this.width));
    };

    Waveform.prototype.update = function(options) {
      if (options.interpolate != null) {
        this.interpolate = options.interpolate;
      }
      if (this.interpolate === false) {
        this.setDataCropped(options.data);
      } else {
        this.setDataInterpolated(options.data);
      }
      return this.redraw();
    };

    Waveform.prototype.bufferMeasure= function(position, length, data) {
      var sum = 0.0;
      for (var i = position; i <= (position + length) - 1; i++) {
        sum += Math.pow(data[i], 2);
      }
      return Math.sqrt(sum / data.length);
    };
    Waveform.prototype.drawbar = function(i,h){

      if (typeof this.innerColor === "function") {
        this.context.fillStyle = this.innerColor(i / this.width, h);
      }
      var w = this.bar_width;
      if (this.bar_gap !== 0) {
        w *= Math.abs(1 - this.bar_gap);
      }
      var x = i + (w / 2),
      y = this.height - h;


      this.context.clearRect(x, y, w, h);
      this.context.fillRect(x, y, w, h);
      /*var grd = this.context.createLinearGradient(0, 0, 0, h);
      grd.addColorStop(0, 'rgb(0, 130, 230)');
      grd.addColorStop(1, 'rgb(0, 20, 100)');

      this.context.fillStyle = grd;
      this.context.beginPath();
      this.context.rect(0, 0, x, h);
      this.context.clip();    
      this.context.fill();    
      this.context.restore();
*/

      if (x < this.width){
       // requestAnimationFrame(draw);
      } 
        
      };
      Waveform.prototype.redraw = function() {

        var d, i, middle, t, _i, _len, _ref, _results;
        this.clear();
        var sections = this.width;
        var buffer=this.data;
        var len = Math.floor(buffer.length / sections);
        var maxHeight = this.height;
        var vals = [];
        var _results=[];
        for (var i = 0; i < sections; i += this.bar_width) {
          vals.push(this.bufferMeasure(i * len, len, buffer) * 10000);
        }

        /*j modified*/
       /* var gradient = this.context.createLinearGradient(0, 0, 0, this.height);
        gradient.addColorStop(0.0, this.innerColor());
        gradient.addColorStop(1.0, this.innerColor());
        this.innerColor = gradient;*/
        /*j modified*/

        if (typeof this.innerColor === "function") {
          this.context.fillStyle = this.innerColor();
        } else {
          this.context.fillStyle = this.innerColor;
        }
        for (var j = 0; j < sections; j += this.bar_width) {
          var scale = maxHeight / vals.max();
          var val = this.bufferMeasure(j * len, len, buffer) * 10000;
          val *= scale;
          val += 1;
          this.drawbar(j, val);
          _results.push(val);
        }
        return _results;
      };

      Waveform.prototype.clear = function() {
        this.context.fillStyle = this.outerColor;
        this.context.clearRect(0, 0, this.width, this.height);
        return this.context.fillRect(0, 0, this.width, this.height);
      };

      Waveform.prototype.patchCanvasForIE = function(canvas) {
        var oldGetContext;
        if (typeof window.G_vmlCanvasManager !== "undefined") {
          canvas = window.G_vmlCanvasManager.initElement(canvas);
          oldGetContext = canvas.getContext;
          return canvas.getContext = function(a) {
            var ctx;
            ctx = oldGetContext.apply(canvas, arguments);
            canvas.getContext = oldGetContext;
            return ctx;
          };
        }
      };

      Waveform.prototype.createCanvas = function(container, width, height) {
        var canvas;
        canvas = document.createElement("canvas");
        container.appendChild(canvas);
        canvas.width = width;
        canvas.height = height;
        return canvas;
      };

      Waveform.prototype.expandArray = function(data, limit, defaultValue) {
        var i, newData, _i, _ref;
        if (defaultValue == null) {
          defaultValue = 0.0;
        }
        newData = [];
        if (data.length > limit) {
          newData = data.slice(data.length - limit, data.length);
        } else {
          for (i = _i = 0, _ref = limit - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
            newData[i] = data[i] || defaultValue;
          }
        }
        return newData;
      };

      Waveform.prototype.linearInterpolate = function(before, after, atPoint) {
        return before + (after - before) * atPoint;
      };

      Waveform.prototype.interpolateArray = function(data, fitCount) {
        var after, atPoint, before, i, newData, springFactor, tmp;
        newData = new Array();
        springFactor = new Number((data.length - 1) / (fitCount - 1));
        newData[0] = data[0];
        i = 1;
        while (i < fitCount - 1) {
          tmp = i * springFactor;
          before = new Number(Math.floor(tmp)).toFixed();
          after = new Number(Math.ceil(tmp)).toFixed();
          atPoint = tmp - before;
          newData[i] = this.linearInterpolate(data[before], data[after], atPoint);
          i++;
        }
        newData[fitCount - 1] = data[data.length - 1];
        return newData;
      };

      Waveform.prototype.optionsForSyncedStream = function(options) {
        var innerColorWasSet, that;
        if (options == null) {
          options = {};
        }
        innerColorWasSet = false;
        that = this;
        return {
          whileplaying: this.redraw,
          whileloading: function() {
            var stream;
            if (!innerColorWasSet) {
              stream = this;
              that.innerColor = function(x, y) {
                if (x < stream.position / stream.durationEstimate) {
                  return options.playedColor || "rgba(255, 102, 0, 0.8)";
                } else if (x < stream.bytesLoaded / stream.bytesTotal) {
                  return options.loadedColor || "rgba(0, 0, 0, 0.8)";
                } else {
                  return options.defaultColor || "rgba(0, 0, 0, 0.4)";
                }
              };
              innerColorWasSet = true;
            }
            return this.redraw;
          }
        };
      };

      Waveform.prototype.dataFromSoundCloudTrack = function(track) {
        var _this = this;
        return JSONP.get("http://www.waveformjs.org/w", {
          url: track.waveform_url
        }, function(data) {
          return _this.update({
            data: data
          });
        });
      };

      return Waveform;

    })();

    JSONP = (function() {
      var config, counter, encode, head, jsonp, key, load, query, setDefaults, window;
      load = function(url) {
        var done, head, script;
        script = document.createElement("script");
        done = false;
        script.src = url;
        script.async = true;
        script.onload = script.onreadystatechange = function() {
          if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
            done = true;
            script.onload = script.onreadystatechange = null;
            if (script && script.parentNode) {
              return script.parentNode.removeChild(script);
            }
          }
        };
        if (!head) {
          head = document.getElementsByTagName("head")[0];
        }
        return head.appendChild(script);
      };
      encode = function(str) {
        return encodeURIComponent(str);
      };
      jsonp = function(url, params, callback, callbackName) {
        var key, query, uniqueName;
        query = ((url || "").indexOf("?") === -1 ? "?" : "&");
        callbackName = callbackName || config["callbackName"] || "callback";
        uniqueName = callbackName + "_json" + (++counter);
        params = params || {};
        for (key in params) {
          if (params.hasOwnProperty(key)) {
            query += encode(key) + "=" + encode(params[key]) + "&";
          }
        }
        window[uniqueName] = function(data) {
          callback(data);
          try {
            delete window[uniqueName];
          } catch (_error) {}
          return window[uniqueName] = null;
        };
        load(url + query + callbackName + "=" + uniqueName);
        return uniqueName;
      };
      setDefaults = function(obj) {
        var config;
        return config = obj;
      };
      counter = 0;
      head = void 0;
      query = void 0;
      key = void 0;
      window = this;
      config = {};
      return {
        get: jsonp,
        init: setDefaults
      };
    })();

  }).call(this);
