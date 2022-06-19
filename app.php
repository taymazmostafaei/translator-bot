<?php

require_once 'vendor/autoload.php';


use ShadPHP\ShadPHP;
use Stichoza\GoogleTranslate\GoogleTranslate;

//use Morilog\Jalali\Jalalian;
$users = [];


$account = new ShadPHP(989394097242); // Only without zero and with area code 98

$account->onUpdate(function (array $update) use ($account) {
    global $users;
    if (isset($update['data_enc'])) {
        $message = $update['data_enc'];
        foreach ($message['message_updates'] as $value) {
            $messageContent = $value['message'];
            $type = $messageContent['type'];
            $author_type = $messageContent['author_type'];
            $author_object_guid = $messageContent['author_object_guid'];
            if ($author_type == 'User' && $type == 'Text') {
                $isRegisterd = 0;
                $UserInfo = $account->getUserInfo($author_object_guid);
                $first_name = $UserInfo['data']['user']['first_name'];
                $text = (string)$messageContent['text'];

                foreach ($users as $user) {
                    if ($user == $author_object_guid) {
                        if (preg_match('/^[a-z0-9 .}, !@#$%^&*()_+|\';?><-]+$/i',$text)) {
                            $text = GoogleTranslate::trans($text, 'fa', 'en');
                        }else{
                            $text = GoogleTranslate::trans($text, 'en', 'fa');
                        }
                        $isRegisterd = 1;
                        $account->sendMessage($author_object_guid, $text);
                    }
                }
                if ($isRegisterd == 0) {
                    $users[] = $author_object_guid;
                    $account->sendMessage($author_object_guid, "سلام $first_name عزیز به شادفیت خوش آمدی");
                    $account->sendMessage($author_object_guid, 'از سرویس رایگان ناینس لذت ببرید');
                }
            }
        }
    }
});
