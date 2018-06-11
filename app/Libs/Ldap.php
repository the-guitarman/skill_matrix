<?php

namespace App\Libs;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

abstract class Ldap {
    const UID_REPLACEMENT_PATTERN = '__UID__';

    public static function authenticate($username, $password) {
        //return true;
        $result = false;

        Log::info('Ldap::authenticate(...)');

        $config              = self::get_config();
        $ldap_servers        = $config['servers'];
        $ldap_standard_port  = $config['standard_port'];
        $ldap_dn             = str_replace(self::UID_REPLACEMENT_PATTERN, $username, $config['dn']);
        $ldap_base_dn        = $config['base_dn'];
        $filter              = str_replace(self::UID_REPLACEMENT_PATTERN, $username, $config['filter']);

        $ldap_connection = self::get_connection($ldap_servers, $ldap_standard_port);
        if ($ldap_connection) {
            ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

            if (@ldap_bind($ldap_connection, $ldap_dn, $password)) {
                Log::info("LDAP: Binding to $ldap_dn successful.");
                Log::info("LDAP: Ldap::authenticate('$username', '********') => true");
                $result = true;
            } else {
                Log::info("LDAP: Binding to $ldap_dn failed.");
            }

            ldap_close($ldap_connection);
            Log::info("LDAP: Connection closed.");
        }

        Log::info('');

        return $result;
    }

    public static function get_config() {
        $config = Config::get('ldap.'.config('app.env'));
        if (empty($config)) {
            $config = Config::get('ldap.default');
        }
        return $config;
    }

    public static function get_user_info($username, $ldap_connection = null) {
        //return [['cn' => ['Nikola Tesla']]];

        $config       = self::get_config();
        $ldap_base_dn = $config['base_dn'];
        $filter       = str_replace(self::UID_REPLACEMENT_PATTERN, $username, $config['filter']);

        if (empty($ldap_connection)) {

            $ldap_servers        = $config['servers'];
            $ldap_standard_port  = $config['standard_port'];
            $ldap_connection = self::get_connection($ldap_servers, $ldap_standard_port);
        }

        $ldap_search = ldap_search($ldap_connection, $ldap_base_dn, $filter);
        return ldap_get_entries($ldap_connection, $ldap_search);
    }

    private static function get_connection($ldap_servers, $ldap_standard_port) {
        $result = null;

        foreach ($ldap_servers as $ldap_server) {
            $ldap_server_parts = explode(":", $ldap_server);
            $ldap_server       = $ldap_server_parts[0];
            if (empty($ldap_server_parts[1])) {
                $ldap_port = $ldap_standard_port;
            } else {
                $ldap_port = intval($ldap_server_parts[1]);
            }

            $ldap_server_url = "ldap://$ldap_server:$ldap_port";
            Log::info("LDAP: Connecting to $ldap_server_url ...");

            $result = ldap_connect("ldap://".$ldap_server, $ldap_port);
            if ($result) {
                Log::info("LDAP: Connection established to $ldap_server_url.");
                break;
            }
        }

        return $result;
    }
}