<?php

namespace Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'chart_of_account_id' => [
                'required', 'integer',
                Rule::exists('chart_of_accounts', 'id')->where(function ($query) {
                    return $query->where('status', 1)->where('type', 'Income');
                })
            ],
            'payment_method' => [
                'required', 'string', 'max:50',
                Rule::in(get_account_var('list')['payment_method'])
            ],
            'bank_account_id' => [
                'required_if:payment_method,Bank', 'nullable', 'integer',
                Rule::exists('bank_accounts', 'id')->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'title' => ['required', 'string', 'max:191'],
            'amount' => ['required', 'numeric'],
            'transaction_date' => ['required', 'date'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'file' => ['sometimes', 'nullable', 'file']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => trans('common.Incomes'),
            'chart_of_account_id' => trans('chart_of_account.Income Account'),
            'bank_account_id' => trans('bank_account.Bank Accounts'),
            'payment_method' => trans('chart_of_account.Payment Method'),
            'amount' => trans('account.Amount'),
            'transaction_date' => trans('account.Transaction date'),
            'description' => trans('common.Description'),
        ];
    }
}
