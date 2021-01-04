<?php 
	extract($_GET);
	$validFunctions = array("consulta", 'guardado', 'mis_bienes');
	if (in_array($funct, $validFunctions)) $funct($val);

		function consulta($val) {

			$json = file_get_contents('http://localhost/suplosBackEnd/data.json');
			$json = json_decode($json);
			$validacion_ciudad = [];
			$callback = [];
			foreach ($json as $contenido) {
				if ($val[1] != '0') {

					if ($contenido->Ciudad.'' == ''.$val[1]) {

						array_push($validacion_ciudad, $contenido);

					}
				} else {

					array_push($validacion_ciudad, $contenido);

				}
				
			}

			foreach ($validacion_ciudad as $ciudades) {
				if ($val[0] != '0') {

					if ($ciudades->Tipo.'' == ''.$val[0]) {

						array_push($callback, $ciudades);

					}

				} else {

					array_push($callback, $ciudades);

				}
				
			}

			print_r(json_encode($callback));
		}

		function guardado($val) {

			$valores = [];
			$mysqli = new mysqli('127.0.0.1', 'root', '1234', 'Intelcost_bienes');
			$mysqli->set_charset("utf8");

			$consulta = $mysqli->query("SELECT count(*) as registros FROM guardado where idusuario=".$val[1]." and idjson=".$val[0]);
			$res_consulta = $consulta->fetch_object();
			if ($res_consulta->registros == 0) {
				$guardado = $mysqli->query("INSERT INTO guardado (id, idjson, idusuario) VALUES (NULL, '".$val[0]."', '".$val[1]."')");

				//consulta posterior al insert para actualizar el registro solicitado
				$res = $mysqli->query("SELECT * FROM guardado where idusuario=".$val[1]);

				while($f = $res->fetch_object()){
     				array_push($valores, $f->idjson);
				}

				print_r(json_encode($valores));
			} else { 

				print_r(json_encode($valores));
			}
			
		}

		function mis_bienes($val) {

			$valores = [];
			$mysqli = new mysqli('127.0.0.1', 'root', '1234', 'Intelcost_bienes');
			$mysqli->set_charset("utf8");

			//consulta posterior al insert para actualizar el registro solicitado
			$res = $mysqli->query("SELECT * FROM guardado where idusuario=".$val[1]);

			while($f = $res->fetch_object()){
     			array_push($valores, $f->idjson);
			}
			$json = file_get_contents('http://localhost/suplosBackEnd/data.json');
			$json = json_decode($json);
			$callback = [];
			foreach ($json as $contenido) {
				foreach ($valores as $id) {
					if ($contenido->Id.'' == ''.$id) {
						array_push($callback, $contenido);
					}
				}
			}
			print_r(json_encode($callback));			
		}
?>