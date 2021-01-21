<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use app\controllers\Api;
use app\core\Database;

$configs = [
    "telegram" => [
        // "token" => "1502889376:AAEJlFbg_oYSFdKJl0tMiYXAdIYZxw6FroY"
        "token" => "1551688607:AAGbnccYV5bshv6mXMDZ9Xc6dXo6PCJb34w"
    ]
];

DriverManager::loadDriver(TelegramDriver::class);

$ginbot = BotManFactory::create($configs);

$ginbot->hears("/start", function (BotMan $bot) {
    $user = $bot->getUser();
    $bot->reply("✨ GinBot\nSelamat Datang, " . $user->getFirstName() . "!✨✨\n\n☛ List Perintah di GinBot:\n➤ /start Memulai percakapan dengan Bot.\n➤ /bantuan Menampilkan teks ini.\n➤ /menu Menampilkan menu.");
});

$ginbot->hears("/bantuan", function (BotMan $bot) {
    $bot->reply("✨ GinBot\n\n☛ List Perintah di GinBot:\n➤ /start Memulai percakapan dengan Bot.\n➤ /bantuan Menampilkan teks ini.\n➤ /menu Menampilkan menu.");
});

$ginbot->hears("/menu", function (BotMan $bot) {
    $db = new Database();
    $q = $db->selectAll("pertanyaan");
    $menu = "✨ Menu ✨\n\nTerimakasih telah memilih Ginapi bot, berikut jenis makanan yang tersedia:\n\n";
    foreach ($q as $d) {
        print_r($d);
        $menu .= $d['q_id'] . ". " . $d['question'] . "\n";
    }
    $menu .= "\nJawab dengan mengetik: 'Pilih menu [nomor menu]'\nContoh: Pilih menu 1";
    $bot->reply($menu);
});

$ginbot->hears("Pilih menu ([0-9]+)", function (BotMan $bot, $number) {
    $user = $bot->getUser();
    $db = new Database();
    $jenis = $db->getInstance()->pdo->query("SELECT * FROM pertanyaan WHERE q_id = '$number'")->fetch(\PDO::FETCH_ASSOC);
    $q = $db->select("sub_pertanyaan_1", "q_id", $number);
    $sub = "✨ $jenis[question] ✨\n\nYey " . $user->getFirstName() . " telah memilih jenis $jenis[question],\nberikut list $jenis[question] yang tersedia:\n\n";
    $i = 1;
    foreach ($q as $d) {
        $sub .= $i . ". " . $d['sub1_question'] . "\n";
        $i++;
    }
    $sub .= "\nJawab dengan mengetik:\n'Pilih [nomor menu] pada menu 1'\nContoh, 'Pilih 2 pada menu 1'.";
    $bot->reply($sub);
});

$ginbot->hears("Pilih ([0-9]+) pada menu (1|2|3)", function (BotMan $bot, $number, $dish) {
    $db = new Database();
    $key = $dish . $number;
    $q = $db->select("sub_pertanyaan_2", "sq1_id", $key);
    $sub = "✨ Isi Sub $number Pada Menu $dish ✨\n\n";
    $i = 1;
    foreach ($q as $d) {
        $sub .= $i . ". " . $d['sub2_question'] . "\n";
        $i++;
    }
    $sub .= "\nJawab dengan mengetik:\n'Pilih [nomor menu] pada menu 1'\nContoh, 'Pilih 2 pada menu 1'.";
    $bot->reply($sub);
});

// $ginbot->hears("Pilih menu ([0-9]+)", function (BotMan $bot, $number) {
//     $data = json_decode(Api::setMenu($number), true);
//     $menu = "✨ Menu ✨\n\n";
//     $i = 1;
//     foreach ($data as $d) {
//         $menu .= $i . ". " . $d['sub1_question'] . "\n";
//         $i++;
//     }
//     $menu .= "\nJawab dengan mengetik:\n'Pilih sub1 [nomor menu]'\nContoh, 'Pilih sub1 1'";
//     $bot->reply($menu);
// });

// $ginbot->hears("Pilih sub1 ([0-9]+)", function (BotMan $bot, $number) {
//     $data = json_decode(Api::setSub1($bot . $number), true);
//     $menu = "✨ Menu ✨\n\n";
//     $i = 1;
//     foreach ($data as $d) {
//         $menu .= $i . ". " . $d['sub2_question'] . "\n";
//         $i++;
//     }
//     $menu .= "\nJawab dengan mengetik:\n'Pilih sub2 [nomor menu]'\nContoh, 'Pilih sub2 1'.";
//     $bot->reply($menu);
// });

// $ginbot->hears("Pilih sub2 ([0-9]+)", function (BotMan $bot, $number) {
//     $data = json_decode(Api::setSub2($number), true);
//     $menu = "✨ Menu ✨\n\n";
//     $i = 1;
//     foreach ($data as $d) {
//         $menu .= $i . ". " . $d['sub3_question'] . "\n";
//         $i++;
//     }
//     $menu .= "\nJawab dengan mengetik:\n'Pilih sub3 [nomor menu]'\nContoh, 'Pilih sub3 1'.";
//     $bot->reply($menu);
// });

$ginbot->fallback(function ($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});

$ginbot->listen();
