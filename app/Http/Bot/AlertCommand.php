<?php

namespace App\Http\Bot;

use App\Networks\NetworkFactory;
use App\Alert;
use App\Chat;

class AlertCommand extends Command
{
    public function execute($network, $chatId, $cmd, $params = '')
    {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        try {
            if (empty($params)) {
                $alerts = Alert::findByAppId($chat->app_id);
                if (count($alerts)) {
                    $text = '';
                    foreach ($alerts as $a) {
                        $text .= '<b>ID</b>: '.$a->id.', <b>Alert</b>: '.$a->name.' in '.$this->getDiff($a).PHP_EOL;
                    }
                } else {
                    $text = 'You currently don\'t have any active alerts.'.PHP_EOL;
                }
                $text .= PHP_EOL;
                $text .= 'To add a new alert, pass time and title to the command, e.g.'.PHP_EOL;
                $text .= '/alert in 5 minutes - This is an alert'.PHP_EOL;
                $text .= "To remove an alert, pass id and 'delete' to the command, e.g.".PHP_EOL;
                $text .= '/alert 314 delete'.PHP_EOL;
                $text .= 'Type \'/alert help\' for more options';

                return $handler->sendText($chatId, $text);
            } elseif (strpos($params, 'delete') !== false && strpos($params, '-') === false) {
                $params = explode(' ', $params, 2);
                if ($params[1] != 'delete') throw new \Exception('Unknown command format.');
                $id = trim($params[0]);
                $alert = Alert::findByIdAndAppId($id, $chat->app_id);
                if ($alert) {
                    $text = $alert->name;
                    $alert->delete();
                    return $handler->sendText($chatId, "👌 I have deleted this alert for you: $text");
                } else {
                    return $handler->sendText($chatId, "😯 Sorry, but I couldn't find an alert with this id...");
                }
            } else {
                if (strpos($params, '-') !== false) {
                    $split = explode('-', $params, 2);
                    $title = trim($split[1]);
                    $params = $split[0];
                } else {
                    $title = 'Alert';
                }

                $params = trim($params);
                if (starts_with($params, 'in')) {
                    $params = substr_replace($params, '+', 0, 2);
                }
                $params = str_replace('and ', ',', $params);

                $time = (new \DateTime($params));
                $time->setTime($time->format('H'), $time->format('i'), 0);
                $alert = new Alert();
                $alert->app_id = $chat->app_id;
                $alert->name = $title;
                $alert->fire_at = $time;
                $alert->save();

                $text = '☝ Great. I\'ll remind you in '.$this->getDiff($alert).' about '.$alert->name;
                $text .= '. Your id is '.$alert->id.PHP_EOL;
                $text .= 'If you want me to remove this alert, just send \'/alert '.$alert->id.' delete\'';
                // $text .= '<b>ID</b>: '.$alert->id.', <b>Alert</b>: '.$alert->name.' in '.$this->getDiff($alert).PHP_EOL;

                return $handler->sendText($chatId, $text);
            }
        } catch (\Exception $e) {
            $text = 'Time can not be parsed. Please use following format:'.PHP_EOL;
            $text .= $this->getHelpOptions();
            return $handler->sendText($chatId, $text);
        }
    }

    public function help($network, $chatId) {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        $text = 'Hey '.$chat->name.'!'.PHP_EOL;
        $text .= 'You can add alerts / reminders with this command. ';
        $text .= 'Here are some options how to do that:'.PHP_EOL.PHP_EOL;
        $text .= $this->getHelpOptions();

        return $handler->sendText($chatId, $text);
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute.
     */
    public function schedule()
    {
        $alerts = Alert::findUpcoming();
        foreach ($alerts as $alert) {
            $text = $alert->name;
            $this->sendToChats($alert->app_id, "⏰ $text");
        }
    }

    private static function getDiff($alert)
    {
        $now = new \DateTime();
        $interval = $alert->fire_at->diff($now);
        $days = $interval->format('%d');
        $hours = $interval->format('%h');
        $min = $interval->format('%i');
        $in = "$min minutes";
        if ($hours > 0) {
            $in = "$hours hours, ".$in;
        }
        if ($days > 0) {
            $in = "$days days, ".$in;
        }

        return $in;
    }

    private function getHelpOptions()
    {
        $text = '/alert in 3 minutes'.PHP_EOL;
        $text .= '/alert in 3 days and 1 hour'.PHP_EOL;
        $text .= '/alert +2 hours'.PHP_EOL;
        $text .= '/alert tomorrow 3:14 PM'.PHP_EOL;
        $text .= '/alert Monday next week 3:14 PM'.PHP_EOL;
        $text .= '/alert Friday 3 PM - Here you can add a title after the \'-\' sign'.PHP_EOL;

        return $text;
    }
}
