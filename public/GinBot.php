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
    $bot->reply("✨ GinBot\nSelamat Datang, Kak " . $user->getFirstName() . "!✨✨\n\n☛ List Perintah di GinBot:\n➤ /start Memulai percakapan dengan Bot.\n➤ /bantuan Menampilkan teks ini.\n➤ /menu Menampilkan menu.");
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
    $sub = "✨ $jenis[question] ✨\n\nYey Kak " . $user->getFirstName() . " telah memilih jenis $jenis[question],\nberikut list $jenis[question] yang tersedia:\n\n";
    $i = 1;
    foreach ($q as $d) {
        $sub .= $i . ". " . $d['sub1_question'] . "\n";
        $i++;
    }
    $sub .= "\nJawab dengan mengetik: 'Pilih [nomor menu] pada menu $number'\nContoh: Pilih 2 pada menu $number";
    $bot->reply($sub);
});

$ginbot->hears("Pilih ([0-9]+) pada menu ([0-9]+)", function (BotMan $bot, $number, $dish) {
    $db = new Database();
    $key = $dish . $number;
    $jenis = $db->getInstance()->pdo->query("SELECT * FROM sub_pertanyaan_1 WHERE sq1_id = '$key'")->fetch(\PDO::FETCH_ASSOC);
    $q = $db->select("sub_pertanyaan_2", "sq1_id", $key);
    $sub = "✨ $jenis[sub1_question] ✨\n\n";
    foreach ($q as $d) {
        $sub .=  " - " . $d['sub2_question'] . "\n";
    }
    $sub .= "\nJawab dengan mengetik:\n'Intip [pilihan] hidangan $jenis[sub1_question]'\nContoh: \nIntip Bahan-bahan $jenis[sub1_question]\nIntip Cara Pembuatan $jenis[sub1_question]";
    $bot->reply($sub);
});

$ginbot->hears("Intip (Bahan-bahan|Cara Pembuatan) (Siomay Ikan|Serabi|Martabak Telur|Pie|Pasta Macaroni Daging|Canape Crackers|Ayam Goreng Lengkuas|Nasi Goreng Cumi|Tumis Tahu Hijau|Es Blewah Serut|Pudding Bobba Thai Tea|Cake Stroberi Kelapa)", function (BotMan $bot, $dish, $hidangan) {
    $user = $bot->getUser();
    $db = new Database();
    $jenis = $db->getInstance()->pdo->query("SELECT * FROM sub_pertanyaan_1 WHERE sub1_question = '$hidangan'")->fetch(\PDO::FETCH_ASSOC);
    if ($dish == 'Bahan-bahan') {
        $q = $db->select("bahan", "sq1_id", $jenis['sq1_id']);
        $sub = "✨ $jenis[sub1_question] ✨\n\nBerikut berbagai macam bahan untuk membuat $jenis[sub1_question]:\n\n";
        $i = 1;
        foreach ($q as $d) {
            $sub .= $i . ". " . $d['list_bahan'] . "\n";
            $i++;
        }
        $sub .= "\nUntuk mengetahui cara pembuatannya, balas dengan mengetik 'Intip Cara Pembuatan $jenis[sub1_question]'";
        $bot->reply($sub);
    } else if ($dish == 'Cara Pembuatan') {
        $q = $db->select("pembuatan", "sq1_id", $jenis['sq1_id']);
        $sub = "✨ $jenis[sub1_question] ✨\n\nBerikut cara pembuatan $jenis[sub1_question]:\n\n";
        $i = 1;
        foreach ($q as $d) {
            $sub .= $i . ". " . $d['cara'] . "\n";
            $i++;
        }
        $sub = "\nUntuk mengetahui bahan untuk membuatnya, balas dengan mengetik 'Intip Bahan-bahan $jenis[sub1_question]'";
        $bot->reply($sub);
    }
    $bot->reply("Apakah info hidangan $jenis[sub1_question] dari Ginapi bot membantu Kak " . $user->getFirstName() . "?\na. Iya\nb. Tidak\n\nJawab dengan mengetik: 'Report [pilihan]'\nContoh: Report Iya");
});

$ginbot->fallback(function ($bot) {
    $user = $bot->getUser();
    $bot->reply("Maaf, Saya tidak mengerti maksud Kak " . $user->getFirstName() . ". \nMohon cek kembali atau coba perintah lain...");
});

$ginbot->listen();
