;(function() {

    function TrackerBot(key, dev) {
        dev = dev || false
        var self = this
        self.key = key
        var url = 'https://api.trackerbot.com/track'
        if(dev) url = 'https://tracker.ngrok.io/track'

        self.track = function (type, event, params) {
            var xhttp = new XMLHttpRequest()
            xhttp.open('POST', url, true)
            xhttp.setRequestHeader('Content-type', 'application/json')
            console.log(JSON.stringify({
                api_key: self.key,
                type: type,
                event: event,
                data: params
            }))
            xhttp.send(JSON.stringify({
                api_key: self.key,
                type: type,
                event: event,
                data: params
            }))
        }

    }

    TrackerBot.prototype.init = function(key) {
		this._init(key)
	}

    TrackerBot.prototype.track = function(type, event, params) {
		this._notify(type, event, params)
	}

	this.TrackerBot = TrackerBot
}).call(this);
