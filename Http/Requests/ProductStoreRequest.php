<?php

    namespace Modules\Product\Http\Requests;

    use App\Helpers\LanguageHelper;
    use Illuminate\Foundation\Http\FormRequest;

    class ProductStoreRequest extends FormRequest
    {
        protected $LANGUAGES;

        public function __construct()
        {
            $this->LANGUAGES = LanguageHelper::getActiveLanguages();
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
        public function rules(): array
        {
            $this->trimInput();
            $formatted_data = $this->all();

            $array = [
                'category_id' => 'required',
                //                'measure_unit_id' => ['required', 'integer', Rule::exists('measure_units', 'id')],
                'brand_id'    => 'required',
                //            'supplier_delivery_price' => 'required',
                //                'price'                   => [
                //                    'required',
                //                    function ($attribute, $value, $fail) use ($formatted_data) {
                //                        if (isset($formatted_data['supplier_delivery_price']) && floatval($value) <= floatval($formatted_data['supplier_delivery_price'])) {
                //                            $fail(trans('shop::admin.products.price_greater_than_supplier_delivery_price'));
                //                        }
                //                    }
                //                ],
                //                'units_in_stock'  => ['required', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'weight'      => ['nullable', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'width'       => ['nullable', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'height'      => ['nullable', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'length'      => ['nullable', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            ];
            foreach ($this->LANGUAGES as $language) {
                $array['title_' . $language->code] = 'required';
            }

            return $array;
        }
        public function trimInput(): void
        {
            $trim_if_string = function ($var) {
                return is_string($var) ? trim($var) : $var;
            };
            $formatted_data = array_map($trim_if_string, $this->all());

            if (isset($formatted_data['supplier_delivery_price'])) {
                $formatted_data['supplier_delivery_price'] = str_replace(',', '.', $formatted_data['supplier_delivery_price']);
                $formatted_data['supplier_delivery_price'] = floatval($formatted_data['supplier_delivery_price']);
            }

            if (isset($formatted_data['price'])) {
                $formatted_data['price'] = str_replace(',', '.', $formatted_data['price']);
                $formatted_data['price'] = floatval($formatted_data['price']);
            }

            $this->merge($formatted_data);
        }
        public function messages(): array
        {
            return [
                'weight.regex'             => trans('product::admin.products.weight_regex'),
                'weight.min'               => trans('product::admin.products.weight_min'),
                'weight.max'               => trans('product::admin.products.weight_max'),
                'width.regex'              => trans('product::admin.products.width_regex'),
                'width.min'                => trans('product::admin.products.width_min'),
                'width.max'                => trans('product::admin.products.width_max'),
                'height.regex'             => trans('product::admin.products.height_regex'),
                'height.min'               => trans('product::admin.products.height_min'),
                'height.max'               => trans('product::admin.products.height_max'),
                'length.regex'             => trans('product::admin.products.length_regex'),
                'length.min'               => trans('product::admin.products.length_min'),
                'length.max'               => trans('product::admin.products.length_max'),
                'price.gt'                 => trans('product::admin.products.price_greater_than_supplier_delivery_price'),
                'measure_unit_id.required' => trans('product::admin.products.measure_unit_id_required'),
                'measure_unit_id.integer'  => trans('product::admin.products.measure_unit_id_integer'),
                'measure_unit_id.exists'   => trans('product::admin.products.measure_unit_id_exists'),
            ];
        }
    }
