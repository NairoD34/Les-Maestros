<?php


$testPDO = new PDO('mysql:dbname=maestro;host=127.0.0.1', 'maestro', 'maestro');


$csv = '..\Ressources\cities.csv';


/**
 * Fonction permettant de parcourir le fichier .csv et mettre les valeurs dans un tableau pour traitement par la suite
 *
 * @param [type] $csv
 * @return array
 */
function read($csv)
{
    $file = fopen($csv, 'r');
    while (!feof($file)) {
        $line[] = fgetcsv($file, null, ';');
    }
    fclose($file);
    return $line;
}

//Assignation de la fonction
$csv = read($csv);

$regions = [];
$departments = [];
$villes = [];
$codePostaux = [];
$deptsValue = [];
$iMax = count($csv);

for ($i = 1; $i <= $iMax; $i++) {
    $string = $csv[$i][0];
    $values = explode(',', $string);

    $ville = $values[3];
    $departmentName = $values[6];
    $departmentValue = $values[7];
    $regionName = $values[8];
    $codePostalValue = $values[2];


    if (!in_array($regionName, $regions)) {
        $regions[] = $regionName;
        $stmt = $testPDO->prepare('SELECT id FROM region WHERE name = " ' . $regionName . '"');
        $stmt->execute();
        $existingRegion = $stmt->fetchColumn();

        if (!$existingRegion) {
            $stmt = $testPDO->prepare('INSERT INTO region (name) VALUES (" ' . $regionName . ' ")');
            $stmt->execute();

            $regionId = $testPDO->lastInsertId();
            $regions[$regionName] = $regionId;
        } else {
            $regions[$regionName] = $existingRegion;
        }
    }
    if (!in_array($departmentName, $departments)) {
        $regionId = $regions[$regionName];

        $stmt = $testPDO->prepare('SELECT id FROM county WHERE name = ? AND zip = ?');
        $stmt->execute([$departmentName, $departmentValue]);
        $existingDept = $stmt->fetchColumn();

        if (!$existingDept) {
            $stmt = $testPDO->prepare('INSERT INTO county (name, zip, region_id) VALUES ("' . $departmentName . '", "' . $departmentValue . '", "' . $regionId . '")');
            $stmt->execute();
            $deptId = $testPDO->lastInsertId();
            $departments[$departmentName] = $deptId;
        } else {

            $departments[$departmentName] = $existingDept;
            $departments[] = $departmentName;
        }
    }
    if (!in_array($ville, $villes)) {
        $deptId = $departments[$departmentName];
        $villes[] = $ville;
        $ville = trim($ville);
        $stmt = $testPDO->prepare('SELECT id FROM city WHERE name = ?');
        $stmt->execute([$ville]);
        $existingVille = $stmt->fetchColumn();

        if (!$existingVille) {
            $stmt = $testPDO->prepare('INSERT INTO city (county_id, name) VALUES (?, ?)');
            $stmt->execute([$deptId, $ville]);
            $villeId = $testPDO->lastInsertId();
            $villes[$ville] = $villeId;
        } else {
            $villes[$ville] = $existingVille;
        }
    }

    if (!array_key_exists($codePostalValue, $codePostaux)) {
        $stmt = $testPDO->prepare('INSERT INTO zipcode (title) VALUES (?)');
        $stmt->execute([$codePostalValue]);
        $codePostaux[$codePostalValue] = $testPDO->lastInsertId();
    }

    $villeId = $villes[$ville];
    $codePostalId = $codePostaux[$codePostalValue];

    $stmt = $testPDO->prepare('SELECT * FROM city_zipcode WHERE city_id = ? AND zipcode_id =?');
    $stmt->execute([$villeId, $codePostalId]);
    $existingRelation = $stmt->fetchColumn();
    if (!$existingRelation) {
        $stmt = $testPDO->prepare('INSERT INTO city_zipcode (city_id, zipcode_id) VALUES (?, ?)');
        $stmt->execute([$villeId, $codePostalId]);
    }
}
