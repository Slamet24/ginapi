<?php

require_once __DIR__.'/../vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use app\controllers\Api;

$configs = [
    "telegram" => [
        "token" => "1502889376:AAEJlFbg_oYSFdKJl0tMiYXAdIYZxw6FroY"
    ]
];

DriverManager::loadDriver(TelegramDriver::class);

$ginbot = BotManFactory::create($configs);

$ginbot->hears("/start",function(BotMan $bot){
    $user = $bot->getUser();
    $bot->reply("✨ GinBot\nSelamat Datang, ".$user->getFirstName()."!✨✨\n\n☛ List Perintah di GinBot:\n➤ /start Memulai percakapan dengan Bot.\n➤ /bantuan Menampilkan teks ini.\n➤ /menu Menampilkan menu.");
});

$ginbot->hears("/bantuan",function(BotMan $bot){
    $bot->reply("✨ GinBot\n\n☛ List Perintah di GinBot:\n➤ /start Memulai percakapan dengan Bot.\n➤ /bantuan Menampilkan teks ini.\n➤ /menu Menampilkan menu.");
});

$ginbot->hears("/menu",function(BotMan $bot){
    $data = json_decode(Api::getMenu(),true);
    $menu = "✨ Menu ✨\n\n";
    foreach($data as $d) {
        $menu .= $d['q_id'].". ".$d['question']."\n";
    }
    $menu .= "\nJawab dengan mengetik:\n'Pilih menu [nomor menu]'\nContoh, 'Pilih menu 1'.";
    $bot->reply($menu);
});

$ginbot->hears("Pilih menu ([0-9]+)",function(BotMan $bot,$number){
    $data = json_decode(Api::setMenu($number),true);
    $menu = "✨ Menu ✨\n\n";
    $i = 1;
    foreach($data as $d) {
        $menu .= $i.". ".$d['sub1_question']."\n";
        $i++;
    }
    $menu .= "\nJawab dengan mengetik:\n'Pilih sub1 [nomor menu]'\nContoh, 'Pilih sub1 1'.";
    $bot->reply($menu);
});

$ginbot->hears("Pilih sub1 ([0-9]+)",function(BotMan $bot,$number){
    $data = json_decode(Api::setSub1($Menu.$number),true);
    $menu = "✨ Menu ✨\n\n";
    $i = 1;
    foreach($data as $d) {
        $menu .= $i.". ".$d['sub2_question']."\n";
        $i++;
    }
    $menu .= "\nJawab dengan mengetik:\n'Pilih sub2 [nomor menu]'\nContoh, 'Pilih sub2 1'.";
    $bot->reply($menu);
});

$ginbot->hears("Pilih sub2 ([0-9]+)",function(BotMan $bot,$number){
    $data = json_decode(Api::setSub2($number),true);
    $menu = "✨ Menu ✨\n\n";
    $i = 1;
    foreach($data as $d) {
        $menu .= $i.". ".$d['sub3_question']."\n";
        $i++;
    }
    $menu .= "\nJawab dengan mengetik:\n'Pilih sub3 [nomor menu]'\nContoh, 'Pilih sub3 1'.";
    $bot->reply($menu);
});

$ginbot->listen();