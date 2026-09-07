<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:db-run-sql-objects')]
#[Description('Run SQL objects (functions, procedures, triggers) from docs/02-sql-objects.sql')]
class DbRunSqlObjects extends Command
{
    public function handle(): int
    {
        $sqlPath = base_path('docs/02-sql-objects.sql');

        if (! file_exists($sqlPath)) {
            $this->error("File tidak ditemukan: {$sqlPath}");
            return 1;
        }

        $this->info("Menjalankan SQL objects via MySQL CLI...");
        
        // Use MySQL CLI to execute the file (handles DELIMITER properly)
        $config = config('database.connections.mysql');
        $host = $config['host'];
        $port = $config['port'] ?? 3306;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $cmd = "mysql -h {$host} -P {$port} -u {$username}";
        if ($password) {
            $cmd .= " -p'{$password}'";
        }
        $cmd .= " {$database} < {$sqlPath}";

        $this->info("Executing: mysql -h {$host} -P {$port} -u {$username} {$database} < {$sqlPath}");
        
        $result = shell_exec($cmd . ' 2>&1');
        
        if ($result !== null && trim($result) !== '') {
            $this->error('Error output: ' . trim($result));
        }

        $this->verify();
        return 0;
    }

    private function verify(): void
    {
        $this->info('Verifikasi:');

        $functions = DB::select("SHOW FUNCTION STATUS WHERE Db = DATABASE()");
        $fnNames = array_column($functions, 'Name');
        $expectedFn = ['fn_rata_rata_nilai', 'fn_persentase_hadir'];
        foreach ($expectedFn as $fn) {
            $this->line(in_array($fn, $fnNames) ? "  <fg=green>✓</> Function {$fn}" : "  <fg=red>✗</> Function {$fn} TIDAK DITEMUKAN");
        }

        $procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
        $procNames = array_column($procedures, 'Name');
        $expectedProc = ['sp_input_nilai_kelas', 'sp_rekap_absensi'];
        foreach ($expectedProc as $proc) {
            $this->line(in_array($proc, $procNames) ? "  <fg=green>✓</> Procedure {$proc}" : "  <fg=red>✗</> Procedure {$proc} TIDAK DITEMUKAN");
        }

        $triggers = DB::select("SHOW TRIGGERS");
        $triggerNames = array_column($triggers, 'Trigger');
        $expectedTriggers = ['trg_rekap_nilai_insert', 'trg_rekap_nilai_update', 'trg_log_nilai_update', 'trg_absensi_insert'];
        foreach ($expectedTriggers as $trg) {
            $this->line(in_array($trg, $triggerNames) ? "  <fg=green>✓</> Trigger {$trg}" : "  <fg=red>✗</> Trigger {$trg} TIDAK DITEMUKAN");
        }
    }
}