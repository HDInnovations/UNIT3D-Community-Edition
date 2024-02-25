<section class="panelV2 panel--grid-item">
    <h2 class="panel__heading">{{ $heading }}</h2>
    <div class="data-table-wrapper">
        <table class="permissions">
            <thead>
                <tr>
                    <th class="permission__deny-header" scope="col">Deny</th>
                    <th class="permission__inherit-header" scope="col">Inherit</th>
                    <th class="permission__allow-header" scope="col">Allow</th>
                    <th class="permission__name-header" scope="col">Permission</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="permission__deny-cell">
                            <input
                                form="role_edit_form"
                                type="radio"
                                name="permissions[{{ $permission }}][authorized]"
                                value="0"
                                @checked($permissionIds->get($permission->value) === false)
                            />
                        </td>
                        <td class="permission__inherit-cell">
                            <input
                                form="role_edit_form"
                                type="radio"
                                name="permissions[{{ $permission }}][authorized]"
                                value=""
                                @checked($permissionIds->get($permission->value) === null)
                            />
                        </td>
                        <td class="permission__allow-cell">
                            <input
                                form="role_edit_form"
                                type="radio"
                                name="permissions[{{ $permission }}][authorized]"
                                value="1"
                                @checked($permissionIds->get($permission->value) === true)
                            />
                        </td>
                        <td class="permission__name-cell">
                            <input
                                form="role_edit_form"
                                type="hidden"
                                name="permissions[{{ $permission }}][permission_id]"
                                value="{{ $permission }}"
                            />
                            <input
                                form="role_edit_form"
                                type="hidden"
                                name="permissions[{{ $permission }}][role_id]"
                                value="{{ $role->id }}"
                            />
                            {{ __($permission->nameTranslationKey()) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
