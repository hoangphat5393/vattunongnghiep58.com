<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pages')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->change();
            });
            $this->addIndex('pages', 'slug', 'pages_slug_index');
            $this->addIndex('pages', ['status', 'id'], 'pages_status_id_index');
            if (Schema::hasColumn('pages', 'type')) {
                $this->addIndex('pages', ['type', 'slug'], 'pages_type_slug_index');
            }
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->change();
            });
            $this->addIndex('products', 'slug', 'products_slug_index');
            $this->addIndex('products', ['status', 'id'], 'products_status_id_index');
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->change();
            });
            $this->addIndex('categories', 'slug', 'categories_slug_index');
            $this->addIndex('categories', ['status', 'id'], 'categories_status_id_index');
            $this->addIndex('categories', 'parent', 'categories_parent_index');
        }
    }

    public function down(): void
    {
        $this->dropIndexIfExists('pages', 'pages_slug_index');
        $this->dropIndexIfExists('pages', 'pages_status_id_index');
        $this->dropIndexIfExists('pages', 'pages_type_slug_index');
        $this->dropIndexIfExists('products', 'products_slug_index');
        $this->dropIndexIfExists('products', 'products_status_id_index');
        $this->dropIndexIfExists('categories', 'categories_slug_index');
        $this->dropIndexIfExists('categories', 'categories_status_id_index');
        $this->dropIndexIfExists('categories', 'categories_parent_index');
    }

    /**
     * @param  string|array<int, string>  $columns
     */
    private function addIndex(string $table, string|array $columns, string $indexName): void
    {
        if (! $this->columnsExist($table, $columns) || $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
            $blueprint->index($columns, $indexName);
        });
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! Schema::hasTable($table) || ! $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
            $blueprint->dropIndex($indexName);
        });
    }

    /**
     * @param  string|array<int, string>  $columns
     */
    private function columnsExist(string $table, string|array $columns): bool
    {
        foreach ((array) $columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('{$table}')");

            foreach ($indexes as $index) {
                if (($index->name ?? '') === $indexName) {
                    return true;
                }
            }

            return false;
        }

        $database = $connection->getDatabaseName();
        $result = DB::select(
            'SELECT COUNT(*) AS cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $indexName]
        );

        return (int) ($result[0]->cnt ?? 0) > 0;
    }
};
