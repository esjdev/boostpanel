<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Setlanguage extends MY_Controller
{
    protected $table_lang_list;

    public function __construct()
    {
        parent::__construct();

        $this->table_lang_list = TABLE_LANG_LIST;

        $this->load->model("panel/language/language_model");
    }

    public function set_language($ids)
    {
        $checkLang = $this->model->fetch('*', $this->table_lang_list, ['status' => '1', 'ids' => $ids]);

        if (!empty($checkLang)) {
            unset_session('langCurrent');
            set_session('langCurrent', $checkLang);
        }

        redirect(base_url());
    }

    public function no_select_language()
    {
        if (lang_code_defaut())
            unset_session('langCurrent');

        redirect(base_url());
    }
}
