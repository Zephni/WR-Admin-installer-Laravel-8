<?php
    namespace App\Classes\Admin;

    /**
     * TableField_HTML
     */
    class TableField_HTML extends TableField
    {
        public function __construct(string $name, array $fieldData)
        {
            parent::__construct($name, TableField::TYPE_TEXT, $fieldData);
        }

        public function Render()
        {
            return $this->value;
        }
    }