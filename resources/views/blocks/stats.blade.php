<!-- Tracker Stats -->
<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>Site Stats</h4></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-4">
          <table class="table table-condensed table-striped">
            <tbody>
              <tr>
                <td><a href="#" title="Torrents"><strong>Total Torrents</strong></a></td>
                <td>{{ $num_torrent }}</td>
              </tr>
              <tr>
                <td><a href="#" title="Movies"><strong>Movie Torrents</strong></a></td>
                <td>{{ $num_movies }}</td>
              </tr>
              <tr>
                <td><a href="#" title="TV-Shows"><strong>HDTV Torrents</strong></a></td>
                <td>{{ $num_hdtv }}</td>
              </tr>
              <tr>
                <td><a href="#" title="Dead"><strong>Dead Torrents</strong></a></td>
                <td>{{ $num_dead }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-4">
          <table class="table table-condensed table-striped">
            <tbody>
              <tr>
                <td><strong>Users</strong></td>
                <td>{{ $num_user }}</td>
              </tr>
              <tr>
                <td><strong>Seeds</strong></td>
                <td>{{ $num_seeders }}</td>
              </tr>
              <tr>
                <td><strong>Leeches</strong></td>
                <td>{{ $num_leechers }}</td>
              </tr>
              <tr>
                <td><strong>Peers</strong></td>
                <td>{{ $num_peers }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-4">
          <table class="table table-condensed table-striped">
            <tbody>
              <tr>
                <td><strong>Total Upload</strong></td>
                <td>{{ \App\Helpers\StringHelper::formatBytes($tot_upload ,2) }}</td>
              </tr>
              <tr>
                <td><strong>Total Download</strong></td>
                <td>{{ \App\Helpers\StringHelper::formatBytes($tot_download ,2) }}</td>
              </tr>
              <tr>
                <td><strong>Total Traffic</strong></td>
                <td>{{ \App\Helpers\StringHelper::formatBytes($tot_up_down ,2) }}</td>
              </tr>
              <tr>
                <td><a href="#" title="FANRES"><strong>FANRES Torrents</strong></a></td>
                <td>{{ $num_fan }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Tracker Stats -->
