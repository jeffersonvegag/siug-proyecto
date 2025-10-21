<?php
// Archivo: app/helpers/Validator.php

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $fieldName => $ruleSet) {
            $value = trim($data[$fieldName] ?? '');

            // Itera sobre cada regla aplicada a un campo (required, numeric, etc.)
            foreach ($ruleSet as $rule => $ruleValue) {
                
                // Si ya se encontró un error para este campo, no se aplican más reglas.
                if (isset($errors[$fieldName])) {
                    continue;
                }

                // Si un campo no es requerido y está vacío, no se validan otras reglas.
                if ($rule !== 'required' && empty($value)) {
                    continue;
                }

                // El 'switch' maneja cada tipo de regla
                switch ($rule) {
                    case 'required':
                        if ($ruleValue === true && empty($value)) {
                            $errors[$fieldName] = $ruleSet['message'] ?? 'Este campo es obligatorio.';
                        }
                        break;

                    case 'numeric':
                        if ($ruleValue === true && !is_numeric($value)) {
                            $errors[$fieldName] = $ruleSet['message'] ?? 'Este campo debe ser numérico.';
                        }
                        break;
                    
                    case 'email':
                        if ($ruleValue === true && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                            $errors[$fieldName] = $ruleSet['message'] ?? 'El formato del correo no es válido.';
                        }
                        break;
                    
                    case 'range':
                        $options = ['options' => ['min_range' => $ruleSet['min'], 'max_range' => $ruleSet['max']]];
                        if (filter_var($value, FILTER_VALIDATE_INT, $options) === false) {
                            $errors[$fieldName] = $ruleSet['message'] ?? "El valor debe estar entre {$ruleSet['min']} y {$ruleSet['max']}.";
                        }
                        break;

                    case 'url':
                        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                            $errors[$fieldName] = $ruleSet['message'] ?? 'El formato de la URL no es válido.';
                        }
                        break;
                }
            }
        }
        return $errors;
    }
}
?>