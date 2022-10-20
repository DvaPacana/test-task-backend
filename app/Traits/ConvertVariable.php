<?php

namespace App\Traits;

trait ConvertVariable
{
    public function convertVariableToModelName($modelName = '', $nameSpace = '')
    {
        if (is_array($nameSpace)) {
            $nameSpace = implode('\\', $nameSpace);
        }
        if (empty($nameSpace) || is_null($nameSpace) || $nameSpace === "") {
            $modelNameWithNameSpace = "App" . '\\' . $modelName;
        }
        if (is_array($nameSpace)) {
            $modelNameWithNameSpace = $nameSpace . '\\' . $modelName;

        } elseif (!is_array($nameSpace) && !empty($nameSpace) && !is_null($nameSpace) && $nameSpace !== "") {
            $modelNameWithNameSpace = $nameSpace . '\\' . $modelName;
        }
        if (class_exists($modelNameWithNameSpace)) {
            $currentModelWithNameSpace = app($modelNameWithNameSpace);
        } else {
            throw new \Exception("Unable to find Model : $modelName With NameSpace $nameSpace", E_USER_ERROR);
        }
        return $currentModelWithNameSpace;
    }
}
