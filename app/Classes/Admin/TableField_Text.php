<?php
    namespace App\Classes\Admin;

    /**
     * TableField_Text
     */
    class TableField_Text extends TableField
    {
        public $inputType = 'text';
        public $value = '';
        public $placeholder = '';
        public $help = '';
        public $invalidFeedback = '';

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_TEXT, $fieldData);
        }

        public function Render()
        {
            $HTML = '<div class="form-group">';
            $HTML .= '<label for="'.$this->name.'Field" class="col-form-label-lg">'.$this->alias.'</label>';
            
            $HTML .= '<input
                type="'.$this->inputType.'"
                name="ufield_'.$this->name.'"
                id="'.$this->name.'Field"
                class="form-control form-control-lg"
                '.(($this->help != '') ? 'aria-describedby="'.$this->name.'Help"' : '').'
                '.$this->BuildCommonAttributes().'
                '.(($this->min != 0) ? 'minlength="'.$this->min.'"' : '').'
                '.(($this->max != 0) ? 'maxlength="'.$this->max.'"' : '').'
                value="'.$this->value.'"
                >';
            
            $HTML .= $this->RenderHelp();

            $HTML .= '<div class="invalid-feedback-tooltip">'.$this->invalidFeedback.'</div>';

            $HTML .= '</div>';

            return $HTML;
        }

        
    }