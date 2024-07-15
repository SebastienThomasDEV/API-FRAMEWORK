<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/templates.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;


$application = new Application();

$application->register('new:controller')
    ->addArgument('name', InputArgument::REQUIRED)
    ->setCode(function (InputInterface $input, OutputInterface $output): int {
        $name = ucfirst($input->getArgument('name'));
        $template = CONTROLLER_TEMPLATE;
        $template = str_replace('%', '$', $template);
        $template = str_replace('@', $name, $template);
        fwrite(fopen(__DIR__ . "/../app/controller/" . $name . "Controller.php", "w"), $template);
        $output->write('Le contrôleur ' . $name . 'Controller' . ' a bien été créer', true);
        return Command::SUCCESS;
    });
$application->register('new:entity')
    ->addArgument('name', InputArgument::REQUIRED)
    ->setCode(function (InputInterface $input, OutputInterface $output): int {
        $name = $input->getArgument('name');
        $properties = [];
        $availableTypes = ['int', 'string', 'float', 'bool', 'array', 'object', 'DateTime'];
        // regex to avoid mistakes in $property a name
        $regex = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';
        $output->write('Entrez les propriétés de l\'entité ' . $name, true);
        while (true) {
            for ($i = 0; $i < 30; $i++) {
                echo PHP_EOL;
            }
            if (count($properties) > 0) {
                $output->write('Propriétés déjà ajoutées : ', true);
                foreach ($properties as $property) {
                    $output->write($property['name'] . ' : ' . $property['type'], true);
                }
            }
            $property = trim(readline('Entrez le nom de la propriété (tapez "exit" pour quitter) : '));
            if ($property === '' || !preg_match($regex, $property)) {
                echo 'Nom de propriété invalide', PHP_EOL;
                die();
            } elseif ($property === 'exit') {
                break;
            }
            $type = trim(readline('Entrez le type de la propriété ' . $property . ' (int|string|float|bool|array|DateTime) ("exit" pour terminer) :'));
            if ($type === 'DateTime') {
                $type = '\DateTime';
            } elseif ($type === 'exit') {
                break;
            }
            if (!in_array($type, $availableTypes)) {
                echo 'Type de propriété invalide', PHP_EOL;
                die();
            } elseif ($type === 'exit') {
                break;
            }
            $properties[] = [
                'name' => $property,
                'type' => $type
            ];
        }
        $properties_template = '';
        $getters_template = '';
        $setters_template = '';

        foreach ($properties as $property) {
            // Generate properties
            $property_template = PROPERTIES_TEMPLATE;
            $property_template = str_replace('@name', $property['name'], $property_template);
            $property_template = str_replace('@type', $property['type'], $property_template);
            $property_template = str_replace('%', '$', $property_template);
            $properties_template .= $property_template . PHP_EOL;
            // Generate getters
            $getter_template = GETTER_TEMPLATE;
            $getter_template = str_replace('@Name', ucfirst($property['name']), $getter_template);
            $getter_template = str_replace('@type', $property['type'], $getter_template);
            $getter_template = str_replace('%', '$', $getter_template);
            $getter_template = str_replace('@name', $property['name'], $getter_template);
            $getters_template .= $getter_template . PHP_EOL;
            // Generate setters
            $setter_template = SETTER_TEMPLATE;
            $setter_template = str_replace('@Name', ucfirst($property['name']), $setter_template);
            $setter_template = str_replace('@type', $property['type'], $setter_template);
            $setter_template = str_replace('%', '$', $setter_template);
            $setter_template = str_replace('@name', $property['name'], $setter_template);
            $setters_template .= $setter_template . PHP_EOL;
        }
        $entity_template = ENTITY_TEMPLATE;
        $entity_template = str_replace('@Entity', ucfirst($name), $entity_template);
        $entity_template = str_replace('@table', strtolower($name), $entity_template);
        $entity_template = str_replace('@properties', $properties_template, $entity_template);
        $entity_template = str_replace('@getters', $getters_template, $entity_template);
        $entity_template = str_replace('@setters', $setters_template, $entity_template);
        $entity_template = str_replace('@Repository', ucfirst($name) . 'Repository', $entity_template);
        $entity_template = str_replace('%', '$', $entity_template);
        $repository_template = REPOSITORY_TEMPLATE;
        $repository_template = str_replace('@Repository', ucfirst($name) . 'Repository', $repository_template);
        fwrite(fopen(__DIR__ . "/../app/repository/" . ucfirst($name) . "Repository.php", "w"), $repository_template);
        fwrite(fopen(__DIR__ . "/../app/entity/" . ucfirst($name) . ".php", "w"), $entity_template);
        $output->write('L\'entité ' . $name . ' a bien été créée', true);

        return Command::SUCCESS;
    });


try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
