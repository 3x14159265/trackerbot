'use strict';

angular.module('tracker', [
    'ngRoute'
]).
config([
    '$locationProvider',
    '$routeProvider',
    function($locationProvider, $routeProvider) {
        $routeProvider.when('/apps', {
            templateUrl: '/views/apps.html',
            controller: 'AppsCtrl'
        }).when('/integrations/:app_id', {
            templateUrl: '/views/integrations.html',
            controller: 'IntegrationsCtrl'
        });

        $routeProvider.otherwise({
            redirectTo: '/apps'
        });
    }
]).run(function($rootScope, $location) {
    // register listener to watch route changes
    $rootScope.$on("$routeChangeStart", function(event, next, current) {});
}).controller('AppsCtrl', function($scope, $http) {

    init()

    function init() {
        $scope.app = new Object()
        load()
    }

    $scope.createApp = function() {
        swal({
            title: "Create a new app",
            text: 'Please enter a name for the new app.',
            type: "input",
            html: true,
            showCancelButton: true,
            closeOnConfirm: true,
            inputPlaceholder: "Enter a name"
        }, function(inputValue) {
            if (inputValue === false || inputValue === "") {
                swal.showInputError("Please enter a name");
                return false
            }
            postApp(inputValue)
        });
    }

    function load() {
        $scope.loading = true
        $http.get('/apps/all').then(function(res) {
            $scope.apps = res.data
        }).finally(function() {
            $scope.loading = false
        })
    }

    function postApp(name) {
        $scope.loading = true
        $http.post('/app', {
                name: name
            })
            .then(function(res) {
                if (res.data && res.data.length) {
                    TrackerBot.track('event', 'app_create')
                }
                $scope.app = new Object()
            })
            .catch(function() {
                swal("Oops...", "Something went wrong!", "error");
            })
            .finally(function() {
                load()
            })
    }
}).controller('IntegrationsCtrl', function($scope, $http, $routeParams) {

    init()

    function init() {
        $scope.connectTelegram = connectTelegram

        load($routeParams.app_id)
    }

    function load(app_id) {
        $scope.loading = true
        $http.get('/integrations/' + app_id).then(function(res) {
            $scope.app = res.data
        }).catch(function() {
            swal("Oops...", "Something went wrong!", "error")
        }).finally(function() {
            $scope.loading = false
        })
    }

    function connectTelegram() {
        swal({
            title: "Connect Telegram Chat",
            text: 'Add <a href="https://telegram.me/TrackerTestBot">@TrackerBot</a> to an existing group or just open a conversation with it. Type <strong>/chat</strong> to retrieve your chat id.',
            type: "input",
            imageUrl: "/img/telegram.png",
            html: true,
            showCancelButton: true,
            closeOnConfirm: true,
            inputPlaceholder: "Insert Telegram chat id"
        }, function(inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("Please insert chat id");
                return false
            }
            $scope.loading = true
            postTelegramConnect(inputValue)
        });

    }

    function postTelegramConnect(chatId) {
        $http.post('/telegram/connect', {
                app_id: $scope.app.id,
                chat_id: chatId
            })
            .then(function(res) {
                if (res.data && res.data.length) {
                    $scope.app.chats = $scope.app.chats.concat(res.data)
                    TrackerBot.track('event', 'chat_connect', {
                        platform: 'telegram'
                    })
                }
            })
            .catch(function() {
                swal("Oops...", "Something went wrong!", "error")
            })
            .finally(function() {
                $scope.loading = false
            })
    }
})
