<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('subtitles')->lazyById()->each(function ($subtitle): void {
            $path = public_path('files/subtitles/').$subtitle->file_name;

            if (is_dir($path)) {
                $files = scandir($path);

                if (\is_array($files) && isset($files[2])) {
                    $firstFile = $files[2]; // [0] = "." and [1] = ".."
                    $fileContents = file_get_contents($path.'/'.$firstFile);
                    unlink($path.'/'.$firstFile);
                    rmdir($path);
                    file_put_contents($path, $fileContents);
                }
            }
        });
    }
};
