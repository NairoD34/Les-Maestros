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
        $stmt = $testPDO->prepare('SELECT id FROM region WHERE nom = " ' . $regionName . '"');
        $stmt->execute();
        $existingRegion = $stmt->fetchColumn();

        if (!$existingRegion) {
            $stmt = $testPDO->prepare('INSERT INTO region (nom) VALUES (" ' . $regionName . ' ")');
            $stmt->execute();

            $regionId = $testPDO->lastInsertId();
            $regions[$regionName] = $regionId;
        } else {
            $regions[$regionName] = $existingRegion;
        }
    }
    if (!in_array($departmentName, $departments)) {
        $regionId = $regions[$regionName];

        $stmt = $testPDO->prepare('SELECT id FROM departement WHERE nom = ? AND numero_departement = ?');
        $stmt->execute([$departmentName, $departmentValue]);
        $existingDept = $stmt->fetchColumn();

        if (!$existingDept) {
            $stmt = $testPDO->prepare('INSERT INTO departement (nom, numero_departement, region_id) VALUES ("' . $departmentName . '", "' . $departmentValue . '", "' . $regionId . '")');
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
        $stmt = $testPDO->prepare('SELECT id FROM ville WHERE nom = ?');
        $stmt->execute([$ville]);
        $existingVille = $stmt->fetchColumn();

        if (!$existingVille) {
            $stmt = $testPDO->prepare('INSERT INTO ville (departement_id, nom) VALUES (?, ?)');
            $stmt->execute([$deptId, $ville]);
            $villeId = $testPDO->lastInsertId();
            $villes[$ville] = $villeId;
        } else {
            $villes[$ville] = $existingVille;
        }
    }

    if (!array_key_exists($codePostalValue, $codePostaux)) {
        $stmt = $testPDO->prepare('INSERT INTO code_postal (libelle) VALUES (?)');
        $stmt->execute([$codePostalValue]);
        $codePostaux[$codePostalValue] = $testPDO->lastInsertId();
    }

    $villeId = $villes[$ville];
    $codePostalId = $codePostaux[$codePostalValue];

    $stmt = $testPDO->prepare('SELECT * FROM ville_code_postal WHERE ville_id = ? AND code_postal_id =?');
    $stmt->execute([$villeId, $codePostalId]);
    $existingRelation = $stmt->fetchColumn();
    if (!$existingRelation) {
        $stmt = $testPDO->prepare('INSERT INTO ville_code_postal (ville_id, code_postal_id) VALUES (?, ?)');
        $stmt->execute([$villeId, $codePostalId]);
    }
}
