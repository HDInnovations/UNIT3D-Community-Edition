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

namespace App\Bots;

class IRCAnnounceBot
{
	protected $socket = null;
	private $channels = array();
	private $username = null;
	private $registered = false;

	public function __construct($app) {
		$this->app = $app;
		$this->username = config('irc.username');
		$this->channels = config('irc.channels');
		$this->server = config('irc.server');
		$this->port = config('irc.port');
		$this->hostname = config('irc.hostname');
		$this->nickservpass = config('irc.nickservpass');
		$this->joinchannels = config('irc.joinchannels');
		$this->socket = fsockopen($this->server, $this->port);


		$this->send_data("NICK {$this->username}");
		$this->send_data("USER {$this->username} {$this->hostname} {$this->server} {$this->username}");

		$this->connect();
	}

	public function __destruct()
	{
		if ($this->socket)
		{
			fclose($this->socket);
		}
	}

	private function connect()
	{
		while ($data = fgets($this->socket))
		{
			flush();
			$ex = explode(" ", $data);

			if ($ex[0] == "PING")
			{
				$this->send_data("PONG " . $ex[1]);
				if ($this->nickservpass)
					$this->send_data("NICKSERV IDENTIFY {$this->nickservpass}");
				return;
			}
		}
	}

	private function send_data($data)
	{
		fputs($this->socket, "$data\r\n");
	}

	private function say($channel, $string) {
		$this->send_data("PRIVMSG $channel $string");
	}

	private function join($channel) {
		$this->send_data("JOIN $channel");
	}

	public function message($channel, $message)
	{
        // Messages an specific IRC Channel
		if ($this->joinchannels && preg_match('/#(\w*[a-zA-Z_0-9]+\w*)/', $channel))
		{
			$this->join($channel);
		}

		$this->say($channel, $message);
	}

	public function broadcast($message, $channels = null)
	{
        // Broadcast to all IRC Channels in config
		$channels = (is_null($channels)) ? $this->channels : $channels;
		foreach ($channels as $channel)
		{
			$this->message($channel, $message);
		}
	}
}
