<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlCities.php';

// encontrar cidade
$city = MdlCities::findCity(44);

echo '<BR> Cidade: ' . $city->getCityName();

//inclui cidade
$cidadeSorriso = new City();
$cidadeSorriso->setCityName("Niteroi!");
MdlCities::insertCity($cidadeSorriso);

//list all cities
$list = MdlCities::listCities();

foreach($list as $city) {
    echo "<BR>" . $city->getCityId() . " - " . $city->getCityName();
}
