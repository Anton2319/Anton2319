<?php
define('ROOT_DIR', dirname(__DIR__));
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use Dotenv\Dotenv;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;
use museshelf\Playlist;

require_once ROOT_DIR . '/Documents/Playlist.php';

$loader = require_once ROOT_DIR . '/vendor/autoload.php';
$loader->add('Documents', ROOT_DIR);

// Load environment variables
$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$config = new Configuration();
$config->setProxyDir(ROOT_DIR . '/cache');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(ROOT_DIR . '/cache');
$config->setHydratorNamespace('Hydrators');
$config->setMetadataDriverImpl(AnnotationDriver::create(ROOT_DIR . '/Documents'));
$config->setDefaultDB('museshelf');

// MongoDB connection URI
$uri = $_ENV['MONGODBURI'];
if (!$uri) {
    throw new Exception('MONGODBURI environment variable not set.');
}

$client = new Client($uri, []);

$dm = DocumentManager::create($client, $config);

$playlistRepository = $dm->getRepository(Playlist::class);

$playlists = $playlistRepository->findAll();

// Convert playlists to an array of associative arrays
$playlistData = array_map(function ($playlist) {
    return $playlist->toArray();
}, $playlists);


$json = json_encode($playlistData);

header('Content-Type: application/json');

echo $json;
?>