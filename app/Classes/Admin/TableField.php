<?php
    namespace App\Classes\Admin;

    use Illuminate\Support\Str;

    /**
     * TableField
     */
    class TableField
    {
        const TYPE_TEXT         = 0;
        const TYPE_TEXTAREA     = 1;
        const TYPE_IMAGE        = 2;
        const TYPE_DATETIME     = 3;
        const TYPE_SELECT       = 4;
        
        public $name;
        public $alias;
        public $type;

        public $inputType = '';     // eg. text, image
        public $value = '';         // eg. "An amazing title", "article1.jpg"
        public $placeholder = '';   // eg. "Article title"
        public $help = '';          // eg. "A title is best kept short and catchy"
        public $alert = '';         // eg. "ALERT! Something import is new about this field!"
        
        // Attributes
        public $attributes = [];

       // Validations
        public $required = false;
        public $min = 0;
        public $max = 0;

        public function __construct(string $name, int $type, array $fieldData)
        {
            // Obligatory
            $this->name = $name;
            $this->alias = $this->PrettifyFieldName($name);
            $this->type = $type;

            if(isset($fieldData['default']))
                $this->value = $fieldData['default'];

            // Attributes
            if(isset($fieldData['attributes']) && is_array($fieldData['attributes']))
                $this->attributes = $fieldData['attributes'];

            // Get validation as array
            $validation = [];
            if(array_key_exists('validation', $fieldData))
            {
                $temp = explode('|', $fieldData['validation']);
                foreach($temp as $item)
                {
                    if(strstr($item, ':') !== false)
                    {
                        $item = explode(':', $item);
                        $validation[$item[0]] = $item[1];
                    }
                    else
                    {
                        $validation[$item] = true;
                    }
                }
            }

            // Set help
            if(array_key_exists('help', $fieldData) && $fieldData['help'])
                $this->help = $fieldData['help'];
            
            // Set alert
            if(array_key_exists('alert', $fieldData) && $fieldData['alert'])
                $this->alert = $fieldData['alert'];

            // Check if required    
            if(array_key_exists('required', $validation) && $validation['required'] == true)
                $this->required = true;
            
            // Check if between
            if(array_key_exists('between', $validation))
            {
                $minMax = explode(',', $validation['between']);
                $this->min = (int)$minMax[0];
                $this->max = (int)$minMax[1];
            }

            // Check if max
            if(array_key_exists('max', $validation))
                $this->max = (int)$validation['max'];

            // Fill value if old value exists (invalidated form)
            if(old('ufield_'.$this->name) !== null)
                $this->value = old('ufield_'.$this->name);
        }

        /**
        * PrettifyFieldName
        *
        * @param string $tableName
        * @param ?bool $plural
        * @return string
        */
        private function PrettifyFieldName(string $tableName, ?bool $plural = null) : string
        {
            $string = $tableName;
            $string = ucfirst($string);
            $string = str_replace('_', ' ', $string);

            if(substr($string, -3, 3) == '_id')
                $string = substr_replace($string, '_id', -3, 3);

            if($plural !== null)
            {
                $string = ($plural) ? Str::of($string)->plural() : Str::of($string)->singular();
            }

            return (string)$string;
        }

        /**
         * RenderHelp
         * 
         * @return string (HTML)
         */
        public function RenderHelp()
        {
            $HTML = '';

            if($this->required)
                $this->help = '<b class="text-primary">Required</b>'.(($this->help != '') ? ' - '.$this->help : '');

            if($this->help != '')
            {
                $HTML .= '<div id="'.$this->name.'Help" class="form-text text-muted alert alert-info">';

                $HTML .= $this->help;
                $HTML .= '</div>';
            }
            
            return $HTML;
        }

        public function RenderAlert()
        {
            if($this->alert == '')
                return '';

            $HTML = '<div class="d-block w-100 mb-2">';
            $HTML .= '<div class="alert alert-danger">';
            $HTML .= $this->alert;
            $HTML .= '</div>';
            $HTML .= '</div>';
            
            return $HTML;
        }

        protected function BuildCommonAttributes()
        {
            $attributesString = '';

            foreach($this->attributes as $k => $v)
            {
                if(is_int($k))
                    $attributesString .= $v.'="'.$v.'" ';
                else
                    $attributesString .= $k.'="'.$v.'" ';
            }

            return rtrim($attributesString, ' ');
        }
    }