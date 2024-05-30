<?php

use cli\Cli;

include_once 'Cli.php';
include_once 'templates.php';


$io = new Cli();
$result = $io->askChoice("Veuillez choisir entre :", ['controller', 'entity']);
switch ($result) {
    case 'controller':
        $io->clearScreen();
        createController($io);
        break;
    case 'entity':
        $io->clearScreen();
        createEntity($io);
        break;
    default:
        $io->writeError("Choix invalide\n");
        break;
}

function createEntity(Cli $io): void
{
    $io->write("Veuillez entrer le nom de l'entité à créer: ");
    $entityName = $io->ask("");
    $entityName = ucfirst($entityName);
    $properties = [];
    $getters = [];
    $setters = [];
    $template = ENTITY_TEMPLATE;
    $template = str_replace('{entity}', $entityName, $template);
    while (true) {
        if (!empty($properties)) {
            $io->clearScreen();
            $io->write("Propriétés de l'entité " . $entityName . "\n");
            foreach ($properties as $property) {
                $io->writeln($property['name'] . " : " . $property['type']);
            }
        }
        $property = $io->ask("Veuillez entrer une propriété de votre entité " . $entityName . " (exit pour terminer): ");
        $property = trim($property);
        if ($property === 'exit') {
            break;
        }
        while (!$io->checkInput($property)) {
            $property = $io->ask("Veuillez entrer une propriété (exit pour terminer): ");
            $property = trim($property);
            if ($property === 'exit') {
                break 2;
            }
        }
        $type = $io->askChoice("Veuillez entrer le type de la propriété: ", ['int', 'string', 'float', 'bool', 'array', 'null']);
        while (!$io->checkEntryType($type)) {
            $io->writeError("Type invalide\n");
            $type = $io->ask("Veuillez entrer le type de la propriété: ");
        }
        $propertiesTemplate = PROPERTY_TEMPLATE;
        $propertiesTemplate = str_replace('{type}',  $type, $propertiesTemplate);
        $propertiesTemplate = str_replace('{name}', $property, $propertiesTemplate);
        $propertiesTemplate = str_replace('@', '$', $propertiesTemplate);
        $properties[] = [
            'name' => $property,
            'type' => $type,
            'template' => $propertiesTemplate
        ];
        $getterTemplate = GETTER_TEMPLATE;
        $getterTemplate = str_replace('{type}', $type, $getterTemplate);
        $getterTemplate = str_replace('{name}', $property, $getterTemplate);
        $getterTemplate = str_replace('{ucName}', ucfirst($property), $getterTemplate);
        $getterTemplate = str_replace('@', '$', $getterTemplate);
        $getters[] = [
            'name' => $property,
            'type' => $type,
            'template' => $getterTemplate
        ];
        $setterTemplate = SETTER_TEMPLATE;
        $setterTemplate = str_replace('{type}', $type, $setterTemplate);
        $setterTemplate = str_replace('{name}', $property, $setterTemplate);
        $setterTemplate = str_replace('{ucName}', ucfirst($property), $setterTemplate);
        $setterTemplate = str_replace('@', '$', $setterTemplate);
        $setters[] = [
            'name' => $property,
            'type' => $type,
            'template' => $setterTemplate
        ];
    }


    $properties = array_map(function ($property) {
        return $property['template'];
    }, $properties);

    $getters = array_map(function ($getter) {
        return $getter['template'];
    }, $getters);

    $setters = array_map(function ($setter) {
        return $setter['template'];
    }, $setters);

    $template = str_replace('{properties}', implode("\n", $properties), $template);
    $template = str_replace('{getters}', implode("\n", $getters), $template);
    $template = str_replace('{setters}', implode("\n", $setters), $template);

    fwrite(fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Entity" . DIRECTORY_SEPARATOR . $entityName . ".php", "w"), $template);
    $io->write($entityName . " créé avec succès\n");
    createRepository($io, $entityName);
}


function createController(Cli $io): void
{
    $io->write("Veuillez entrer le nom du controller à créer: ");
    $controllerName = $io->ask("");
    $controllerName = ucfirst($controllerName);
    $template = CONTROLLER_TEMPLATE;
    $template = str_replace('@', '$', $template);
    $template = str_replace('{controller}', $controllerName, $template);
    fwrite(fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Controller" . DIRECTORY_SEPARATOR . $controllerName . ".php", "w"), $template);
    $io->write($controllerName . " créé avec succès\n");
}

function createRepository(Cli $io, string $entityName): void
{
    $template = REPOSITORY_TEMPLATE;
    $template = str_replace('{repository}', $entityName . "Repository", $template);
    fwrite(fopen(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Repository" . DIRECTORY_SEPARATOR . $entityName . "Repository.php", "w"), $template);
    $io->write($entityName . "Repository créé avec succès\n");
}
