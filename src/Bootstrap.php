<?php
namespace c3p0;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Endeveit\Cache\Drivers\Memcache as CMemcache;


class Bootstrap {
    
    private static $instance = null;
    
    private function __construct() {
        
    }
    
    public static function getInstance() {
        if (empty(self::$instance)) self::$instance = new Bootstrap ();
        return self::$instance; 
    }
    
    public function start() {
        $this->startCache();
        $this->startDatabase();
        $this->startWhoops();
        $this->startOthers();
    }
    
    private function startCache() {
        global $_cache;
        if (file_exists(BASE_PATH . '/app/config/memcache.php')) {
            $memcacheConfig = require_once BASE_PATH . '/app/config/memcache.php';           
            foreach ($memcacheConfig as $group => $item) {
                $memcache = new \Memcache();
                foreach ($item as $itemttt) {
                    $memcache->addServer($itemttt['host'], $itemttt['port']);
                }
                $cache = new CMemcache(array('client' => $memcache));
                $_cache[$group] = $cache; 
            }                   
        }
    }
    
    private function startDatabase() {
        global $_entityManager; 
        if (file_exists(BASE_PATH . '/app/config/database.php')) {
            $databaseConfig = require_once BASE_PATH . '/app/config/database.php'; 
            if ($databaseConfig) {
                foreach ($databaseConfig as $key => $value) {
                    $config = Setup::createConfiguration(true);
                    $driver = new AnnotationDriver(new AnnotationReader(), $value['source']);
                    AnnotationRegistry::registerLoader('class_exists');
                    $config->setMetadataDriverImpl($driver);
                    $em = EntityManager::create($value['database'], $config);
                    $_entityManager[$key] = $em; 
                }
            }
            
        }
    }
    
    private function startWhoops() {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();
    }
    
    private function startOthers() {
        
    }
    
}

