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
 * @credits    clandestine8 <https://github.com/clandestine8>
 */

namespace Database\Seeders;

use App\Models\Forum;
use App\Models\Privilege;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ConvertForumPermissions extends Seeder
{
    /**
     * @var array[]
     */
    private array $GroupToRoleMap;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forums = Forum::all();
        foreach ($forums as $forum) {
            $perms = $forum->permissions()->get();
            $showForum = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_show_forum', 'name' =>'Forums: '.$forum->name.' - Show Forum']);
            $readTopics = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_read_topic', 'name' =>'Forums: '.$forum->name.' - Read Topics']);
            $replyTopic = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_reply_topic', 'name' =>'Forums: '.$forum->name.' - Reply To Topics']);
            $createTopic = Privilege::create(['slug'=> 'forum_'.$forum->slug.'_start_topic', 'name' =>'Forums: '.$forum->name.' - Create Topics']);
            foreach ($perms as $perm) {
                $role = Role::where('slug', '=', $this->searchMap($perm->group_id))->first();
                $this->command->getOutput()->writeln('Attaching '.$forum->name.' Privileges to '.$role->name);
                $this->command->getOutput()->writeln($perm);
                if ($perm->show_forum) {
                    $this->command->getOutput()->writeln('Attach Show Forum');
                    $this->command->getOutput()->writeln($showForum);
                    $role->privileges()->attach($showForum);
                }
                if ($perm->read_topic) {
                    $this->command->getOutput()->writeln('Attach Read Topics');
                    $this->command->getOutput()->writeln($readTopics);
                    $role->privileges()->attach($readTopics);
                }
                if ($perm->reply_topic) {
                    $this->command->getOutput()->writeln('Attach Reply To Topics');
                    $this->command->getOutput()->writeln($replyTopic);
                    $role->privileges()->attach($replyTopic);
                }
                if ($perm->start_topic) {
                    $this->command->getOutput()->writeln('Attach Create Topics');
                    $this->command->getOutput()->writeln($createTopic);
                    $role->privileges()->attach($createTopic);
                }
            }
        }
    }

    public function __construct()
    {
        //This is the mapping for depreciated Groups to the New Roles that will be used to generate the new Privileges structure for the UNIT3D Forums, and assigning them to User Roles.
        //Configure these mappings prior to migrating to RBAC. Forum Permissions will be handled by the RBAC Roles and Privileges system
        $this->GroupToRoleMap = [
            ['group'=> 1, 'role'=> 'validating'], //Validating
            ['group'=> 2, 'role'=> 'guest'], //Guest
            ['group'=> 3, 'role'=> 'user'], //User
            ['group'=> 4, 'role'=> 'admin'], //Administrator
            ['group'=> 5, 'role'=> 'banned'], //Banned
            ['group'=> 6, 'role'=> 'moderator'], //Moderator
            ['group'=> 7, 'role'=> 'uploader'], //Uploader
            ['group'=> 8, 'role'=> 'trustee'], //Trustee
            ['group'=> 9, 'role'=> 'bot'], //Bot
            ['group'=> 10, 'role'=> 'root'], //Owner
            ['group'=> 11, 'role'=> 'group_2'], //PowerUser
            ['group'=> 12, 'role'=> 'group_3'], //SuperUser
            ['group'=> 13, 'role'=> 'group_4'], //ExtremeUser
            ['group'=> 14, 'role'=> 'group_5'], //InsaneUser
            ['group'=> 15, 'role'=> 'leech'], //Leech
            ['group'=> 16, 'role'=> 'group_6'], //Veteran
            ['group'=> 17, 'role'=> 'group_7'], //Seeder
            ['group'=> 18, 'role'=> 'group_8'], //Archivist
            ['group'=> 19, 'role'=> 'internal'], //Internal
            ['group'=> 20, 'role'=> 'disabled'], //Disabled
            ['group'=> 21, 'role'=> 'pruned'], //Pruned
        ];
    }

    private function searchMap($gID)
    {
        foreach ($this->GroupToRoleMap as $key => $val) {
            if ($val['group'] == $gID) {
                return $val['role'];
            }
        }

        return null;
    }
}
