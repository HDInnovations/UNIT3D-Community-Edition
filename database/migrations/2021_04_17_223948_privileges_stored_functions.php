<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PrivilegesStoredFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('drop function if exists UserHasPrivilegeTo;');
        DB::unprepared('CREATE FUNCTION UserHasPrivilegeTo(USER INT UNSIGNED, privilege_slug VARCHAR(255)) RETURNS INT UNSIGNED DETERMINISTIC READS SQL DATA BEGIN DECLARE result INT(1) DEFAULT 0; DECLARE role BIGINT; DECLARE privilege BIGINT; DECLARE bDone INT(1) DEFAULT 0; DECLARE countUR INT; DECLARE countR INT; DECLARE countU INT; DECLARE roles CURSOR FOR SELECT role_id FROM user_role WHERE user_id = USER; DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1; SELECT id INTO privilege FROM privileges WHERE ((slug LIKE privilege_slug) COLLATE utf8mb4_unicode_ci); SET result = 0; SET countU = 0; SET countUR = 0; SELECT COUNT(*) INTO countUR FROM user_restricted_privilege WHERE user_id = USER AND privilege_id = privilege; IF countUR < 1 THEN SELECT COUNT(*) INTO countU FROM user_privilege WHERE user_id = USER AND privilege_id = privilege; IF countU >= 1 THEN SET result = 1; ELSE OPEN roles; SET bDone = 0; REPEAT FETCH roles INTO role; SET countR = 0; SELECT COUNT(*) INTO countR FROM role_privilege WHERE role_id = role AND privilege_id = privilege; IF countR >= 1 THEN SET bDone = 1; SET result = 1; END IF; UNTIL bDone END REPEAT; END IF; END IF; RETURN (result); END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::raw('drop function if exists UserHasPrivilegeTo;');
    }
}
