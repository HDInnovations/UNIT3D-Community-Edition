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

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;

class TicketAttachmentController extends Controller
{
    /**
     * Download a ticket attachment from storage.
     */
    final public function download(Request $request, Ticket $ticket, TicketAttachment $attachment): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        abort_unless($request->user()->group->is_modo || $request->user()->id === $ticket->user_id, 403);

        return response()->download(getcwd().'/files/attachments/attachments/'.$attachment->file_name)->deleteFileAfterSend(false);
    }
}
