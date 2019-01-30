<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_juridica extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('solicitud_juridica_model');
		$this->load->model('expedientes_model');
		$this->load->model('delegados_model');
		$this->load->library('FPDF/fpdf');
	}

  	public function index(){
  		$data['id_empresa'] = $this->input->post('id_empresa');
  		$data['tipo_solicitud'] = $this->input->post('tipo_solicitud');
  		$data['band_mantto'] = $this->input->post('band_mantto');
	    $this->load->view('templates/header');
	    $this->load->view('resolucion_conflictos/solicitud_juridica', $data);
	    $this->load->view('templates/footer');
  	}

  	public function obtener_expediente_juridico() {
        print json_encode(
            $this->solicitud_juridica_model->obtener_registros_expedientes($this->input->post('id'))->result()
        );
    }

	public function tabla_solicitud_juridica(){
	    $this->load->view('resolucion_conflictos/solicitud_juridica_ajax/tabla_solicitud_juridica');
	}

  	public function tabla_representantes(){
		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/tabla_representantes');
	}

	public function ver_expediente(){
		$data['personaci'] = $this->solicitud_juridica_model->obtener_personaci($this->input->post('id_per'));
		$data['expediente'] = $this->solicitud_juridica_model->obtener_registros_expedientes( $this->input->post('id') );

		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/vista_expediente', $data);
	}

	public function imprimir_ficha_pdf() {
		$persona = $this->solicitud_juridica_model->obtener_personaci($this->input->post('id_personaci'));
		$expediente = $this->solicitud_juridica_model->obtener_registros_expedientes( $this->input->post('id_expedienteci') );
		$expediente = $expediente->result()[0];

		$html = "<table width='100%' style='border-outline: 1px; border-collapse: collapse;'><tbody>
				<tr>
					<td align='right'>
					<p style='font-size: 18px;'><small>N&uacute;mero de caso:</small> <b>$expediente->numerocaso_expedienteci</b><br></p>
					<b>Fecha y hora de creaci&oacute;n del expediente:</b> ".date("d-m-Y h:i:s A", strtotime($expediente->fechacrea_expedienteci))."</td>
				</tr>
		</tbody></table><br>";

		$html .= "
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td align='center'><span style='font-weight: bold; font-size: 14px;'>Información de la persona solicitante</span>
						</td>
					</tr>
				</tbody>
			</table>
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td colspan='3'>
							<b>N&uacute;mero de Inscripci&oacute;n de empresa: </b>$expediente->numinscripcion_empresa
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<b>Nombre de la empresa: </b>$expediente->nombre_empresa
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<b>Actividad: </b>$expediente->actividad_catalogociiu
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<b>Dirección: </b>$expediente->direccion_empresa
						</td>
					</tr>
					<tr>
						<td>
							<b>Municipio: </b>$expediente->municipio
						</td>
						<td>
							<b>Teléfono: </b>$expediente->telefono_empresa
						</td>

						<td>
							<b>Persona representante: </b>$expediente->nombres_representante
						</td>
					</tr>
				</tbody>
			</table><br>";

		$html .= "
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td align='center'><span style='font-weight: bold; font-size: 14px;'>Información de la persona solicitada</span>
						</td>
					</tr>
				</tbody>
			</table>
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td>
							<b>N&uacute;mero de DUI: </b>$expediente->dui_personaci
						</td>
					</tr>
					<tr>
						<td>
							<b>Nombre de la persona solicitante: </b>$expediente->nombre_personaci $expediente->apellido_personaci
						</td>
					</tr>
					<tr>
						<td>
							<b>Teléfono: </b>$expediente->telefono_personaci
						</td>
					</tr>
					<tr>
						<td>
							<b>Municipio: </b>$expediente->municipio
						</td>
					</tr>
					<tr>
						<td>
							<b>Dirección: </b>$expediente->direccion_personaci
						</td>
					</tr>
				</tbody>
			</table><br>";

		$html .= "
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td align='center'><span style='font-weight: bold; font-size: 14px;'>Información de la solicitud</span>
						</td>
					</tr>
				</tbody>
			</table>
			<table width='100%' style='border: 1px solid black;'>
				<tbody>
					<tr>
						<td>
							<b>Persona delegada asignada: </b>$expediente->nombre_delegado_actual
						</td>
					</tr>
					<tr>
						<td>
							<b>Motivo de la solicitud: </b>".(($expediente->motivo_expedienteci==1) ? "Despido de hecho o injustificado" : "Conflictos laborales")."
						</td>
					</tr>
					<tr>
						<td>
							<b>Descripción del motivo: </b>$expediente->descripmotivo_expedienteci
						</td>
					</tr>

				</tbody>
			</table>";

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL',
				'DIRECCIÓN GENERAL DE TRABAJO',
				'FICHA DE EXPEDIENTE');

		$this->load->library('mpdf');
		$this->mpdf=new mPDF('c','letter','10','Arial',10,10,30,17,3,9);

	 	$header = head_table_html($titles, $data, 'pdf');

	 	$this->mpdf->SetHTMLHeader($header);

	 	$pie = piePagina($this->session->userdata('usuario'));
		$this->mpdf->setFooter($pie);

		$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
		$this->mpdf->AddPage('P','','','','',10,10,30,17,5,10);
		$this->mpdf->SetTitle($titles[2]);
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output($titles[2].date(" - Ymd_His").'.pdf','I');

		//$this->load->view('resolucion_conflictos/solicitudes_ajax/vista_expediente', $data);
	}

  	public function combo_establecimiento() {
		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/combo_establecimiento',
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);
	}

	public function gestionar_representante(){
		if($this->input->post('band2') == "save"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
      		echo $this->solicitud_juridica_model->insertar_representante($data);
		}else if($this->input->post('band2') == "edit"){
      		$data = array(
		    'id_representante' => $this->input->post('id_representante'),
		    'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
			echo $this->solicitud_juridica_model->editar_representante($data);
		}else if($this->input->post('band2') == "delete"){
			$data = array(
			'id_representante' => $this->input->post('id_representante'),
			'estado_representante' => $this->input->post('estado_representante')
			);
			echo $this->solicitud_juridica_model->eliminar_representante($data);
		}
	}

	public function gestionar_establecimiento(){
		if($this->input->post('band') == "save"){
			$data = array(
			'tiposolicitud_empresa' => ($this->input->post('tiposolicitud_empresa')),
			'razon_social' => mb_strtoupper($this->input->post('razon_social')),
			'abreviatura_empresa' => mb_strtoupper($this->input->post('abreviatura_empresa')),
			'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'id_municipio' => $this->input->post('id_municipio'),
			'direccion_empresa' => mb_strtoupper($this->input->post('direccion_empresa')),
			'id_empleado' => $this->session->userdata('id_empleado')
			);
      		echo $this->solicitud_juridica_model->insertar_establecimiento($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
      		'id_empresa' => $this->input->post('id_empresa'),
      		'tiposolicitud_empresa' => ($this->input->post('tiposolicitud_empresa')),
			'razon_social' => mb_strtoupper($this->input->post('razon_social')),
			'abreviatura_empresa' => mb_strtoupper($this->input->post('abreviatura_empresa')),
		    'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'id_municipio' => $this->input->post('id_municipio'),
			'direccion_empresa' => mb_strtoupper($this->input->post('direccion_empresa'))
			);
			echo $this->solicitud_juridica_model->upgrade_establecimiento($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'estado_empresa' => $this->input->post('estado_empresa')
			);
			echo $this->solicitud_juridica_model->eliminar_establecimiento($data);
		}
	}

	public function gestionar_solicitado(){
		if($this->input->post('band3') == "save"){
			$data = array(
      'nombre_personaci' => mb_strtoupper($this->input->post('nombre_personaci')),
			'apellido_personaci' => mb_strtoupper($this->input->post('apellido_personaci')),
			'telefono_personaci' => $this->input->post('telefono_personaci'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion_personaci'),
			'sexo_personaci' => $this->input->post('sexo'),
			'id_empresaci' => $this->input->post('id_empresaci'),
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fecha_modifica' => date('Y-m-d')
			);
			echo $this->solicitud_juridica_model->insertar_solicitado($data);

		}else if($this->input->post('band3') == "edit"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => mb_strtoupper($this->input->post('nombre_personaci')),
			'apellido_personaci' => mb_strtoupper($this->input->post('apellido_personaci')),
			'telefono_personaci' => $this->input->post('telefono_personaci'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => mb_strtoupper($this->input->post('direccion_personaci')),
			'sexo_personaci' => $this->input->post('sexo_personaci'),
			'discapacidad_personaci' => $this->input->post('discapacidad_personaci'),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fecha_modifica' => date('Y-m-d')
			);
			echo $this->solicitud_juridica_model->editar_solicitado($data);

		}else if($this->input->post('band3') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitud_juridica_model->eliminar_solicitado($data);
		}
	}

	public function gestionar_expediente(){
		if($this->input->post('band4') == "save"){
			$data = array(
      'id_empresaci' => $this->input->post('id_empresaci'),
			'id_personal' => $this->input->post('id_personal'),
			'id_personaci' => $this->input->post('id_personaci'),
			'causa_expedienteci' => $this->input->post('causa_expedienteci'),
			'id_representanteci' => $this->input->post('id_representanteci'),
			'motivo_expedienteci' => $this->input->post('motivo_expedienteci'),
			'descripmotivo_expedienteci' => mb_strtoupper($this->input->post('descripmotivo_expedienteci')),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fechacrea_expedienteci' => date('Y-m-d H:i:s'),
			'fecha_modifica' => date('Y-m-d'),
			'tiposolicitud_expedienteci' => 3,
			'id_estadosci' =>1
			);
			echo $id_expedienteci = $this->solicitud_juridica_model->insertar_expediente($data);
			$id_expedienteci = explode(',',$id_expedienteci);
			$delegado = array(
				'id_expedienteci' => $id_expedienteci[1],
				'id_personal' => $data['id_personal'],
				'fecha_cambio_delegado' => date('Y-m-d'),
				'id_rol_guarda' => $this->session->userdata('id_rol'),
				'id_usuario_guarda' => $this->session->userdata('id_usuario'),
				'cambios' => "Asignación de expediente"
			);
			$this->delegados_model->insertar_delegado_exp($delegado);

		}else if($this->input->post('band4') == "edit"){
			$data = array(
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_empresaci' => $this->input->post('id_empresaci'),
			'id_personal' => $this->input->post('id_personal'),
			'id_personaci' => $this->input->post('id_personaci'),
			'causa_expedienteci' => $this->input->post('causa_expedienteci'),
			'id_representanteci' => $this->input->post('id_representanteci'),
			'motivo_expedienteci' => $this->input->post('motivo_expedienteci'),
			'descripmotivo_expedienteci' => mb_strtoupper($this->input->post('descripmotivo_expedienteci')),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fecha_modifica' => date('Y-m-d')
			);
			echo $this->solicitud_juridica_model->editar_expediente($data);

		}else if($this->input->post('band4') == "delete"){
			$data = array(
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitud_juridica_model->eliminar_expediente($data);
		}
	}

	public function combo_ocupacion() {
		$data = $this->db->get('sge_catalogociuo');
		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/combo_ocupacion',
			array(
				'id' => $this->input->post('id'),
				'ocupacion' => $data
			)
		);
	}

	public function emitir_ficha($id_expedienteci) {

        $this->load->library("phpword");
        $PHPWord = new PHPWord();
        $titulo = 'FichaSolicitud_PJPN';

		$rows = $this->solicitud_juridica_model->obtener_registros_expedientes( $id_expedienteci );
        $expediente = $rows->row();
        $rows2 = $this->solicitud_juridica_model->obtener_personaci( $expediente->id_personaci );
        $personaci = $rows2->row();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/templates/actasSolicitud/'.$titulo.'.docx');
        $get = array ('AAAA', 'BBBB', 'CCCC', 'DDDD', 'EEEE', 'FFFF', 'GGGG', 'HHHH', 'IIII', 'JJJJ', 'KKKK');
        $set = array ($expediente->numerocaso_expedienteci, $expediente->fechacrea_expedienteci, $personaci->direccion_personaci, $personaci->nombre_personaci." ".$personaci->apellido_personaci, $personaci->primarios_catalogociuo, $expediente->nombre_empresa, $expediente->telefono_empresa, $expediente->direccion_empresa, $expediente->primer_nombre." ".$expediente->segundo_nombre." ".$expediente->tercer_nombre." ".$expediente->primer_apellido." ".$expediente->segundo_apellido." ".$expediente->apellido_casada);

        $templateWord->setValue($get,$set);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='".$titulo."-".date('dmy_Hmi').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item("nombre_base").'/files/generate/'.$nombreWord.'.docx');

    }

    private function random() {
        $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
        $code = "";
        $longitud=5;
        for($i=0;$i<$longitud;$i++){
            $code .= $alpha[rand(0, strlen($alpha)-1)];
        }
        return $code;
    }

}
