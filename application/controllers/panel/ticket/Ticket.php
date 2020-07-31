<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends MY_Controller
{
    protected $table;
    protected $table_ticket_messages;
    protected $table_user;
    private $user;

    public function __construct()
    {
        parent::__construct();

        if (!logged()) return redirect(base_url());

        $this->table = TABLE_TICKETS;
        $this->table_ticket_messages = TABLE_TICKET_MESSAGES;
        $this->table_user = TABLE_USERS;

        $this->load->model("panel/ticket/ticket_model");
        $this->user = $this->model->get('*', $this->table_user, ['email' => logged()], '', '', true);
    }

    public function index()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        $this->load->library('pagination');

        $config = [
            'base_url' => base_url("tickets"),
            'total_rows' => $this->model->counts($this->table, ['user_id' => $this->user['id']]),
            'per_page' => 10,
            'uri_segment' => 2,
            'use_page_numbers' => true,
            'reuse_query_string' => false,

            'first_link' => lang("pagination_first"),
            'last_link' => lang("pagination_last"),
            'full_tag_open' => '<div class="pagging text-center"><nav><ul class="pagination pagination-sm justify-content-center">',
            'full_tag_close' => '</ul></nav></div>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tagl_close' => '<span aria-hidden="true">&raquo;</span></li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tagl_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tagl_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tagl_close' => '</li>',
            'attributes' => ['class' => 'page-link'],
        ];

        $this->pagination->initialize($config);
        $page = (uri(2)) ? uri(2) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        $data = [
            'title' => lang("title_tickets"),
            'get_ticket_user' => $this->model->fetch('*', $this->table, ['user_id' => $this->user['id']], 'created_at', 'desc', $offset, $config["per_page"]),
            'pagination_links' => $this->pagination->create_links(),
        ];

        view('layouts/auth_header', $data);
        view('panel/ticket/tickets');
        view('layouts/auth_footer');

        $this->store(); // Insert ticket
    }

    public function index_admin()
    {
        if (userLevel(logged(), 'user')) return redirect(base_url());

        $this->load->library('pagination');

        $config = [
            'base_url' => base_url("admin/tickets"),
            'total_rows' => $this->model->counts($this->table),
            'per_page' => 10,
            'uri_segment' => 3,
            'use_page_numbers' => true,
            'reuse_query_string' => false,

            'first_link' => lang("pagination_first"),
            'last_link' => lang("pagination_last"),
            'full_tag_open' => '<div class="pagging text-center"><nav><ul class="pagination pagination-sm justify-content-center">',
            'full_tag_close' => '</ul></nav></div>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tagl_close' => '<span aria-hidden="true">&raquo;</span></li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tagl_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tagl_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tagl_close' => '</li>',
            'attributes' => ['class' => 'page-link'],
        ];

        $this->pagination->initialize($config);
        $page = (uri(3)) ? uri(3) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $config["per_page"];

        $data = [
            'title' => lang("title_tickets"),
            'get_all_ticket' => $this->model->fetch('*', $this->table, '', 'created_at', 'desc', $offset, $config["per_page"]),
            'pagination_links' => $this->pagination->create_links(),
        ];

        view('layouts/auth_header', $data);
        view('panel/ticket/tickets_admin');
        view('layouts/auth_footer');
    }

    public function store()
    {
        if (!userLevel(logged(), 'user')) return redirect(base_url());

        if (isset($_POST) && !empty($_POST)) {
            $subject = $this->input->post('subject_ticket', true);
            $message = $this->input->post('message', true);
            $order = $this->input->post("order_id", true);
            $payment = $this->input->post("trans_id_payment", true);

            $this->ticket_model->validate_form();

            if ($this->form_validation->run() == false) {
                foreach ($this->form_validation->error_array() as $key => $value) {
                    json([
                        'csrf' => $this->security->get_csrf_hash(),
                        'type' => 'error',
                        'message' => form_error($key, false, false),
                    ]);
                }
            }

            if (empty($order) && $subject == 'order') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_ticket_select_order"),
                ]);
            }

            if (empty($payment) && $subject == 'payment') {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => lang("error_ticket_select_payment"),
                ]);
            }

            if ($subject == 'payment') {
                $subject_other = $payment;
            }

            if ($subject == 'order') {
                $subject_other = $order;
            }

            if (!in_array($subject, ['payment', 'order'])) {
                $subject_other = 0;
            }

            $data = [
                'uuid' => uuid(),
                'subject' => $subject,
                'description' => $message,
                'user_id' => $this->user['id'],
                'pay_or_order_id' => $subject_other,
                'created_at' => NOW,
                'updated_at' => NOW,
            ];
            $this->model->insert($this->table, $data);

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'message' => lang("success_ticket"),
            ]);
        }
    }

    public function search()
    {
        if (isset($_POST) and !empty($_POST)) {
            $search = $this->input->post('searchTickets');

            $data = [
                'search_tickets' => $this->model->search($this->table, 'created_at', 'desc', ['id' => $search]),
                'search_tickets_user' => $this->model->search($this->table, 'created_at', 'desc', ['id' => $search], '', ['user_id' => $this->user['id']]),
            ];

            if (userLevel(logged(), 'admin') || userLevel(logged(), 'support')) {
                view('panel/ticket/get_tickets_all', $data);
            } else {
                view('panel/ticket/get_tickets_user', $data);
            }
        }
    }

    public function show($uuid)
    {
        $count_ticket = $this->model->counts($this->table, ['uuid' => $uuid, 'user_id' => $this->user['id']]);
        $ticket_status = $this->model->counts($this->table, ['uuid' => $uuid, 'status' => 'closed']);

        if ($count_ticket != 0 || userLevel(logged(), 'admin') || userLevel(logged(), 'support')) {
            $data = [
                'title' => lang("title_viewing_ticket"),
                'get_ticket' => $this->model->fetch('*', $this->table, ['uuid' => $uuid]),
                'get_message_ticket' => $this->model->fetch('*', $this->table_ticket_messages, ['uuid' => $uuid], 'created_at', 'desc'),
                'ticket_status' => $ticket_status,
            ];

            view('layouts/auth_header', $data);
            view('panel/ticket/show_ticket');
            view('layouts/auth_footer');

            if ($ticket_status == 0) return $this->save_ticket_message($this->user['id'], $uuid);
        } else {
            redirect(base_url());
        }
    }

    public function save_ticket_message($user_id, $uuid)
    {
        if (isset($_POST) && !empty($_POST)) {
            $message = $this->input->post('message', true);

            if (strlen($message) < 1) {
                json([
                    'csrf' => $this->security->get_csrf_hash(),
                    'type' => 'error',
                    'message' => sprintf(lang("error_length_message"), 1),
                ]);
            }

            $ticket = $this->model->get('*', $this->table, ['uuid' => $uuid], '', '', true);
            $user = $this->model->get('*', $this->table_user, ['id' => $ticket['user_id']], '', '', true);

            if (notification('notification_ticket', 'value') == 1 && userLevel(logged(), 'admin') || userLevel(logged(), 'support')) {
                $subject = (email_tpl('notification_ticket_reply_subject', 'value') == '') ? email_template('notification_ticket_reply')->subject : email_tpl('notification_ticket_reply_subject', 'value');
                $subject = str_replace("{{app_name}}", configs('app_title', 'value'), $subject);
                $email_content = (email_tpl('notification_ticket_reply_content', 'value') == '') ? email_template('notification_ticket_reply')->content : email_tpl('notification_ticket_reply_content', 'value');
                $email_content = str_replace("{{app_name}}", configs('app_title', 'value'), $email_content);
                $email_content = str_replace("{{username}}", $user['username'], $email_content);
                $email_content = str_replace("{{link_ticket}}", base_url('ticket/show/' . $uuid), $email_content);

                email_send($user['email'], $subject, $email_content);
            }

            $data = [
                'uuid' => $ticket['uuid'],
                'content' => $message,
                'user_id' => $user_id,
                'created_at' => convert_time(NOW, $user['timezone']),
                'updated_at' => convert_time(NOW, $user['timezone']),
            ];
            $this->model->insert($this->table_ticket_messages, $data);

            if ($user_id != $ticket['user_id']) {
                $this->model->update($this->table, ['uuid' => $uuid], ['status' => 'answered', 'updated_at' => convert_time(NOW, $user['timezone'])]);
            } else {
                $this->model->update($this->table, ['uuid' => $uuid], ['status' => 'pending', 'updated_at' => convert_time(NOW, $user['timezone'])]);
            }

            json([
                'csrf' => $this->security->get_csrf_hash(),
                'type' => 'success',
                'base_url' => base_url('ticket/show/' . $uuid),
            ]);
        }
    }

    public function close_ticket($uuid)
    {
        if (userLevel(logged(), 'admin') || userLevel(logged(), 'support'))
            $this->model->update($this->table, ['uuid' => $uuid], ['status' => 'closed']);

        redirect(base_url('ticket/show/' . $uuid));
    }

    public function destroy_ticket($uuid)
    {
        if (userLevel(logged(), 'admin')) {
            $this->model->delete($this->table, ['uuid' => $uuid]);
            $this->model->delete($this->table_ticket_messages, ['uuid' => $uuid]);
        }
        redirect(base_url('admin/tickets'));
    }

    /**
     * Callbacks errors form validation
     */
    public function select_validation($var, $message)
    {
        if ($var == 'not') {
            $this->form_validation->set_message('select_validation', $message);
            return false;
        } else {
            return true;
        }
    }
}
