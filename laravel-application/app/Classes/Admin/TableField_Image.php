<?php
    namespace App\Classes\Admin;

    /**
     * TableField_Image
     */
    class TableField_Image extends TableField
    {
        public $inputType = 'file';
        public $value = '';
        public $help = '';
        public $invalidFeedback = '';
        public $required = false;
        public $defaultImage = '/images/admin/placeholder-landscape.png';
        
        // Filled in constructor
        public $previewType = '';
        private $layoutClasses = [];

        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_IMAGE, $fieldData);
            
            if(is_string($this->value) && strlen($this->value) > 0 && substr($this->value, 0, 4) != 'http')
            {
                $publicImagePath = str_replace('//', '/', '/images/'.($fieldData['path'] ?? '').'/'.$this->value);

                if(is_file(public_path().$publicImagePath))
                    $this->defaultImage = $publicImagePath;
            }
            else if(substr($this->value, 0, 4) == 'http')
            {
                $this->defaultImage = $this->value; // Remote image
            }

            $this->previewType = $fieldData['previewType'] ?? 'landscape'; // landscape, square

            if($this->previewType == 'landscape')
                $this->layoutClasses = ['col-12 col-md-6 col-lg-4 mb-4 mb-md-0', 'col-12 col-md-6 col-lg-8'];
            else if($this->previewType == 'square')
                $this->layoutClasses = ['col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-sm-0 justify-content-center', 'col-12 col-sm-6 col-md-6 col-lg-7'];
            else if($this->previewType == 'varied')
                $this->layoutClasses = ['col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-sm-0 justify-content-center', 'col-12 col-sm-6 col-md-6 col-lg-7'];
        }

        public function Render()
        {
            $HTML = '<div class="form-group">';

            $HTML .= '<label for="'.$this->name.'Field" class="col-form-label-lg">'.$this->alias.'</label>';

            $HTML .= $this->RenderAlert();

            $HTML .= '
            <div class="row">
                <div class="'.$this->layoutClasses[0].' align-self-center">
                    <div class="'.$this->previewType.'-image-container mx-auto mx-sm-0">
                        <img src="'.$this->defaultImage.'" class="image-preview '.($this->previewType != 'varied' ? 'cover-fit-center' : 'w-100').'">
                    </div>
                </div>

                <div class="'.$this->layoutClasses[1].' align-self-center">
                    <input type="file" name="ufield_'.$this->name.'" id="'.$this->name.'Image" class="file-upload-input d-none" accept="image/jpeg, image/png" />
                    <div class="input-group">
                        <input type="text" class="file-name-display form-control" disabled placeholder="Click \'Browse\' to choose a file">
                        <div class="input-group-append">
                            <button type="button" class="browse btn btn-primary">Browse</button>
                        </div>
                    </div>
                    ';
                    $HTML .= $this->RenderHelp();
            $HTML .= '
                </div>
            </div>
            ';
            
            $HTML .= '<div class="invalid-feedback-tooltip">'.$this->invalidFeedback.'</div>';
            
            $HTML .= '</div>';

            return $HTML;
        }
    }