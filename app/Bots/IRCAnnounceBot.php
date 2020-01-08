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
 * @author     HDVinnie
 */

namespace App\Bots;

final class IRCAnnounceBot
{
    /**
     * @var mixed
     */
    public $server;
    /**
     * @var mixed
     */
    public $port;
    /**
     * @var mixed
     */
    public $hostname;
    /**
     * @var mixed
     */
    public $nickservpass;
    /**
     * @var mixed
     */
    public $joinchannels;
    /**
     * @var null|resource|bool
     */
    protected $socket = null;

    /**
     * @var mixed[]
     */
    private array $channels = [];

    /**
     * @var null
     */
    private $username = null;

    private bool $registered = false;

    public function __construct()
    {
        $this->username = config('irc-bot.username');
        $this->channels = config('irc-bot.channels');
        $this->server = config('irc-bot.server');
        $this->port = config('irc-bot.port');
        $this->hostname = config('irc-bot.hostname');
        $this->nickservpass = config('irc-bot.nickservpass');
        $this->joinchannels = config('irc-bot.joinchannels');
        $this->socket = fsockopen($this->server, $this->port);

        $this->send_data(sprintf('NICK %s', $this->username));
        $this->send_data(sprintf('USER %s %s %s %s', $this->username, $this->hostname, $this->server, $this->username));

        $this->connect();
    }

    public function __destruct()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    private function connect(): void
    {
        while ($data = fgets($this->socket)) {
            flush();
            $ex = explode(' ', $data);

            if ($ex[0] === 'PING') {
                $this->send_data('PONG '.$ex[1]);
                if ($this->nickservpass) {
                    $this->send_data(sprintf('NICKSERV IDENTIFY %s', $this->nickservpass));
                }

                return;
            }
        }
    }

    private function send_data($data): void
    {
        fwrite($this->socket, sprintf('%s
', $data));
    }

    private function say($channel, $string): void
    {
        $this->send_data(sprintf('PRIVMSG %s %s', $channel, $string));
    }

    private function join($channel): void
    {
        $this->send_data(sprintf('JOIN %s', $channel));
    }

    public function message($channel, $message): void
    {
        // Messages an specific IRC Channel
        if ($this->joinchannels && preg_match('##(\w*[a-zA-Z_0-9]+\w*)#', $channel)) {
            $this->join($channel);
        }

        $this->say($channel, $message);
    }

    public function broadcast($message, $channels = null): void
    {
        // Broadcast to all IRC Channels in config
        $channels = (is_null($channels)) ? $this->channels : $channels;
        foreach ($channels as $channel) {
            $this->message($channel, $message);
        }
    }
}
