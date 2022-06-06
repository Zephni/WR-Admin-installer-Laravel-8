<?php
    namespace App\Classes\Admin;

    /**
     * TableField_WYSIWYG
     */
    class TableField_WYSIWYG extends TableField
    {
        public $inputType = 'wysiwyg';
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
            
            $HTML .= '<textarea name="ufield_'.$this->name.'" class="wysiwyg">';

            $HTML .= $this->value;
            
            $HTML .= '</textarea>';

            $HTML .= $this->RenderHelp();
            
            $HTML .= '</div>';

            return $HTML;
        }
    }