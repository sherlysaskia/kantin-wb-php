<?php
include "fonte_config.php";

function kirimWA($nomor, $pesan) {
    global $fonte_token;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'target' => $nomor,
            'message' => $pesan
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: $fonte_token"
        ],
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
?>
