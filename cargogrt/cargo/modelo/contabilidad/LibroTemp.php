<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class LibroTemp extends ModeloBase {

    /**
     * 
     * @return LibroTemp
     */
    static function create() {
        return parent::create();
    }

    public function listar() {
        $this->commandPrepare("sp_libro_temp_listar");
        return $this->commandGetData();
    }

    public function guardar($excelNombre, $anio, $mes, $usuarioId){
        $this->commandPrepare("sp_libro_temp_insert");
        $this->commandAddParameter(":vin_excel_nombre", $excelNombre);
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function actualizar($id, $txtNombre, $estado, $parametrosSaldos = NULL, $contVoucherId = NULL) {
        $this->commandPrepare("sp_libro_temp_actualizar");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_txt_nombre", $txtNombre);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_parametros_saldos", $parametrosSaldos);
        $this->commandAddParameter(":vin_cont_voucher_id", $contVoucherId);
        return $this->commandGetData();
    }

    public function eliminar8() {
        $this->commandPrepare("sp_libro8_eliminar");
        return $this->commandGetData();
    }

    public function listar8() {
        $this->commandPrepare("sp_libro8_listar");
        return $this->commandGetData();
    }

    public function guardar8($periodo, $cuo, $correlativo, $fecha_emision, $comprobante_tipo, $serie, $numero, $valor, $conceptos_otros, $total, $comprobante_tipo_fiscal, $serie_fiscal, $dua_anio, $comprobante_fiscal_numero, $igv, $moneda, $tipo_cambio, $pais, $proveedor_nombre, $proveedor_direccion, $proveedor_id, $proveedor_pagos_id, $proveedor_pagos_nombre, $proveedor_pagos_pais, $vinculo, $renta_bruta, $deduccion, $renta_neta, $tasa_retencion, $impuesto_retenido, $convenios, $exoneracion_aplica, $tipo_renta, $servicio_modalidad, $impuesto_aplicacion, $estado_ajuste, $libre) {
        $this->commandPrepare("sp_libro8_insertar");
        $this->commandAddParameter(':vin_periodo', $periodo);
        $this->commandAddParameter(':vin_cuo', $cuo);
        $this->commandAddParameter(':vin_correlativo', $correlativo);
        $this->commandAddParameter(':vin_fecha_emision', $fecha_emision);
        $this->commandAddParameter(':vin_comprobante_tipo', $comprobante_tipo);
        $this->commandAddParameter(':vin_serie', $serie);
        $this->commandAddParameter(':vin_numero', $numero);
        $this->commandAddParameter(':vin_valor', $valor);
        $this->commandAddParameter(':vin_conceptos_otros', $conceptos_otros);
        $this->commandAddParameter(':vin_total', $total);
        $this->commandAddParameter(':vin_comprobante_tipo_fiscal', $comprobante_tipo_fiscal);
        $this->commandAddParameter(':vin_serie_fiscal', $serie_fiscal);
        $this->commandAddParameter(':vin_dua_anio', $dua_anio);
        $this->commandAddParameter(':vin_comprobante_fiscal_numero', $comprobante_fiscal_numero);
        $this->commandAddParameter(':vin_igv', $igv);
        $this->commandAddParameter(':vin_moneda', $moneda);
        $this->commandAddParameter(':vin_tipo_cambio', $tipo_cambio);
        $this->commandAddParameter(':vin_pais', $pais);
        $this->commandAddParameter(':vin_proveedor_nombre', $proveedor_nombre);
        $this->commandAddParameter(':vin_proveedor_direccion', $proveedor_direccion);
        $this->commandAddParameter(':vin_proveedor_id', $proveedor_id);
        $this->commandAddParameter(':vin_proveedor_pagos_id', $proveedor_pagos_id);
        $this->commandAddParameter(':vin_proveedor_pagos_nombre', $proveedor_pagos_nombre);
        $this->commandAddParameter(':vin_proveedor_pagos_pais', $proveedor_pagos_pais);
        $this->commandAddParameter(':vin_vinculo', $vinculo);
        $this->commandAddParameter(':vin_renta_bruta', $renta_bruta);
        $this->commandAddParameter(':vin_deduccion', $deduccion);
        $this->commandAddParameter(':vin_renta_neta', $renta_neta);
        $this->commandAddParameter(':vin_tasa_retencion', $tasa_retencion);
        $this->commandAddParameter(':vin_impuesto_retenido', $impuesto_retenido);
        $this->commandAddParameter(':vin_convenios', $convenios);
        $this->commandAddParameter(':vin_exoneracion_aplica', $exoneracion_aplica);
        $this->commandAddParameter(':vin_tipo_renta', $tipo_renta);
        $this->commandAddParameter(':vin_servicio_modalidad', $servicio_modalidad);
        $this->commandAddParameter(':vin_impuesto_aplicacion', $impuesto_aplicacion);
        $this->commandAddParameter(':vin_estado_ajuste', $estado_ajuste);
        $this->commandAddParameter(':vin_libre', $libre);

        return $this->commandGetData();
    }

}
