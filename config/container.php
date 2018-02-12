<?php

return [
    'definitions' => [
        yii\data\Pagination::className() => [
            'pageSize' => 5,
        ],
        kartik\number\NumberControl::className() => [
            'maskedInputOptions' => [
                'prefix' => '',
                'suffix' => ' €',
                //'allowMinus' => false,
                'groupSeparator' => '.',
                'radixPoint' => ',',
            ],
        ],
        \kartik\daterange\DateRangePicker::className() => [
            'readonly' => true,
            'autoUpdateOnInit' => false,
            'pluginOptions' => [
                'locale' => [
                    'format' => 'DD-MM-YYYY',
                    'cancelLabel' => 'Limpiar',
                ],
            ],
            'pluginEvents' => [
                'cancel.daterangepicker' => "function (ev, picker) {
                    $(picker.element[0]).val('').trigger('change');
                }",
            ],
        ],
    ],
];
