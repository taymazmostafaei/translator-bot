<?php

require_once 'vendor/autoload.php';


use ShadPHP\ShadPHP;
use Stichoza\GoogleTranslate\GoogleTranslate;

//use Morilog\Jalali\Jalalian;


$account = new ShadPHP(989147318832); // Only without zero and with area code 98

$account->onUpdate(function (array $update) use ($account) {
    if (isset($update['data_enc'])) {
        $message = $update['data_enc'];
        foreach ($message['message_updates'] as $value) {
            $messageContent = $value['message'];
            $type = $messageContent['type'];
            $author_type = $messageContent['author_type'];
            $author_object_guid = $messageContent['author_object_guid'];
            if ($author_type == 'User' && $type == 'Text') {
                $UserInfo = $account->getUserInfo($author_object_guid);
                $first_name = $UserInfo['data']['user']['first_name'];
                $text = (string)$messageContent['text']; //. ' ' . $name['data']['user']['first_name'];

                if (preg_match('/^[a-z0-9 .}, !@#$%^&*()_+|\';?><-]+$/i',$text)) {
                    $text = GoogleTranslate::trans($text, 'fa', 'en');
                }else{
                    $text = GoogleTranslate::trans($text, 'en', 'fa');
                }
                
                $account->sendMessage($author_object_guid, $text);
                var_dump($author_object_guid);
            }
        }
    }
});
