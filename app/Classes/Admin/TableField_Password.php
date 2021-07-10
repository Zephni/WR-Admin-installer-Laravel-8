<?php
    namespace App\Classes\Admin;

    /**
     * TableField_Password
     */
    class TableField_Password extends TableField
    {
        public $inputType = 'password';
        public $value = '';
        public $placeholder = '';
        public $help = '';
        public $invalidFeedback = '';
        public $confirmation = false;

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_TEXT, $fieldData);

            $this->confirmation = $fieldData['confirmation'] ?? false;
        }

        public function Render()
        {
            $HTML = '<div class="form-group '.($this->confirmation ? 'password-confirmation' : '').'">';
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
                >';
            
            $HTML .= $this->RenderHelp();

            $HTML .= '<div class="invalid-feedback-tooltip">'.$this->invalidFeedback.'</div>';

            $HTML .= '</div>';

            return $HTML;
        }

        
    }