<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validation Form Service Via Api
     */
    public function validation_form_service_via_api()
    {
        $this->form_validation(
            [
                [
                    'field' => 'name_service',
                    'label' => lang("input_name"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
                [
                    'field' => 'category_service',
                    'label' => lang("menu_category"),
                    'rules' => 'numeric|callback_select_validation[' . lang("error_no_select_category") . ']',
                    'errors' => [
                        'numeric' => lang("error_only_numbers"),
                    ],
                ],
                [
                    'field' => 'price_service',
                    'label' => lang("price_per_1k"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
            ]
        );
    }

    /**
     * Validation Form Edit Service only API
     * @param array $field
     */
    public function validation_form_service_api()
    {
        $this->form_validation(
            [
                [
                    'field' => 'edit_package_name',
                    'label' => lang("input_name"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
                [
                    'field' => 'category_service',
                    'label' => lang("menu_category"),
                    'rules' => 'numeric|callback_select_validation[' . lang("error_no_select_category") . ']',
                    'errors' => [
                        'numeric' => lang("error_only_numbers"),
                    ],
                ],
                [
                    'field' => 'edit_price_amount',
                    'label' => lang("price_per_1k"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
            ]
        );
    }

    /**
     * Validation Form Create or Edit Service
     * @param array $field
     */
    public function validation_form_service($field = [])
    {
        $this->form_validation(
            [
                [
                    'field' => $field[0],
                    'label' => lang("package_name"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
                [
                    'field' => $field[1],
                    'label' => lang("menu_category"),
                    'rules' => 'numeric|callback_select_validation[' . lang("error_no_select_category") . ']',
                    'errors' => [
                        'numeric' => lang("error_only_numbers"),
                    ],
                ],
                [
                    'field' => $field[2],
                    'label' => lang("min"),
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                        'numeric' => lang("error_only_numbers"),
                    ],
                ],
                [
                    'field' => $field[3],
                    'label' => lang("max"),
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                        'numeric' => lang("error_only_numbers"),
                    ],
                ],
                [
                    'field' => $field[4],
                    'label' => lang("price"),
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang("error_empty_field"),
                    ],
                ],
            ]
        );
    }
}
