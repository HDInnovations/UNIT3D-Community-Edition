<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Helpers;

class RequestViewHelper
{
    public static function view($results)
    {
        $data = [];

        foreach ($results as $list) {

            $category = "<i class='{$list->category->icon} torrent-icon' data-toggle='tooltip' title='' data-original-title='{$list->category->name} Torrent'></i>";
            $request_link = route('request', ['id' => $list->id]);
            $user_link = route('profile', ['username' => $list->user->username, 'id' => $list->user->id]);
            $user = "<a href='{$user_link}'>{$list->user->username}</a>";
            $datetime = date('Y-m-d H:m:s', strtotime($list->created_at));
            $datetime_inner = $list->created_at->diffForHumans();
            $request = trans('request.request');
            $claimed = trans('request.claimed');
            $pending = trans('request.pending');
            $unfilled = trans('request.unfilled');
            $filled = trans('request.filled');

            $status = "";

            if ($list->claimed != null && $list->filled_hash == null) {
                $status .= "<button class='btn btn-xs btn-primary' data-toggle='tooltip' title='' data-original-title='{$request} {$claimed}'>
                            <i class='fa fa-suitcase'></i> {$claimed}</button>";
            } elseif ($list->filled_hash != null && $list->approved_by == null) {
                $status .= "<button class='btn btn-xs btn-info' data-toggle='tooltip' title='' data-original-title='{$request} {$pending}'>
                            <i class='fa fa-question-circle'></i> {$pending}</button>";
            } elseif ($list->filled_hash == null) {
                $status .= "<button class='btn btn-xs btn-danger' data-toggle='tooltip' title='' data-original-title='{$request} {$unfilled}'>
                            <i class='fa fa-times-circle'></i> {$unfilled}</button>";
            } else {
                $status .= "<button class='btn btn-xs btn-success' data-toggle='tooltip' title='' data-original-title='{$request} {$filled}'>
                            <i class='fa fa-check-circle'></i> {$filled}</button>";
            }

            $data[] =
            "<tr>
              <td>{$category}</td>
              <td><span class='label label-success' data-toggle='tooltip' title='' data-original-title='{$list->type}'>{$list->type}</span></td>
              <td><a class='view-torrent' data-id='{$list->id}' href='{$request_link}' data-toggle='tooltip' title='' data-original-title='{$list->name}'>{$list->name}</a></td>
              <td><span class='badge-user'>{$user}</span></td>
              <td><span class='badge-user'>{$list->votes}</span></td>
              <td>{$list->comments->count()}</td>
              <td>{$list->bounty}</td>
              <td><time datetime='{$datetime}'>{$datetime_inner}</time></td>
              <td>{$status}</td>
            </tr>";
        }
        return $data;
    }
}
