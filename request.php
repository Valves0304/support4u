<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/Models/MdlRequests.php';

// encontrar request
// encontrar request
$request = MdlRequests::findRequest(3);

echo '<BR> Request: ' . $request->getRequestId();
echo '<BR>    -> possui ' . count($request->getRequestItems()) . ' itens';

foreach ($request->getRequestItems() as $requestItem) {
    echo '<BR> - ' . $requestItem->getQuantity() . ' ' . $requestItem->getItem();
    echo '<BR> - Phone '  . $requestItem->getPhone();
}

// soma uma quantidade no primeiro item
$request->getRequestItems()[0]->setQuantity($request->getRequestItems()[0]->getQuantity() + 1);

echo '<BR> apos incremento: ';
foreach ($request->getRequestItems() as $requestItem) {
    echo '<BR> - ' . $requestItem->getQuantity() . ' ' . $requestItem->getItem();
}

// atualiza no banco
MdlRequests::updateRequest($request);

//inclui cidade
//$cidadeSorriso = new City();
//$cidadeSorriso->setCityName("Niteroi!");
//MdlCities::insertCity($cidadeSorriso);

//list all cities
//$list = MdlCities::listCities();

//foreach($list as $city) {
//    echo "<BR>" . $city->getCityId() . " - " . $city->getCityName();
//}
