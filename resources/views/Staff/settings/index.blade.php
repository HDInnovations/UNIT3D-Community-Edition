@extends('layout.default')

@section('content')
<div class="container">
<div class="col-md-10">
<h4 class="text-center">Site Settings</h4>
<table class="table table-bordered table-hover" width="100%" align="center">
<tr>
<td class="header" colspan="1">Site URL</td>
<td class="lista" colspan="3"><input type="text" name="url" value="{{ Config::get('app.url') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Site Tme Zone</td>
<td class="lista" colspan="3"><input type="text" name="timezone" value="{{ Config::get('app.timezone') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Default Language</td>
<td class="lista" colspan="3"><input type="text" name="locale" value="{{ Config::get('app.locale') }}" size="60" /></td>
</tr>

<tr>
<td class="header" colspan="1">Title</td>
<td class="lista" colspan="3"><input type="text" name="title" value="{{ Config::get('other.title') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Sub Title</td>
<td class="lista" colspan="3"><input type="text" name="subTitle" value="{{ Config::get('other.subTitle') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Admin Email</td>
<td class="lista" colspan="3"><input type="text" name="email" value="{{ Config::get('other.email') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Meta Description</td>
<td class="lista" colspan="3"><input type="text" name="meta_description" value="{{ Config::get('other.meta_description') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Default Ratio To Download</td>
<td class="lista" colspan="3"><input type="number" name="ratio" value="{{ Config::get('other.ratio') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">FreeLeech</td>
<td class="lista" colspan="3"><input type="text" name="freeleech" value="{{ Config::get('other.freeleech') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Private Site?</td>
<td class="lista" colspan="3"><input type="text" name="private" value="{{ Config::get('other.private') }}" size="60" /></td>
</tr>
<tr>
<td class="header" colspan="1">Invite Only?</td>
<td class="lista" colspan="3"><input type="text" name="invite-only" value="{{ Config::get('other.invite-only') }}" size="60" /></td>
</tr>
</table>

<center>
<input type="submit" name="write" class="btn btn-md btn-success" value="Confrim" />
<input type="submit" name="cancel" class="btn btn-md btn-warning" value="Cancel" />
</center>
</div>
</div>
@stop
