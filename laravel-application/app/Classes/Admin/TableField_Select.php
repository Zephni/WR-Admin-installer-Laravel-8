<?php
    namespace App\Classes\Admin;

    use App\Http\Controllers\AdminController;

    /**
     * TableField_Select
     */
    class TableField_Select extends TableField
    {
        public $inputType = 'select';
        public $value = '';
        public $help = '';
        public $invalidFeedback = '';
        public $options = [];

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_SELECT, $fieldData);
            
            // key => value array
            if(is_array($fieldData['options']))
            {
                $this->options = $fieldData['options'];
            }
            // Method returning array
            else if (is_string($fieldData['options']) && substr($fieldData['options'], 0, 4) == 'App\\')
            {
                $this->options = call_user_func(explode('::', $fieldData['options']));
            }
            // join eg. "join ModelName fieldname"
            else if(is_string($fieldData['options']) && strtolower(substr($fieldData['options'], 0, 5)) == 'join ')
            {
                $split = explode(' ', $fieldData['options']);
                $modelName = $split[1];
                $fieldName = $split[2];
                
                $modelRef = AdminController::getModelRef($modelName);
                
                foreach($modelRef::all() as $row)
                    $this->options[$row['id']] = $row[$fieldName];
            }
        }

        public function Render()
        {
            $HTML = '<div class="form-group">';
            $HTML .= '<label for="'.$this->name.'Field" class="col-form-label-lg">'.$this->alias.'</label>';

            // SELECT FIELD
            $HTML .= '<select
                name="ufield_'.$this->name.'"
                class="form-control form-control-lg"
                id="'.$this->name.'Field"
                '.(($this->help != '') ? 'aria-describedby="'.$this->name.'Help"' : '').'
                '.$this->BuildCommonAttributes().'
                >';
            
            $count = 0;
            foreach($this->options as $k => $v)
            {
                if(($this->value === '' && $count == 0) || ($this->value !== '' && $this->value == $k))
                    $HTML .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
                else
                    $HTML .= '<option value="'.$k.'">'.$v.'</option>';

                $count++;
            }

            $HTML .= '';

            $HTML .= '</select>';
            // /SELECT FIELD
            
            $HTML .= $this->RenderHelp();

            $HTML .= '<div class="invalid-feedback-tooltip">'.$this->invalidFeedback.'</div>';

            $HTML .= '</div>';

            return $HTML;
        }
    }