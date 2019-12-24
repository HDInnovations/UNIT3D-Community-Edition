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

use Illuminate\Contracts\Config\Repository;

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
    protected $socket = null;

    private array $channels = [];

    private $username = null;

    private bool $registered = false;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    public function __construct(Repository $configRepository)
    {
        $this->username = $this->configRepository->get('irc-bot.username');
        $this->channels = $this->configRepository->get('irc-bot.channels');
        $this->server = $this->configRepository->get('irc-bot.server');
        $this->port = $this->configRepository->get('irc-bot.port');
        $this->hostname = $this->configRepository->get('irc-bot.hostname');
        $this->nickservpass = $this->configRepository->get('irc-bot.nickservpass');
        $this->joinchannels = $this->configRepository->get('irc-bot.joinchannels');
        $this->socket = fsockopen($this->server, $this->port);

        $this->send_data(sprintf('NICK %s', $this->username));
        $this->send_data(sprintf('USER %s %s %s %s', $this->username, $this->hostname, $this->server, $this->username));

        $this->connect();
        $this->configRepository = $configRepository;
    }

    public function __destruct()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    private function connect()
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

    private function send_data($data)
    {
        fwrite($this->socket, sprintf('%s
', $data));
    }

    private function say($channel, $string)
    {
        $this->send_data(sprintf('PRIVMSG %s %s', $channel, $string));
    }

    private function join($channel)
    {
        $this->send_data(sprintf('JOIN %s', $channel));
    }

    public function message($channel, $message)
    {
        // Messages an specific IRC Channel
        if ($this->joinchannels && preg_match('##(\w*[a-zA-Z_0-9]+\w*)#', $channel)) {
            $this->join($channel);
        }

        $this->say($channel, $message);
    }

    public function broadcast($message, $channels = null)
    {
        // Broadcast to all IRC Channels in config
        $channels = (is_null($channels)) ? $this->channels : $channels;
        foreach ($channels as $channel) {
            $this->message($channel, $message);
        }
    }
}
