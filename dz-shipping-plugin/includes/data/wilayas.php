<?php
// wilayas.php
function dz_get_wilayas_data() {
    return array(
        '01' => 'Adrar - أدرار',
        '02' => 'Chlef - الشلف',
        '03' => 'Laghouat - الأغواط',
        // ... all 58 wilayas
        '58' => 'In Guezzam - عين قزام'
    );
}

// communes.php
function dz_get_communes_data() {
    return array(
        '01' => array(
            '01001' => 'Adrar - أدرار',
            '01002' => 'Tamest - تامست',
            // ... all communes for Adrar
        ),
        // ... all wilayas with communes
    );
}

function dz_get_communes_by_wilaya($wilaya_code) {
    $communes_data = dz_get_communes_data();
    return isset($communes_data[$wilaya_code]) ? $communes_data[$wilaya_code] : array();
}