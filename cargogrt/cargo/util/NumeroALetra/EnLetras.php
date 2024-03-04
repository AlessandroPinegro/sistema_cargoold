<?php

class EnLetras {

    var $Void = "";
    var $SP = " ";
    var $Dot = ".";
    var $Zero = "0";
    var $Neg = "Menos";

    function ValorEnLetras($x, $c = 2) {
        $d = $c * 1 == 2 ? "SOLES" : "DÓLARES";
        $s = "";
        $Ent = "";
        $Frc = "";
        $Signo = "";

        if (floatVal($x) < 0)
            $Signo = $this->Neg . " ";
        else
            $Signo = "";

        if (intval(number_format($x, 2, '.', '')) != $x) //<- averiguar si tiene decimales
            $s = number_format($x, 2, '.', '');
        else
            $s = number_format($x, 0, '.', '');

        $Pto = strpos($s, $this->Dot);

        if ($Pto === false) {
            $Ent = $s;
            $Frc = $this->Void;
        } else {
            $Ent = substr($s, 0, $Pto);
            $Frc = substr($s, $Pto + 1);
        }

        if ($Ent == $this->Zero || $Ent == $this->Void)
            $s = "Cero ";
        elseif (strlen($Ent) > 7) {
            $s = $this->SubValLetra(intval(substr($Ent, 0, strlen($Ent) - 6))) .
                    "Millones " . $this->SubValLetra(intval(substr($Ent, -6, 6)));
        } else {
            $s = $this->SubValLetra(intval($Ent));
        }

        if (substr($s, -9, 9) == "Millones " || substr($s, -7, 7) == "Millón ") {
            $s = $s . "de ";
        }

        //        $s = $s . "Nuevos Soles";

        if ($Frc != $this->Void) {
            //            $s = $s . " Con " . (intval($Frc)) . "/100 Nuevos Soles";
            $s = $s . " Con " . ($Frc) . "/100 $d";
            //$s = $s . " " . $Frc . "/100";
        } else {
            $s = $s . " Con 00/100 $d";
        }

        //        if ($Frc != $this->Void) {
        //            $s = $s . " Con " . $this->SubValLetra(intval($Frc)) . "Centimos";
        //            //$s = $s . " " . $Frc . "/100";
        //        }
        return ($Signo . $s);
    }

    function SubValLetra($numero) {
        $Ptr = "";
        $n = 0;
        $i = 0;
        $x = "";
        $Rtn = "";
        $Tem = "";

        $x = trim("$numero");
        $n = strlen($x);

        $Tem = $this->Void;
        $i = $n;

        while ($i > 0) {
            $Tem = $this->Parte(intval(substr($x, $n - $i, 1) .
                            str_repeat($this->Zero, $i - 1)));
            if ($Tem != "Cero")
                $Rtn .= $Tem . $this->SP;
            $i = $i - 1;
        }


        //--------------------- GoSub FiltroMil ------------------------------
        $Rtn = str_replace(" Mil Mil", " Un Mil", $Rtn);
        while (1) {
            $Ptr = strpos($Rtn, "Mil ");
            if (!($Ptr === false)) {
                if (!(strpos($Rtn, "Mil ", $Ptr + 1) === false))
                    $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
                else
                    break;
            } else
                break;
        }

        //--------------------- GoSub FiltroCiento ------------------------------
        $Ptr = -1;
        do {
            $Ptr = strpos($Rtn, "Cien ", $Ptr + 1);
            if (!($Ptr === false)) {
                $Tem = substr($Rtn, $Ptr + 5, 1);
                if ($Tem == "M" || $Tem == $this->Void)
                    ;
                else
                    $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
            }
        } while (!($Ptr === false));

        //--------------------- FiltroEspeciales ------------------------------
        $Rtn = str_replace("Diez Un", "Once", $Rtn);
        $Rtn = str_replace("Diez Dos", "Doce", $Rtn);
        $Rtn = str_replace("Diez Tres", "Trece", $Rtn);
        $Rtn = str_replace("Diez Cuatro", "Catorce", $Rtn);
        $Rtn = str_replace("Diez Cinco", "Quince", $Rtn);
        $Rtn = str_replace("Diez Seis", "Dieciseis", $Rtn);
        $Rtn = str_replace("Diez Siete", "Diecisiete", $Rtn);
        $Rtn = str_replace("Diez Ocho", "Dieciocho", $Rtn);
        $Rtn = str_replace("Diez Nueve", "Diecinueve", $Rtn);
        $Rtn = str_replace("Veinte Un", "Veintiun", $Rtn);
        $Rtn = str_replace("Veinte Dos", "Veintidos", $Rtn);
        $Rtn = str_replace("Veinte Tres", "Veintitres", $Rtn);
        $Rtn = str_replace("Veinte Cuatro", "Veinticuatro", $Rtn);
        $Rtn = str_replace("Veinte Cinco", "Veinticinco", $Rtn);
        $Rtn = str_replace("Veinte Seis", "Veintiseís", $Rtn);
        $Rtn = str_replace("Veinte Siete", "Veintisiete", $Rtn);
        $Rtn = str_replace("Veinte Ocho", "Veintiocho", $Rtn);
        $Rtn = str_replace("Veinte Nueve", "Veintinueve", $Rtn);

        //--------------------- FiltroUn ------------------------------
        if (substr($Rtn, 0, 1) == "M")
            $Rtn = "Un " . $Rtn;
        //--------------------- Adicionar Y ------------------------------
        for ($i = 65; $i <= 88; $i++) {
            if ($i != 77)
                $Rtn = str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
        }
        $Rtn = str_replace("*", "a", $Rtn);
        return ($Rtn);
    }

