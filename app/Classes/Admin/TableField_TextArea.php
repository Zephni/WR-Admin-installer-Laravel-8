<?php
    namespace App\Classes\Admin;

    /**
     * TableField_TextArea
     */
    class TableField_TextArea extends TableField
    {
        public $inputType = 'textarea';
        public $value = '';
        public $placeholder = '';
        public $help = '';

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_TEXTAREA, $fieldData);
        }

        public function Render()
        {
            $HTML = '<div class="form-group">';
            $HTML .= '<label for="'.$this->name.'Field" class="col-form-label-lg">'.$this->alias.'</label>';
            
            $HTML .= '<textarea
                name="ufield_'.$this->name.'"
                id="'.$this->name.'Field"
                class="form-control form-control-lg"
                '.(($this->help != '') ? 'aria-describedby="'.$this->name.'Help"' : '').' 
                rows="8"
                '.$this->BuildCommonAttributes().'
                >';

            $HTML .= $this->value;
            $HTML .= '</textarea>';

            $HTML .= $this->RenderHelp();
            
            $HTML .= '</div>';

            return $HTML;
        }
    }