<?php

include 'Telegram.php';

$telegram = new Telegram('5722902670:AAGpclq4o3KYV_i9febd5574QnqdkLA5p6k');

$data = $telegram->getData();
$message = $data['message'];

$text = $message['text'];
$text = $message['chat']['id'];

$chat_id = $telegram->ChatID();
$text = $telegram->Text();


$orderTypes = ["1kg - 50 000 so'm"  , "1.5kg(1L) - 75 000 so'm", "4.5kg(3L) - 220 000 so'm", "7.5kg(5L) - 370 000 so'm"];

switch ($text) {
    case '/start':
        showStart();
        break;
    case '👨💻 Bmw haqida':
        showAbout();
        break;
    case '🚶Buyurtma berish':
        showOrder();
        break;
    case "✈️Yetkazib berish ✈":
        showLocation();
        break;
    case "Location jo'nata olmayamman" :
        showLocationn();
        break;
    default:
        if (in_array($text, $orderTypes)) {
            file_put_contents('users/massa.txt', $text);
            askContact();
        } else {
            switch (file_get_contents('users/step.txt')){
                case "phone":
                    if($message['contact']['phone_number'] != "") {
                        file_put_contents('users/phone.txt', $message['contact']['phone_number']);
                    } else {
                        file_put_contents('users/phone.txt', $text);
                    }
//                    $telegram->sendMessage([
//                        "chat_id" => $telegram->ChatID(),
//                        "text" => $message['contact']['phone_number'],
//                    ]);

                    showDeliveryType();
                    break;

            }
        }
        break;

}

function showStart()
{
    global $telegram, $chat_id;

    $option = array(
        //First row
        array($telegram->buildKeyboardButton("👨💻 Bmw haqida")),
        //Second row
        array($telegram->buildKeyboardButton("🚶Buyurtma berish")));

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Salom, botimizga xush kelibsiz!');
    $telegram->sendMessage($content);

}

function showAbout()
{
    global $telegram, $chat_id;

    $content = array('chat_id' => $chat_id, 'text' => "Bmw haqida koproq ma'lumot:<a href='https://telegra.ph/Biz-haqimizda-09-01'>Ma'lumot</a>", "parse_mode" => "html");
    $telegram->sendMessage($content);
}

function showOrder()
{
    global $telegram, $chat_id;

    $option = array(
        array($telegram->buildKeyboardButton("1kg - 50 000 so'm")),
        array($telegram->buildKeyboardButton("1.5kg(1L) - 75 000 so'm")),
        array($telegram->buildKeyboardButton("4.5kg(3L) - 220 000 so'm")),
        array($telegram->buildKeyboardButton("7.5kg(5L) - 370 000 so'm")),
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Sizing buyurtmangiz qabul qilindi", "parse_mode" => "html");
    $telegram->sendMessage($content);
}

function askContact(){
    global $telegram, $chat_id ;

    file_put_contents('users/step.txt', 'phone');

    $option = array(
        array($telegram->buildKeyboardButton("Raqamni jo'natish",  $request_contact = true)),
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Hajim tanlandi, endi telefon raqamingnizni kiritsangiz", "parse_mode" => "html");
    $telegram->sendMessage($content);
}


function showDeliveryType() {
    global $telegram, $chat_id ;

    $option = array(
        array($telegram->buildKeyboardButton("✈️Yetkazib berish ✈")),
        array($telegram->buildKeyboardButton("Borib olish")),
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Bizda Farg'ona shahri bo'ylab yetkazib berish xizmati mavjud.", "parse_mode" => "html");
    $telegram->sendMessage($content);
}

function showLocation(){

    global $telegram, $chat_id ;
    file_put_contents('users/step.txt', 'Location');
    $option = array(
        array($telegram->buildKeyboardButton("Location jo'natish", $request_location = true)),
        array($telegram->buildKeyboardButton("Location jo'nata olmayamman"))
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Bizda Farg'ona shahri bo'ylab yetkazib berish xizmati mavjud.", "parse_mode" => "html");
    $telegram->sendMessage($content);

}


function showLocationn() {
    global $telegram, $chat_id ;
    $option = array(
        array($telegram->buildKeyboardButton("Boshqatan buyurtma berish bosing")),
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize = true, $selective = true);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Sizning buyutmangiz qabul qilindi. Tez orada siz bilan bo'lanamiz", "parse_mode" => "html");
    $telegram->sendMessage($content);
}