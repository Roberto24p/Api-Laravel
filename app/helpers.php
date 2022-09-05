<?php

function getAge($bornDate)
{
    $nacimiento = new DateTime($bornDate);

    $now = new DateTime(date('Y-m-d'));

    $dif = $now->diff($nacimiento);
    return $dif->format('%y');
}

function setRange($age, $ranges){
    $type = '';
    foreach($ranges as $range){
        if($age <= $range->max && $age >= $range->min )
            $type = $range->name;
    }
    return $type;
}
function fechaLatinoShort($fecha){
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
  
    $meses_ES = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return "$nombreMes $numeroDia, $anio";
  }
function validarCI($strCedula)
{
    if (is_null($strCedula) || empty($strCedula)) { //compruebo si que el numero enviado es vacio o null
        return 0;
    } else { //caso contrario sigo el proceso
        if (is_numeric($strCedula)) {
            $total_caracteres = strlen($strCedula); // se suma el total de caracteres
            if ($total_caracteres == 10) { //compruebo que tenga 10 digitos la cedula
                $nro_region = substr($strCedula, 0, 2); //extraigo los dos primeros caracteres de izq a der
                if ($nro_region >= 1 && $nro_region <= 24) { // compruebo a que region pertenece esta cedula//
                    $ult_digito = substr($strCedula, -1, 1); //extraigo el ultimo digito de la cedula
                    //extraigo los valores pares//
                    $valor2 = substr($strCedula, 1, 1);
                    $valor4 = substr($strCedula, 3, 1);
                    $valor6 = substr($strCedula, 5, 1);
                    $valor8 = substr($strCedula, 7, 1);
                    $suma_pares = ($valor2 + $valor4 + $valor6 + $valor8);
                    //extraigo los valores impares//
                    $valor1 = substr($strCedula, 0, 1);
                    $valor1 = ($valor1 * 2);
                    if ($valor1 > 9) {
                        $valor1 = ($valor1 - 9);
                    } else {
                    }
                    $valor3 = substr($strCedula, 2, 1);
                    $valor3 = ($valor3 * 2);
                    if ($valor3 > 9) {
                        $valor3 = ($valor3 - 9);
                    } else {
                    }
                    $valor5 = substr($strCedula, 4, 1);
                    $valor5 = ($valor5 * 2);
                    if ($valor5 > 9) {
                        $valor5 = ($valor5 - 9);
                    } else {
                    }
                    $valor7 = substr($strCedula, 6, 1);
                    $valor7 = ($valor7 * 2);
                    if ($valor7 > 9) {
                        $valor7 = ($valor7 - 9);
                    } else {
                    }
                    $valor9 = substr($strCedula, 8, 1);
                    $valor9 = ($valor9 * 2);
                    if ($valor9 > 9) {
                        $valor9 = ($valor9 - 9);
                    } else {
                    }

                    $suma_impares = ($valor1 + $valor3 + $valor5 + $valor7 + $valor9);
                    $suma = ($suma_pares + $suma_impares);
                    $dis = substr($suma, 0, 1); //extraigo el primer numero de la suma
                    $dis = (($dis + 1) * 10); //luego ese numero lo multiplico x 10, consiguiendo asi la decena inmediata superior
                    $digito = ($dis - $suma);
                    if ($digito == 10) {
                        $digito = '0';
                    } else {
                    } //si la suma nos resulta 10, el decimo digito es cero
                    if ($digito == $ult_digito) { //comparo los digitos final y ultimo
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}

