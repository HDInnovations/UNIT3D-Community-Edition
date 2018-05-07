<?php

return [

    /**
     * Which broadcasting driver will be used ?
     *
     * Supports: Pusher or Database
     *
     * Note: If using a Free Pusher account, once daily limits are hit the system will automatically fallback to
     * database driver.
     */
    'driver' => 'Pusher',

    /**
     * Which chatroom should system messages be routed to ?
     *
     * Note: can use the id or name of the chatroom
     *
     * id (integer) example: 3
     * name (string) example: 'System'
     */
    'system_chatroom' => 'System',


];