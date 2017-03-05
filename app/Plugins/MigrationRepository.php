<?php

namespace Codice\Plugins;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class MigrationRepository extends DatabaseMigrationRepository implements MigrationRepositoryInterface
{
    protected $plugin;

    /**
     * Create a new database migration repository instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $resolver
     * @param  string  $plugin  Unique application-wide plugin identifier
     */
    public function __construct(Resolver $resolver, $plugin)
    {
        $this->resolver = $resolver;
        $this->plugin = $plugin;
        $this->table = 'migrations_plugins';

        parent::__construct($resolver, $this->table);
    }

    /**
     * Get the ran migrations.
     *
     * @return array
     */
    public function getRan()
    {
        return $this->table()
            ->where('plugin', $this->plugin)
            ->orderBy('batch', 'asc')
            ->orderBy('migration', 'asc')
            ->pluck('migration')->all();
    }

    /**
     * Get list of migrations.
     *
     * @param  int  $steps
     * @return array
     */
    public function getMigrations($steps)
    {
        $query = $this->table()
            ->where('plugin', $this->plugin)
            ->where('batch', '>=', '1');

        return $query->orderBy('migration', 'desc')->take($steps)->get()->all();
    }

    /**
     * Get the last migration batch.
     *
     * @return array
     */
    public function getLast()
    {
        $query = $this->table()
            ->where('plugin', $this->plugin)
            ->where('batch', $this->getLastBatchNumber());

        return $query->orderBy('migration', 'desc')->get()->all();
    }

    /**
     * Log that a migration was run.
     *
     * @param  string  $file
     * @param  int     $batch
     * @return void
     */
    public function log($file, $batch)
    {
        $record = ['plugin' => $this->plugin, 'migration' => $file, 'batch' => $batch];

        $this->table()->insert($record);
    }

    /**
     * Remove a migration from the log.
     *
     * @param  object  $migration
     * @return void
     */
    public function delete($migration)
    {
        $this->table()->where('migration', $migration->migration)->delete();
    }

    /**
     * Create the migration repository data store.
     *
     * @return void
     */
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('plugin');
            $table->string('migration');
            $table->integer('batch');
        });
    }
}
