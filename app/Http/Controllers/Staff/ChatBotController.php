<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     singularity43
 */

namespace App\Http\Controllers\Staff;

use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ChatBotController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    public function __construct(Factory $viewFactory, Redirector $redirector)
    {
        $this->viewFactory = $viewFactory;
        $this->redirector = $redirector;
    }
    /**
     * Display a listing of the Bots resource.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function index(string $hash = null): Factory
    {
        $bots = Bot::orderBy('position', 'ASC')->get();

        return $this->viewFactory->make('Staff.chat.bot.index', [
            'bots' => $bots,
        ]);
    }

    /**
     * Show the form for editing the specified Bot resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id): Factory
    {
        $user = $request->user();
        $bot = Bot::findOrFail($id);

        return $this->viewFactory->make('Staff.chat.bot.edit', [
            'user'           => $user,
            'bot'            => $bot,
        ]);
    }

    /**
     * Update the specified Bot resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function update(Request $request, int $id)
    {
        $user = $request->user();
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
            $bot->slug = Str::slug($request->input('name'));
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
        if ($success === null) {
            $error = 'Unable To Process Request';
            if ($v->errors()) {
                $error = $v->errors();
            }

            return $this->redirector->route('staff.bots.edit', ['id' => $id])
                ->withErrors($error);
        }

        return $this->redirector->route('staff.bots.edit', ['id' => $id])
            ->withSuccess($success);
    }

    /**
     * Remove the specified Bot resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $bot = Bot::where('is_protected', '=', 0)->findOrFail($id);
        $bot->delete();

        return $this->redirector->route('staff.bots.index')
            ->withSuccess('The Humans Vs Machines War Has Begun! Humans: 1 and Bots: 0');
    }

    /**
     * Disable the specified Bot resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable(int $id): Response
    {
        $bot = Bot::findOrFail($id);
        $bot->active = 0;
        $bot->save();

        return $this->redirector->route('staff.bots.index')
            ->withSuccess('The Bot Has Been Disabled');
    }

    /**
     * Enable the specified Bot resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable(int $id): Response
    {
        $bot = Bot::findOrFail($id);
        $bot->active = 1;
        $bot->save();

        return $this->redirector->route('staff.bots.index')
            ->withSuccess('The Bot Has Been Enabled');
    }
}
