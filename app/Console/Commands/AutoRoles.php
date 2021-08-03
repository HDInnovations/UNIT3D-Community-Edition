<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate ';

    //private $users;
    private $rules;
    //private $roles;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        //$this->users = User::select("SET @p0='user_special_no_auto_role'; CALL `UsersWithoutPrivilege`(@p0);");
        $this->rules = \App\Models\AutoRoles::all();
        //$roles = Role::whereIn('id', \App\Models\AutoRoles::distinct()->get(['role_id']))->get();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach ($this->rules as $rule) {
            if ($rule->enabled) {
                $query = DB::table('users')->select('id')
                    ->when($rule->buffer && $rule->bufferMin !== null, function ($query) use ($rule) {
                        return $query->whereRaw('uploaded / '.config('other.ratio').' - downloaded >= '.$rule->bufferMin);
                    })
                    ->when($rule->buffer && $rule->bufferMax !== null, function ($query) use ($rule) {
                        return $query->whereRaw('uploaded / '.config('other.ratio').' - downloaded <= '.$rule->bufferMax);
                    })
                    ->when($rule->download && $rule->downloadMin !== null, function ($query) use ($rule) {
                        return $query->whereRaw('downloaded >= '.$rule->downloadMin);
                    })
                    ->when($rule->download && $rule->downloadMax !== null, function ($query) use ($rule) {
                        return $query->whereRaw('downloaded <= '.$rule->downloadMax);
                    })
                    ->when($rule->upload && $rule->uploadMin !== null, function ($query) use ($rule) {
                        return $query->whereRaw('uploaded >= '.$rule->uploadMin);
                    })
                    ->when($rule->upload && $rule->uploadMax !== null, function ($query) use ($rule) {
                        return $query->whereRaw('uploaded <= '.$rule->uploadMax);
                    })->pluck('id')->toArray();
                $users = User::whereIn('id', array_values($query))->get();
                switch ($rule->type) {
                    case 'give':
                        foreach ($users as $user) {
                            if (! $user->hasRole($rule->roles->slug)) {
                                $user->roles()->attach($rule->roles);
                            }
                        }
                        break;
                    case 'remove':
                        foreach ($users as $user) {
                            if ($user->hasRole($rule->roles->slug)) {
                                $user->roles()->detach($rule->roles);
                            }
                        }
                        break;
                }
            }
        }
    }
}
