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

return [
    /*
    |--------------------------------------------------------------------------
    | Status column
    |--------------------------------------------------------------------------
    */
    'status_column' => 'status',

    /*
    |--------------------------------------------------------------------------
    | Moderated At column
    |--------------------------------------------------------------------------
    */
    'moderated_at_column' => 'moderated_at',

    /*
    |--------------------------------------------------------------------------
    | Moderated By column
    |--------------------------------------------------------------------------
    | Moderated by column is disabled by default.
    | If you want to include the id of the user who moderated a resource set
    | here the name of the column.
    | REMEMBER to migrate the database to add this column.
    */
    'moderated_by_column' => 'moderated_by',

    /*
    |--------------------------------------------------------------------------
    | Strict Moderation
    |--------------------------------------------------------------------------
    | If Strict Moderation is set to true then the default query will return
    | only approved resources.
    | In other case, all resources except Rejected ones, will returned as well.
    */
    'strict' => true,
];
