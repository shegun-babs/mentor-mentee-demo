<?php


class Validator
{

    private $rules;
    /**
     * @var array
     */
    private $fields;
    private $submitted;
    /**
     * @var string
     */
    private $inputType;
    private $missing = [];
    /**
     * @var array
     */
    private $attributes;

    public function __construct(array $rules, $attributes=[], $inputType = "post")
    {
        $this->rules = $rules;
        $this->setInputType($inputType);
        $this->fields = array_keys($rules);
        $this->attributes = $attributes;
        $this->run();
    }

    public function run()
    {
        $this->checkRules();
    }

    protected function checkRules()
    {
        foreach ($this->rules as $field => $rulesArray)
        {
            $this->validateRules($field, $rulesArray);
        }
    }

    protected function validateRules($field, array $rules)
    {
        foreach ($rules as $rule)
        {
            //switch (strtolower($rule)):
            switch (true):
                case ( strtolower($rule) == "required"):
                    if ( empty($this->submitted[$field]) && empty($_FILES[$field]['name']) )
                        $this->missing[$field][] = $this->setMessage("The %fieldName% is required", $field);
                    break;
                case ( strtolower($rule) == "string"):
                    if ( ctype_alnum($this->submitted[$field]) !== true )
                        $this->missing[$field][] = $this->setMessage("The %fieldName% must be a string", $field);
                    break;
                case ( strtolower($rule) == "email"):
                    if ( filter_var($this->submitted[$field], FILTER_VALIDATE_EMAIL) === false )
                        $this->missing[$field][] = $this->setMessage("The %fieldName% must be a valid email", $field);
                    break;
                case ( strtolower($rule) == "file"):
                    if ($_FILES[$field]['size'] == 0)
                        $this->missing[$field][] = $this->setMessage("The file %fieldName% is required", $field);
                    break;
                case ( substr(strtolower($rule),0,4) == "size"):
                    $size = explode(":", $rule);
                    if ($_FILES[$field]['size']/1024 > $size[1])
                        $this->missing[$field][] = $this->setMessage(" The %fieldName% cannot be more than {$size[1]}KB", $field);
                    break;
                case ( strtolower($rule) == "image" ):
                    $imageMime = ["image/gif", "image/png", "image/jpeg", "image/jpg"];
                    if (!in_array($_FILES[$field]['type'], $imageMime))
                        $this->missing[$field][] = $this->setMessage("The %fieldName% must be an image of type gif, jpeg, png or jpg", $field);
                    break;
                case ( strtolower($rule) == "date" ):
                    $date = $this->submitted[$field];
                    $format = "D M d Y";
                    $d = DateTime::createFromFormat($format, $date);
                    $d && $d->format($format) === $date
                        ? ''
                        : $this->missing[$field][] = $this->setMessage("The %fieldName% is not a valid date.", $field);
                    break;

            endswitch;
        }
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    private function setInputType(string $inputType)
    {
        switch (strtolower($inputType)) {
            case 'post':
                $this->inputType = 'POST';
                $this->submitted = $_POST;
                break;
            case 'get':
                $this->inputType = 'GET';
                $this->submitted = $_GET;
                break;
            default:
                throw new Exception("Invalid input type. Valid Types are GET and POST");
        }
    }

    public function passed()
    {
        return ! (bool) count($this->missing);
    }

    public function getErrors()
    {
        return $this->missing;
    }

    public function hasError($var){
        return !empty($this->missing[$var]) ? $this->missing[$var] : null ;
    }

    private function setMessage(string $string, $fieldName)
    {
        $fieldName = array_key_exists($fieldName, $this->attributes)
            ? $this->attributes[$fieldName]
            : $fieldName;

        return str_replace("%fieldName%", $fieldName, $string);
    }

    public function validated()
    {
        return $this->submitted;
    }
}