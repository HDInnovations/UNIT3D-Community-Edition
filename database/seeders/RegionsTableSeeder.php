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

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    private $regions;

    public function __construct()
    {
        $this->regions = $this->getRegions();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->regions as $region) {
            Region::updateOrCreate($region);
        }
    }

    private function getRegions(): array
    {
        return [
            [
                'id'       => 1,
                'name'     => 'AFG',
                'position' => 0,
            ],
            [
                'id'       => 2,
                'name'     => 'AIA',
                'position' => 1,
            ],
            [
                'id'       => 3,
                'name'     => 'ALA',
                'position' => 2,
            ],
            [
                'id'       => 4,
                'name'     => 'ALG',
                'position' => 3,
            ],
            [
                'id'       => 5,
                'name'     => 'AND',
                'position' => 4,
            ],
            [
                'id'       => 6,
                'name'     => 'ANG',
                'position' => 5,
            ],
            [
                'id'       => 7,
                'name'     => 'ARG',
                'position' => 6,
            ],
            [
                'id'       => 8,
                'name'     => 'ARM',
                'position' => 7,
            ],
            [
                'id'       => 9,
                'name'     => 'ARU',
                'position' => 8,
            ],
            [
                'id'       => 10,
                'name'     => 'ASA',
                'position' => 9,
            ],
            [
                'id'       => 11,
                'name'     => 'ATA',
                'position' => 10,
            ],
            [
                'id'       => 12,
                'name'     => 'ATF',
                'position' => 11,
            ],
            [
                'id'       => 13,
                'name'     => 'ATG',
                'position' => 12,
            ],
            [
                'id'       => 14,
                'name'     => 'AUS',
                'position' => 13,
            ],
            [
                'id'       => 15,
                'name'     => 'AUT',
                'position' => 14,
            ],
            [
                'id'       => 16,
                'name'     => 'AZE',
                'position' => 15,
            ],
            [
                'id'       => 17,
                'name'     => 'BAH',
                'position' => 16,
            ],
            [
                'id'       => 18,
                'name'     => 'BAN',
                'position' => 17,
            ],
            [
                'id'       => 19,
                'name'     => 'BDI',
                'position' => 18,
            ],
            [
                'id'       => 20,
                'name'     => 'BEL',
                'position' => 19,
            ],
            [
                'id'       => 21,
                'name'     => 'BEN',
                'position' => 20,
            ],
            [
                'id'       => 22,
                'name'     => 'BER',
                'position' => 21,
            ],
            [
                'id'       => 23,
                'name'     => 'BES',
                'position' => 22,
            ],
            [
                'id'       => 24,
                'name'     => 'BFA',
                'position' => 23,
            ],
            [
                'id'       => 25,
                'name'     => 'BHR',
                'position' => 24,
            ],
            [
                'id'       => 26,
                'name'     => 'BHU',
                'position' => 25,
            ],
            [
                'id'       => 27,
                'name'     => 'BIH',
                'position' => 26,
            ],
            [
                'id'       => 28,
                'name'     => 'BLM',
                'position' => 27,
            ],
            [
                'id'       => 29,
                'name'     => 'BLR',
                'position' => 28,
            ],
            [
                'id'       => 30,
                'name'     => 'BLZ',
                'position' => 29,
            ],
            [
                'id'       => 31,
                'name'     => 'BOL',
                'position' => 30,
            ],
            [
                'id'       => 32,
                'name'     => 'BOT',
                'position' => 31,
            ],
            [
                'id'       => 33,
                'name'     => 'BRA',
                'position' => 32,
            ],
            [
                'id'       => 34,
                'name'     => 'BRB',
                'position' => 33,
            ],
            [
                'id'       => 35,
                'name'     => 'BRU',
                'position' => 34,
            ],
            [
                'id'       => 36,
                'name'     => 'BVT',
                'position' => 35,
            ],
            [
                'id'       => 37,
                'name'     => 'CAM',
                'position' => 36,
            ],
            [
                'id'       => 38,
                'name'     => 'CAN',
                'position' => 37,
            ],
            [
                'id'       => 39,
                'name'     => 'CAY',
                'position' => 38,
            ],
            [
                'id'       => 40,
                'name'     => 'CCK',
                'position' => 39,
            ],
            [
                'id'       => 41,
                'name'     => 'CEE',
                'position' => 40,
            ],
            [
                'id'       => 42,
                'name'     => 'CGO',
                'position' => 41,
            ],
            [
                'id'       => 43,
                'name'     => 'CHA',
                'position' => 42,
            ],
            [
                'id'       => 44,
                'name'     => 'CHI',
                'position' => 43,
            ],
            [
                'id'       => 45,
                'name'     => 'CHN',
                'position' => 44,
            ],
            [
                'id'       => 46,
                'name'     => 'CIV',
                'position' => 45,
            ],
            [
                'id'       => 47,
                'name'     => 'CMR',
                'position' => 46,
            ],
            [
                'id'       => 48,
                'name'     => 'COD',
                'position' => 47,
            ],
            [
                'id'       => 49,
                'name'     => 'COK',
                'position' => 48,
            ],
            [
                'id'       => 50,
                'name'     => 'COL',
                'position' => 49,
            ],
            [
                'id'       => 51,
                'name'     => 'COM',
                'position' => 50,
            ],
            [
                'id'       => 52,
                'name'     => 'CPV',
                'position' => 51,
            ],
            [
                'id'       => 53,
                'name'     => 'CRC',
                'position' => 52,
            ],
            [
                'id'       => 54,
                'name'     => 'CRO',
                'position' => 53,
            ],
            [
                'id'       => 55,
                'name'     => 'CTA',
                'position' => 54,
            ],
            [
                'id'       => 56,
                'name'     => 'CUB',
                'position' => 55,
            ],
            [
                'id'       => 57,
                'name'     => 'CUW',
                'position' => 56,
            ],
            [
                'id'       => 58,
                'name'     => 'CXR',
                'position' => 57,
            ],
            [
                'id'       => 59,
                'name'     => 'CYP',
                'position' => 58,
            ],
            [
                'id'       => 60,
                'name'     => 'DJI',
                'position' => 59,
            ],
            [
                'id'       => 61,
                'name'     => 'DMA',
                'position' => 60,
            ],
            [
                'id'       => 62,
                'name'     => 'DOM',
                'position' => 61,
            ],
            [
                'id'       => 63,
                'name'     => 'ECU',
                'position' => 62,
            ],
            [
                'id'       => 64,
                'name'     => 'EGY',
                'position' => 63,
            ],
            [
                'id'       => 65,
                'name'     => 'ENG',
                'position' => 64,
            ],
            [
                'id'       => 66,
                'name'     => 'EQG',
                'position' => 65,
            ],
            [
                'id'       => 67,
                'name'     => 'ERI',
                'position' => 66,
            ],
            [
                'id'       => 68,
                'name'     => 'ESH',
                'position' => 67,
            ],
            [
                'id'       => 69,
                'name'     => 'ESP',
                'position' => 68,
            ],
            [
                'id'       => 70,
                'name'     => 'ETH',
                'position' => 69,
            ],
            [
                'id'       => 71,
                'name'     => 'FIJ',
                'position' => 70,
            ],
            [
                'id'       => 72,
                'name'     => 'FLK',
                'position' => 71,
            ],
            [
                'id'       => 73,
                'name'     => 'FRA',
                'position' => 72,
            ],
            [
                'id'       => 74,
                'name'     => 'FRO',
                'position' => 73,
            ],
            [
                'id'       => 75,
                'name'     => 'FSM',
                'position' => 74,
            ],
            [
                'id'       => 76,
                'name'     => 'GAB',
                'position' => 75,
            ],
            [
                'id'       => 77,
                'name'     => 'GAM',
                'position' => 76,
            ],
            [
                'id'       => 78,
                'name'     => 'GBR',
                'position' => 77,
            ],
            [
                'id'       => 79,
                'name'     => 'GEO',
                'position' => 78,
            ],
            [
                'id'       => 80,
                'name'     => 'GER',
                'position' => 79,
            ],
            [
                'id'       => 81,
                'name'     => 'GGY',
                'position' => 80,
            ],
            [
                'id'       => 82,
                'name'     => 'GHA',
                'position' => 81,
            ],
            [
                'id'       => 83,
                'name'     => 'GIB',
                'position' => 82,
            ],
            [
                'id'       => 84,
                'name'     => 'GLP',
                'position' => 83,
            ],
            [
                'id'       => 85,
                'name'     => 'GNB',
                'position' => 84,
            ],
            [
                'id'       => 86,
                'name'     => 'GRE',
                'position' => 85,
            ],
            [
                'id'       => 87,
                'name'     => 'GRL',
                'position' => 86,
            ],
            [
                'id'       => 88,
                'name'     => 'GRN',
                'position' => 87,
            ],
            [
                'id'       => 89,
                'name'     => 'GUA',
                'position' => 88,
            ],
            [
                'id'       => 90,
                'name'     => 'GUF',
                'position' => 89,
            ],
            [
                'id'       => 91,
                'name'     => 'GUI',
                'position' => 90,
            ],
            [
                'id'       => 92,
                'name'     => 'GUM',
                'position' => 91,
            ],
            [
                'id'       => 93,
                'name'     => 'GUY',
                'position' => 92,
            ],
            [
                'id'       => 94,
                'name'     => 'HAI',
                'position' => 93,
            ],
            [
                'id'       => 95,
                'name'     => 'HKG',
                'position' => 94,
            ],
            [
                'id'       => 96,
                'name'     => 'HMD',
                'position' => 95,
            ],
            [
                'id'       => 97,
                'name'     => 'HON',
                'position' => 96,
            ],
            [
                'id'       => 98,
                'name'     => 'HUN',
                'position' => 97,
            ],
            [
                'id'       => 99,
                'name'     => 'IDN',
                'position' => 98,
            ],
            [
                'id'       => 100,
                'name'     => 'IMN',
                'position' => 99,
            ],
            [
                'id'       => 101,
                'name'     => 'IND',
                'position' => 100,
            ],
            [
                'id'       => 102,
                'name'     => 'IOT',
                'position' => 101,
            ],
            [
                'id'       => 103,
                'name'     => 'IRL',
                'position' => 102,
            ],
            [
                'id'       => 104,
                'name'     => 'IRN',
                'position' => 103,
            ],
            [
                'id'       => 105,
                'name'     => 'IRQ',
                'position' => 104,
            ],
            [
                'id'       => 106,
                'name'     => 'ISL',
                'position' => 105,
            ],
            [
                'id'       => 107,
                'name'     => 'ISR',
                'position' => 106,
            ],
            [
                'id'       => 108,
                'name'     => 'ITA',
                'position' => 107,
            ],
            [
                'id'       => 109,
                'name'     => 'JAM',
                'position' => 108,
            ],
            [
                'id'       => 110,
                'name'     => 'JEY',
                'position' => 109,
            ],
            [
                'id'       => 111,
                'name'     => 'JOR',
                'position' => 110,
            ],
            [
                'id'       => 112,
                'name'     => 'JPN',
                'position' => 111,
            ],
            [
                'id'       => 113,
                'name'     => 'KAZ',
                'position' => 112,
            ],
            [
                'id'       => 114,
                'name'     => 'KEN',
                'position' => 113,
            ],
            [
                'id'       => 115,
                'name'     => 'KGZ',
                'position' => 114,
            ],
            [
                'id'       => 116,
                'name'     => 'KIR',
                'position' => 115,
            ],
            [
                'id'       => 117,
                'name'     => 'KNA',
                'position' => 116,
            ],
            [
                'id'       => 118,
                'name'     => 'KOR',
                'position' => 117,
            ],
            [
                'id'       => 119,
                'name'     => 'KSA',
                'position' => 118,
            ],
            [
                'id'       => 120,
                'name'     => 'KUW',
                'position' => 119,
            ],
            [
                'id'       => 121,
                'name'     => 'KVX',
                'position' => 120,
            ],
            [
                'id'       => 122,
                'name'     => 'LAO',
                'position' => 121,
            ],
            [
                'id'       => 123,
                'name'     => 'LBN',
                'position' => 122,
            ],
            [
                'id'       => 124,
                'name'     => 'LBR',
                'position' => 123,
            ],
            [
                'id'       => 125,
                'name'     => 'LBY',
                'position' => 124,
            ],
            [
                'id'       => 126,
                'name'     => 'LCA',
                'position' => 125,
            ],
            [
                'id'       => 127,
                'name'     => 'LES',
                'position' => 126,
            ],
            [
                'id'       => 128,
                'name'     => 'LIE',
                'position' => 127,
            ],
            [
                'id'       => 129,
                'name'     => 'LKA',
                'position' => 128,
            ],
            [
                'id'       => 130,
                'name'     => 'LUX',
                'position' => 129,
            ],
            [
                'id'       => 131,
                'name'     => 'MAC',
                'position' => 130,
            ],
            [
                'id'       => 132,
                'name'     => 'MAD',
                'position' => 131,
            ],
            [
                'id'       => 133,
                'name'     => 'MAF',
                'position' => 132,
            ],
            [
                'id'       => 134,
                'name'     => 'MAR',
                'position' => 133,
            ],
            [
                'id'       => 135,
                'name'     => 'MAS',
                'position' => 134,
            ],
            [
                'id'       => 136,
                'name'     => 'MDA',
                'position' => 135,
            ],
            [
                'id'       => 137,
                'name'     => 'MDV',
                'position' => 136,
            ],
            [
                'id'       => 138,
                'name'     => 'MEX',
                'position' => 137,
            ],
            [
                'id'       => 139,
                'name'     => 'MHL',
                'position' => 138,
            ],
            [
                'id'       => 140,
                'name'     => 'MKD',
                'position' => 139,
            ],
            [
                'id'       => 141,
                'name'     => 'MLI',
                'position' => 140,
            ],
            [
                'id'       => 142,
                'name'     => 'MLT',
                'position' => 141,
            ],
            [
                'id'       => 143,
                'name'     => 'MNG',
                'position' => 142,
            ],
            [
                'id'       => 144,
                'name'     => 'MNP',
                'position' => 143,
            ],
            [
                'id'       => 145,
                'name'     => 'MON',
                'position' => 144,
            ],
            [
                'id'       => 146,
                'name'     => 'MOZ',
                'position' => 145,
            ],
            [
                'id'       => 147,
                'name'     => 'MRI',
                'position' => 146,
            ],
            [
                'id'       => 148,
                'name'     => 'MSR',
                'position' => 147,
            ],
            [
                'id'       => 149,
                'name'     => 'MTN',
                'position' => 148,
            ],
            [
                'id'       => 150,
                'name'     => 'MTQ',
                'position' => 149,
            ],
            [
                'id'       => 151,
                'name'     => 'MWI',
                'position' => 150,
            ],
            [
                'id'       => 152,
                'name'     => 'MYA',
                'position' => 151,
            ],
            [
                'id'       => 153,
                'name'     => 'MYT',
                'position' => 152,
            ],
            [
                'id'       => 154,
                'name'     => 'NAM',
                'position' => 153,
            ],
            [
                'id'       => 155,
                'name'     => 'NCA',
                'position' => 154,
            ],
            [
                'id'       => 156,
                'name'     => 'NCL',
                'position' => 155,
            ],
            [
                'id'       => 157,
                'name'     => 'NEP',
                'position' => 156,
            ],
            [
                'id'       => 158,
                'name'     => 'NFK',
                'position' => 157,
            ],
            [
                'id'       => 159,
                'name'     => 'NIG',
                'position' => 158,
            ],
            [
                'id'       => 160,
                'name'     => 'NIR',
                'position' => 159,
            ],
            [
                'id'       => 161,
                'name'     => 'NIU',
                'position' => 160,
            ],
            [
                'id'       => 162,
                'name'     => 'NLD',
                'position' => 161,
            ],
            [
                'id'       => 163,
                'name'     => 'NOR',
                'position' => 162,
            ],
            [
                'id'       => 164,
                'name'     => 'NRU',
                'position' => 163,
            ],
            [
                'id'       => 165,
                'name'     => 'NZL',
                'position' => 164,
            ],
            [
                'id'       => 166,
                'name'     => 'OMA',
                'position' => 165,
            ],
            [
                'id'       => 167,
                'name'     => 'PAK',
                'position' => 166,
            ],
            [
                'id'       => 168,
                'name'     => 'PAN',
                'position' => 167,
            ],
            [
                'id'       => 169,
                'name'     => 'PAR',
                'position' => 168,
            ],
            [
                'id'       => 170,
                'name'     => 'PCN',
                'position' => 169,
            ],
            [
                'id'       => 171,
                'name'     => 'PER',
                'position' => 170,
            ],
            [
                'id'       => 172,
                'name'     => 'PHI',
                'position' => 171,
            ],
            [
                'id'       => 173,
                'name'     => 'PLE',
                'position' => 172,
            ],
            [
                'id'       => 174,
                'name'     => 'PLW',
                'position' => 173,
            ],
            [
                'id'       => 175,
                'name'     => 'PNG',
                'position' => 174,
            ],
            [
                'id'       => 176,
                'name'     => 'POL',
                'position' => 175,
            ],
            [
                'id'       => 177,
                'name'     => 'POR',
                'position' => 176,
            ],
            [
                'id'       => 178,
                'name'     => 'PRK',
                'position' => 177,
            ],
            [
                'id'       => 179,
                'name'     => 'PUR',
                'position' => 178,
            ],
            [
                'id'       => 180,
                'name'     => 'QAT',
                'position' => 179,
            ],
            [
                'id'       => 181,
                'name'     => 'REU',
                'position' => 180,
            ],
            [
                'id'       => 182,
                'name'     => 'ROU',
                'position' => 181,
            ],
            [
                'id'       => 183,
                'name'     => 'RSA',
                'position' => 182,
            ],
            [
                'id'       => 184,
                'name'     => 'RUS',
                'position' => 183,
            ],
            [
                'id'       => 185,
                'name'     => 'RWA',
                'position' => 184,
            ],
            [
                'id'       => 186,
                'name'     => 'SAM',
                'position' => 185,
            ],
            [
                'id'       => 187,
                'name'     => 'SCO',
                'position' => 186,
            ],
            [
                'id'       => 188,
                'name'     => 'SDN',
                'position' => 187,
            ],
            [
                'id'       => 189,
                'name'     => 'SEN',
                'position' => 188,
            ],
            [
                'id'       => 190,
                'name'     => 'SEY',
                'position' => 189,
            ],
            [
                'id'       => 191,
                'name'     => 'SGS',
                'position' => 190,
            ],
            [
                'id'       => 192,
                'name'     => 'SHN',
                'position' => 191,
            ],
            [
                'id'       => 193,
                'name'     => 'SIN',
                'position' => 192,
            ],
            [
                'id'       => 194,
                'name'     => 'SJM',
                'position' => 193,
            ],
            [
                'id'       => 195,
                'name'     => 'SLE',
                'position' => 194,
            ],
            [
                'id'       => 196,
                'name'     => 'SLV',
                'position' => 195,
            ],
            [
                'id'       => 197,
                'name'     => 'SMR',
                'position' => 196,
            ],
            [
                'id'       => 198,
                'name'     => 'SOL',
                'position' => 197,
            ],
            [
                'id'       => 199,
                'name'     => 'SOM',
                'position' => 198,
            ],
            [
                'id'       => 200,
                'name'     => 'SPM',
                'position' => 199,
            ],
            [
                'id'       => 201,
                'name'     => 'SRB',
                'position' => 200,
            ],
            [
                'id'       => 202,
                'name'     => 'SSD',
                'position' => 201,
            ],
            [
                'id'       => 203,
                'name'     => 'STP',
                'position' => 202,
            ],
            [
                'id'       => 204,
                'name'     => 'SUI',
                'position' => 203,
            ],
            [
                'id'       => 205,
                'name'     => 'SUR',
                'position' => 204,
            ],
            [
                'id'       => 206,
                'name'     => 'SWZ',
                'position' => 205,
            ],
            [
                'id'       => 207,
                'name'     => 'SXM',
                'position' => 206,
            ],
            [
                'id'       => 208,
                'name'     => 'SYR',
                'position' => 207,
            ],
            [
                'id'       => 209,
                'name'     => 'TAH',
                'position' => 208,
            ],
            [
                'id'       => 210,
                'name'     => 'TAN',
                'position' => 209,
            ],
            [
                'id'       => 211,
                'name'     => 'TCA',
                'position' => 210,
            ],
            [
                'id'       => 212,
                'name'     => 'TGA',
                'position' => 211,
            ],
            [
                'id'       => 213,
                'name'     => 'THA',
                'position' => 212,
            ],
            [
                'id'       => 214,
                'name'     => 'TJK',
                'position' => 213,
            ],
            [
                'id'       => 215,
                'name'     => 'TKL',
                'position' => 214,
            ],
            [
                'id'       => 216,
                'name'     => 'TKM',
                'position' => 215,
            ],
            [
                'id'       => 217,
                'name'     => 'TLS',
                'position' => 216,
            ],
            [
                'id'       => 218,
                'name'     => 'TOG',
                'position' => 217,
            ],
            [
                'id'       => 219,
                'name'     => 'TRI',
                'position' => 218,
            ],
            [
                'id'       => 220,
                'name'     => 'TUN',
                'position' => 219,
            ],
            [
                'id'       => 221,
                'name'     => 'TUR',
                'position' => 220,
            ],
            [
                'id'       => 222,
                'name'     => 'TUV',
                'position' => 221,
            ],
            [
                'id'       => 223,
                'name'     => 'TWN',
                'position' => 222,
            ],
            [
                'id'       => 224,
                'name'     => 'UAE',
                'position' => 223,
            ],
            [
                'id'       => 225,
                'name'     => 'UGA',
                'position' => 224,
            ],
            [
                'id'       => 226,
                'name'     => 'UKR',
                'position' => 225,
            ],
            [
                'id'       => 227,
                'name'     => 'UMI',
                'position' => 226,
            ],
            [
                'id'       => 228,
                'name'     => 'URU',
                'position' => 227,
            ],
            [
                'id'       => 229,
                'name'     => 'USA',
                'position' => 228,
            ],
            [
                'id'       => 230,
                'name'     => 'UZB',
                'position' => 229,
            ],
            [
                'id'       => 231,
                'name'     => 'VAN',
                'position' => 230,
            ],
            [
                'id'       => 232,
                'name'     => 'VAT',
                'position' => 231,
            ],
            [
                'id'       => 233,
                'name'     => 'VEN',
                'position' => 232,
            ],
            [
                'id'       => 234,
                'name'     => 'VGB',
                'position' => 233,
            ],
            [
                'id'       => 235,
                'name'     => 'VIE',
                'position' => 234,
            ],
            [
                'id'       => 236,
                'name'     => 'VIN',
                'position' => 235,
            ],
            [
                'id'       => 237,
                'name'     => 'VIR',
                'position' => 236,
            ],
            [
                'id'       => 238,
                'name'     => 'WAL',
                'position' => 237,
            ],
            [
                'id'       => 239,
                'name'     => 'WLF',
                'position' => 238,
            ],
            [
                'id'       => 240,
                'name'     => 'YEM',
                'position' => 239,
            ],
            [
                'id'       => 241,
                'name'     => 'ZAM',
                'position' => 240,
            ],
            [
                'id'       => 242,
                'name'     => 'ZIM',
                'position' => 241,
            ],
        ];
    }
}
