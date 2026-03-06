<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetupDatabase extends Command {
  protected $signature = 'db:setup';
  protected $description = 'Create the database, run migrations and seed data.';

  public function handle() {
    $dbConnection = ENV('DB_CONNECTION', 'mysql');

    $host = config("database.connections.$dbConnection.host");
    $database = config("database.connections.$dbConnection.database");
    $username = config("database.connections.$dbConnection.username");
    $password = config("database.connections.$dbConnection.password");

    try {

      $this->createDatabase($dbConnection, $host, $database, $username, $password); 
      $this->runMigrations();
      $this->seedDatabase();

      $this->info("Database setup completed successfully.");
      return 0;

    } catch (\Exception $e) {
      $this->error("Error setting up database: " . $e->getMessage());
      return 1;
    }
  }

  private function createDatabase($dbConnection, $host, $database, $username, $password) {
    try {
      $this->info("Creating database '$database' if it doesn't exist...");
      $connection = DB::connection($dbConnection);
      $connection->statement("CREATE DATABASE IF NOT EXISTS `$database`");
      $this->info("Database '$database' is ready.");
    } catch (\Exception $e) {
      $this->error("Failed to create database: " . $e->getMessage());
      throw $e;
    }
  }

  private function runMigrations() {
    $this->info("Running migrations...");
    $this->call('migrate', ['--force' => true]);
    $this->info("Migrations completed.");
  }

  private function seedDatabase() {
    $this->info("Seeding database...");
    $this->call('db:seed', ['--force' => true]);
    $this->info("Database seeding completed.");
  }
}