    function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr) {
        $x = substr($x, 0, $Ptr) . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
    }

    function Parte($x) {
        $Rtn = '';
        $t = '';
        $i = 0;
        do {
            switch ($x) {
                case 0:
                    $t = "Cero";
                    break;
                case 1:
                    $t = "Un";
                    break;
                case 2:
                    $t = "Dos";
                    break;
                case 3:
                    $t = "Tres";
                    break;
                case 4:
                    $t = "Cuatro";
                    break;
                case 5:
                    $t = "Cinco";
                    break;
                case 6:
                    $t = "Seis";
                    break;
                case 7:
                    $t = "Siete";
                    break;
                case 8:
                    $t = "Ocho";
                    break;
                case 9:
                    $t = "Nueve";
                    break;
                case 10:
                    $t = "Diez";
                    break;
                case 20:
                    $t = "Veinte";
                    break;
                case 30:
                    $t = "Treinta";
                    break;
                case 40:
                    $t = "Cuarenta";
                    break;
                case 50:
                    $t = "Cincuenta";
                    break;
                case 60:
                    $t = "Sesenta";
                    break;
                case 70:
                    $t = "Setenta";
                    break;
                case 80:
                    $t = "Ochenta";
                    break;
                case 90:
                    $t = "Noventa";
                    break;
                case 100:
                    $t = "Cien";
                    break;
                case 200:
                    $t = "Doscientos";
                    break;
                case 300:
                    $t = "Trescientos";
                    break;
                case 400:
                    $t = "Cuatrocientos";
                    break;
                case 500:
                    $t = "Quinientos";
                    break;
                case 600:
                    $t = "Seiscientos";
                    break;
                case 700:
                    $t = "Setecientos";
                    break;
                case 800:
                    $t = "Ochocientos";
                    break;
                case 900:
                    $t = "Novecientos";
                    break;
                case 1000:
                    $t = "Mil";
                    break;
                case 1000000:
                    $t = "Millón";
                    break;
            }

            if ($t == $this->Void) {
                $i = $i + 1;
                $x = $x / 1000;
                if ($x == 0)
                    $i = 0;
            } else
                break;
        } while ($i != 0);

        $Rtn = $t;
        switch ($i) {
            case 0:
                $t = $this->Void;
                break;
            case 1:
                $t = " Mil";
                break;
            case 2:
                $t = " Millones";
                break;
            case 3:
                $t = " Billones";
                break;
        }
        return ($Rtn . $t);
    }

    static public function convertir($n) {
        switch (true) {
            case ($n >= 1 && $n <= 29):
                return basico($n, null);
                break;
            case ($n >= 30 && $n < 100):
                return decenas($n, null);
                break;
            case ($n >= 100 && $n < 1000):
                return centenas($n, null);
                break;
            case ($n >= 1000 && $n <= 999999):
                return miles($n);
                break;
            case ($n >= 1000000):
                return millones($n);
        }
    }

    function digitosALetras($valor) {
        $cadena = str_split($valor);
        $letras = '';
        foreach ($cadena as $valor) {
            switch ($valor) {
                case 0:
                    $letras=$letras.'A';
                    break;
                case 1:
                    $letras=$letras.'B';
                    break;
                case 2:
                    $letras=$letras.'C';
                    break;
                case 3:
                    $letras=$letras.'D';
                    break;
                case 4:
                    $letras=$letras.'E';
                    break;
                case 5:
                    $letras=$letras.'F';
                    break;
                case 6:
                    $letras=$letras.'G';
                    break;
                case 7:
                    $letras=$letras.'H';
                    break;
                case 8:
                    $letras=$letras.'I';
                    break;
                case 9:
                    $letras=$letras.'J';
                    break;
            }
        }
        return $letras;
    }

}

