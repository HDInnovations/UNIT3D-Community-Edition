<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MatthiasMullie\Minify;

class MinifyAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minify:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Minify the CSS files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private static function minify($in_path, $out_path, $file) {
        if (!file_exists($out_path)) {
            mkdir($out_path, 0777, true);
        }
        $m = new Minify\CSS($in_path . "/" . $file);
        $m->minify($out_path . "/" . $file);
    }

    private static function is_css_file($path) {
        $len = strlen($path);
        if ($len < 4) {
            return false;
        }

        return substr($path, -4, 4) === ".css";
    }

    private static function minify_dir($in_path, $out_path) {
        foreach (scandir($in_path) as $file) {
            if (self::is_css_file($file)) {
                self::minify($in_path, $out_path, $file);
            }
        }
    }

    private static function minify_simple($in_path) {
        $pos = strpos($in_path, "assets");
        if ($pos !== false) {
            $out_path = substr_replace($in_path, "public", $pos, strlen("public"));
        }

        self::minify_dir($in_path, $out_path);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        self::minify_simple("assets/css/main");
        self::minify_simple("assets/css/nav");
    }
}
