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

namespace App\Bots;

class IRCAnnounceBot
{
    public $server;

    public $port;

    public $hostname;

    public $nickservpass;

    public $joinchannel;

    protected $socket;

    protected $eol;

    private $channel;

    private $username;

    private bool $registered = false;

    public function __construct()
    {
        $this->eol = PHP_EOL;
        $this->username = config('irc-bot.username');
        $this->channel = config('irc-bot.channel');
        $this->server = config('irc-bot.server');
        $this->port = config('irc-bot.port');
        $this->hostname = config('irc-bot.hostname');
        $this->nickservpass = config('irc-bot.nickservpass');
        $this->joinchannel = config('irc-bot.joinchannel');
        $this->socket = fsockopen($this->server, $this->port);

        $this->send_data(sprintf('NICK %s', $this->username));
        $this->send_data(sprintf('USER %s %s %s %s', $this->username, $this->hostname, $this->server, $this->username));

        $this->connect();
        sleep(2);
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
            // If MOTD
            if ($ex[1] === "366") {
                return;
            }
        }
    }

    private function send_data($data): void
    {
        fwrite($this->socket, sprintf('%s %s', $data, $this->eol));
    }

    private function say($channel, $string): void
    {
        $this->send_data(sprintf('PRIVMSG %s %s %s', $channel, $string, $this->eol));
    }

    private function join($channel): void
    {
        $this->send_data(sprintf('JOIN %s %s', $channel, $this->eol));
    }

    public function message($channel, $message): void
    {
        // Messages an specific IRC Channel
        if ($this->joinchannel && preg_match('/#(\w*[a-zA-Z_0-9]+\w*)/', (string) $channel)) {
            $this->join($channel);
        }

        $this->say($channel, $message);
    }
}
