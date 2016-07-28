@extends('app')
@section('title') Dashboard :: @parent @endsection
@section('content')
<div class="row">
    <div class="page-header">
        <h2>Dashboard</h2>
        <a href="https://slack.com/oauth/authorize?scope=incoming-webhook,commands,bot&client_id=24628397702.64258146496"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x" /></a>
    </div>
</div>

@endsection
