;(function() {

    function TrackerBot(key, dev) {
        dev = dev || false
        var self = this
        self.key = key
        var url = 'https://messengersapi.com/js'
        if(dev) url = 'https://tracker.ngrok.io/js'

        self._domain = function (domain, filter) {
            filter = filter || []
            post(url+'/domain/'+self.key, {
                domain: domain,
                filter: filter
            })
        }

        self._email = function (email) {
            post(url+'/email/'+self.key, {
                email: email
            })
        }

        self._track = function (type, event, params) {
            post(url+'/track/'+self.key, {
                type: type,
                event: event,
                data: params
            })
        }

        function post(url, data) {
            var xhttp = new XMLHttpRequest()
            xhttp.open('POST', url, true)
            xhttp.setRequestHeader('Content-type', 'application/json')
            xhttp.send(JSON.stringify({data: data}))
        }

    }

    TrackerBot.prototype.init = function(key) {
		this._init(key)
	}

    TrackerBot.prototype.domain = function(domain, filter) {
		this._domain(domain, filter)
	}

    TrackerBot.prototype.email = function(email) {
		this._email(email)
	}

    TrackerBot.prototype.track = function(type, event, params) {
		this._track(type, event, params)
	}

	this.TrackerBot = TrackerBot
}).call(this);
