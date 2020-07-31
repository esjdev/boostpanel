<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chartjs_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function chartjs_model($table, $status = "")
    {
        $date_list = [];
        $date = strtotime(date('Y-m-d', strtotime(NOW)));

        $user = dataUser(logged(), 'id');

        if (!userLevel(logged(), 'admin')) {
            $sql = "SELECT COUNT(created_at) as count, DATE(created_at) as created FROM `$table` WHERE status = '$status' AND service_type != 'subscriptions' AND user_id = '$user' AND created_at > NOW() - INTERVAL 7 DAY GROUP BY DATE(created_at), status;";
        } else {
            $sql = "SELECT COUNT(created_at) as count, DATE(created_at) as created FROM `$table` WHERE status = '$status' AND service_type != 'subscriptions' AND created_at > NOW() - INTERVAL 7 DAY GROUP BY DATE(created_at), status;";
        }

        for ($i = 7; $i >= 0; $i--) {
            $left_date = $date - 86400 * $i;
            $date_list[date('Y-m-d', $left_date)] = 0;
        }

        $query = $this->db->query($sql);

        if ($query->result()) {
            foreach ($query->result() as $key => $value) {
                if (isset($date_list[$value->created])) {
                    $date_list[$value->created] = $value->count;
                }
            }
        }

        $data_value = [];
        $data_date = [];

        foreach ($date_list as $date => $value) {
            $data_value[] = $value;
            $data_date[]  = $date;
        }

        $data = (object) [
            "value" => $data_value,
            "date" => $data_date
        ];

        return $data;
    }

    public function chartjs()
    {
        $data_orders_chart = [
            "time" => $this->chartjs_model(TABLE_ORDERS, 'completed')->date,
            "completed"    => $this->chartjs_model(TABLE_ORDERS, 'completed')->value,
            "processing" => $this->chartjs_model(TABLE_ORDERS, 'processing')->value,
            "pending" => $this->chartjs_model(TABLE_ORDERS, 'pending')->value,
            "in progress" => $this->chartjs_model(TABLE_ORDERS, 'inprogress')->value,
            "partial" => $this->chartjs_model(TABLE_ORDERS, 'partial')->value,
            "canceled" => $this->chartjs_model(TABLE_ORDERS, 'canceled')->value,
        ];

        $data_orders_chart = json_encode($data_orders_chart);

        $data_orders_chart = str_replace("completed", lang("status_completed"), $data_orders_chart);
        $data_orders_chart = str_replace("processing", lang("status_processing"), $data_orders_chart);
        $data_orders_chart = str_replace("in progress", lang("status_inprocess"), $data_orders_chart);
        $data_orders_chart = str_replace("pending", lang("status_pending"), $data_orders_chart);
        $data_orders_chart = str_replace("partial", lang("status_partial"), $data_orders_chart);
        $data_orders_chart = str_replace("canceled", lang("status_canceled"), $data_orders_chart);

        return $data_orders_chart;
    }
}
