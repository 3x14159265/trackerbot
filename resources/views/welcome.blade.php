<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TrackerBot</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300" rel="stylesheet" type="text/css">
        <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        {{-- <link rel="icon" type="image/png" href="/img/kronos.png"> --}}

        <!-- Styles -->
        <style>

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-right links">
            </div>

            <div class="content">
                <div class="title m-b-md">
                    TrackerBot
                </div>
                <div class="title m-b-md">
                    <a href="https://telegram.me/Klio_Bot" class="btn btn-info btn-tg network">
                        <i class="fa fa-paper-plane"></i>
                        Add to
                        <strong>Telegram</strong>
                    </a>

                    <!-- <a href="https://slack.com/oauth/authorize?scope=incoming-webhook,commands,bot&client_id=24628397702.64258146496&state=state" class="network">
                        <img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x"/>
                    </a> -->

                </div>
            </div>
        </div>

    <script type="text/javascript" src="/js/trackerbot.js"></script>
    <script type="text/javascript">
        window.TrackerBot = new TrackerBot('tg_41478911', true);
        // TrackerBot.track('rt_event', 'eventname', {'some': 'data'})
        TrackerBot.track('rt_event', 'eventname', {'what': 'ever', 'some': {'more': 'data', 'and': {'even': 'more'}}})
        TrackerBot.track('rt_error', 'eventname', {'what': 'ever', 'some': {'more': 'data', 'and': {'even': 'more'}}})
        TrackerBot.domain('heycookie.co.za', ['woocommerce', 'shop'])
    </script>
    </body>
</html>