function basico($numero, $num2) {
    $num = (int) $num2;
    if ($num == 1) {
        $valor = array(
            'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO',
            'NUEVE', 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO',
            'DIECINUEVE', 'VEINTE', 'VEINTIUNO', 'VEINTIDOS', 'VEINTITRES', 'VEINTICUATRO', 'VEINTICINCO',
            'VEINTISEIS', 'VEINTISIETE', 'VEINTIOCHO', 'VEINTINUEVO'
        );
    } else {
        $valor = array(
            'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO',
            'NUEVE', 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO',
            'DIECINUEVE', 'VEINTE', 'VEINTIUNO', 'VEINTIDOS', 'VEINTITRES', 'VEINTICUATRO', 'VEINTICINCO',
            'VEINTISEIS', 'VEINTISIETE', 'VEINTIOCHO', 'VEINTINUEVO'
        );
    }
    return $valor[$numero - 1];
}

function decenas($n, $nn) {
    $decenas = array(
        30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA', 60 => 'SESENTA',
        70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA'
    );
    if ($n <= 29)
        return basico($n, $nn);
    $x = $n % 10;
    if ($x == 0) {
        return $decenas[$n];
    } else
        return $decenas[$n - $x] . ' Y ' . basico($x, null);
}

function centenas($n, $dd) {
    $cientos = array(
        100 => 'CIEN', 200 => 'DOSCIENTOS', 300 => 'TRECIENTOS',
        400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS', 600 => 'SEISCIENTOS',
        700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS'
    );
    if ($n >= 100) {
        if ($n % 100 == 0) {
            return $cientos[$n];
        } else {
            $u = (int) substr($n, 0, 1);
            $d = (int) substr($n, 1, 2);
            $ddd = (int) substr($n, 2, 1);
            if ($ddd == 1) {
                $ddd = 1;
            } else {
                $ddd = $dd;
            }

            return (($u == 1) ? 'CIENTO' : $cientos[$u * 100]) . ' ' . decenas($d, $ddd);
        }
    } else
        return decenas($n, null);
}

function miles($n) {
    if ($n > 999) {
        if ($n == 1000) {
            return 'MIL';
        } else {
            $l = strlen($n);
            $c = (int) substr($n, 0, $l - 3);
            $x = (int) substr($n, -3);
            $xx = (int) substr($n, -4, 1);
            if ($c == 1) {
                $cadena = 'MIL ' . centenas($x, $xx);
            } else if ($x != 0) {
                $cadena = centenas($c, $xx) . ' MIL ' . centenas($x, $xx);
            } else
                $cadena = centenas($c, $xx) . ' MIL';
            return $cadena;
        }
    } else
        return centenas($n, 2);
}

function millones($n) {
    if ($n == 1000000) {
        return 'UN MILLON';
    } else {
        $l = strlen($n);
        $c = (int) substr($n, 0, $l - 6);
        $x = (int) substr($n, -6);
        if ($c == 1) {
            $cadena = ' MILLON ';
        } else {
            $cadena = ' MILLON ';
        }
        return miles($c) . $cadena . (($x > 0) ? miles($x) : '');
    }
}
