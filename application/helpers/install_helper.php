<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('setup_install')) {
    /**
     * @param [type] $host
     * @param [type] $db_user
     * @param [type] $db_password
     * @param [type] $db_name
     * @param [type] $name
     * @param [type] $username
     * @param [type] $email
     * @param [type] $password
     * @param [type] $timezone
     * @return void
     */
    function setup_install($host, $db_user, $db_password, $db_name, $name, $username, $email, $password, $timezone)
    {
        $ci = &get_instance();

        $ci->load->model('setup_model');

        // Connection MYSQL
        $config['hostname'] = $host;
        $config['username'] = $db_user;
        $config['password'] = $db_password;
        $config['database'] = $db_name;
        $config['dbdriver'] = 'mysqli';

        $ci->load->database($config);
        $error_db = $ci->db->error();

        if ($error_db['code'] != 0) {
            json([
                'csrf' => $ci->security->get_csrf_hash(),
                'type' => 'error',
                'message' => 'Host data for installation is incorrect.',
            ]);
        }

        $file_sql = 'sql_esjdev_boostpanel.sql';
        $database = file_get_contents($file_sql);

        $sql = explode(';', $database);
        foreach ($sql as $sqls) {
            $statement = $sqls . ";";
            $ci->setup_model->createTable($statement);
        }

        rename($file_sql, "sql" . DIRECTORY_SEPARATOR . rand(0, 9999) . time() . "_sql_esjdev_boostpanel.sql");

        $ci->model->insert(TABLE_USERS, [
            'uuid' => uuid(),
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'timezone' => $timezone,
            'balance' => 0,
            'role' => 'ADMIN',
            'api_key' => create_random_api_key(""),
            'activation_token' => 0,
            'custom_rate' => 0,
            'status' => 'Active',
            'created_at' => NOW,
        ]);

        change_config('mysqli', $host, $db_user, $db_password, $db_name, $timezone);

        $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $url = $protocolo . '://' . $host;

        json([
            'csrf' => $ci->security->get_csrf_hash(),
            'type' => 'success',
            'title' => lang("installed_success"),
            'html' => lang("redirect_install"),
            'language' => "Installing ...",
            'base_url' => $url,
        ]);
    }
}
