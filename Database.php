<?php

namespace app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigration = $this->getAppliedMigrations();


        $files = scandir(Application::$ROOT_DIR.'/migrations');

        $toArrayMigrations = array_diff($files, $appliedMigration);
        foreach ($toArrayMigrations as $migration){
            if($migration === '.' || $migration === '..') {
                continue;
            }
                include_once Application::$ROOT_DIR.'/migrations/'.$migration;
                $className = pathinfo($migration, PATHINFO_FILENAME);
                $instance = new $className;
                $instance->up();
                $this->log("Migration $className has been up");
                $newMigrations[] = $migration;
            }
        if(!empty($newMigrations)){
            $this->SaveMigrations($newMigrations);
        }
        else{
            $this->log('all migration has been accepted');
        }

    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations 
    (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
    )
     ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function SaveMigrations($migrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $migrations ));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }
    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s')  . ' - ' . $message . PHP_EOL;
    }
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}