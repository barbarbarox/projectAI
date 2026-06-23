<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

#[Signature('db:export-json')]
#[Description('Export Supabase DB to JSON')]
class ExportDbToJson extends Command
{
    public function handle()
    {
        $this->info('Starting database export from Supabase (pgsql)...');

        $tables = [
            'users',
            'scans',
            'temuan',
            'simulasi_serangan',
            'tantangan',
            'poin_user',
            'knowledge_chunks',
            'audit_logs',
            'domain_verifications'
        ];

        $exportData = [];

        foreach ($tables as $table) {
            $this->info("Exporting table: {$table}");
            try {
                $exportData[$table] = DB::connection('pgsql')->table($table)->get()->toArray();
            } catch (\Exception $e) {
                $this->warn("Skipping table {$table} or error: " . $e->getMessage());
            }
        }

        $path = storage_path('app/db_backup.json');
        File::put($path, json_encode($exportData, JSON_PRETTY_PRINT));

        $this->info("Database exported successfully to: {$path}");
    }
}
