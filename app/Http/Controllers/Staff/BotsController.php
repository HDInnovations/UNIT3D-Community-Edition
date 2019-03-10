<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     singularity43
 */

namespace App\Http\Controllers\Staff;

use App\Models\Bot;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BotsController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * BotsController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Display a listing of the Bots resource.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function index($hash = null)
    {
        $bots = Bot::orderBy('position', 'ASC')->get();

        return view('Staff.bots.index', [
            'bots' => $bots,
        ]);
    }

    /**
     * Show the form for editing the specified Bot resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        $bot = Bot::findOrFail($id);

        return view('Staff.bots.edit', [
            'user'           => $user,
            'bot'            => $bot,
        ]);
    }

    /**
     * Update the specified Bot resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $bot = Bot::findOrFail($id);

        if ($request->has('command') && $request->input('command') == $bot->command) {
            $v = validator($request->all(), [
                'name' => 'required|min:3|max:255',
                'command' => 'required|alpha_dash|min:3|max:255',
                'position' => 'required',
                'color' => 'required',
                'icon' => 'required',
                'emoji' => 'required',
                'help' => 'sometimes|max:9999',
                'info' => 'sometimes|max:9999',
                'about' => 'sometimes|max:9999',
            ]);
        } else {
            $v = validator($request->all(), [
                'name' => 'required|min:3|max:255',
                'command' => 'required|alpha_dash|min:3|max:255|unique:bots',
                'position' => 'required',
                'color' => 'required',
                'icon' => 'required',
                'emoji' => 'required',
                'help' => 'sometimes|max:9999',
                'info' => 'sometimes|max:9999',
                'about' => 'sometimes|max:9999',
            ]);
        }

        $error = null;
        $success = null;
        $redirect = null;

        if ($v->passes()) {
            $bot->name = $request->input('name');
            $bot->slug = str_slug($request->input('name'));
            $bot->position = $request->input('position');
            $bot->color = $request->input('color');
            $bot->icon = $request->input('icon');
            $bot->emoji = $request->input('emoji');
            $bot->about = $request->input('about');
            $bot->info = $request->input('info');
            $bot->help = $request->input('help');
            $bot->command = $request->input('command');
            $bot->save();
            $success = 'The Bot Has Been Updated';
        }
        if (! $success) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return redirect()->route('Staff.bots.edit', ['id' => $id])
                ->with($this->toastr->error($error, 'Whoops!', ['options']));
        }

        return redirect()->route('Staff.bots.edit', ['id' => $id])
            ->with($this->toastr->success($success, 'Yay!', ['options']));
    }

    /**
     * Remove the specified Bot resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bot = Bot::where('is_protected', '=', 0)->findOrFail($id);
        $bot->delete();

        return redirect()->route('Staff.bots.index')
            ->with($this->toastr->success('The Humans Vs Machines War Has Begun! Humans: 1 and Bots: 0', 'Yay!', ['options']));
    }

    /**
     * Disable the specified Bot resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        $bot = Bot::findOrFail($id);
        $bot->active = 0;
        $bot->save();

        return redirect()->route('Staff.bots.index')
            ->with($this->toastr->success('The Bot Has Been Disabled', 'Yay!', ['options']));
    }

    /**
     * Enable the specified Bot resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $bot = Bot::findOrFail($id);
        $bot->active = 1;
        $bot->save();

        return redirect()->route('Staff.bots.index')
            ->with($this->toastr->success('The Bot Has Been Enabled', 'Yay!', ['options']));
    }
}
