<?php
    namespace App\Classes\Admin;

    /**
     * TableField_Text
     */
    class TableField_DateTime extends TableField
    {
        public $inputType = 'text';
        public $value = '';
        public $placeholder = 'Click to pick a new date and time';
        public $help = '';
        public $invalidFeedback = '';

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_DATETIME, $fieldData);

            if($this->value == '')
                $this->value = (isset($fieldData['default'])) ? $fieldData['default'] : date("Y-m-d H:i:00");
        }

        public function Render()
        {
            $HTML = '<div class="form-group">';
            $HTML .= '<label for="'.$this->name.'Field" class="col-form-label-lg">'.$this->alias.'</label>';

            $HTML .= '<input
                type="'.$this->inputType.'"
                name="ufield_'.$this->name.'"
                id="'.$this->name.'Field"
                class="form-control form-control-lg datetimepicker"
                '.(($this->help != '') ? 'aria-describedby="'.$this->name.'Help"' : '').'
                autocomplete="off"
                '.$this->BuildCommonAttributes().'
                value="'.$this->value.'">';
            
            $HTML .= $this->RenderHelp();

            $HTML .= '<div class="invalid-feedback-tooltip">'.$this->invalidFeedback.'</div>';

            $HTML .= '</div>';

            return $HTML;
        }
    }