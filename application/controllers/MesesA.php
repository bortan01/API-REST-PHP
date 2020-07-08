 public function index()
 {
 echo "index";
 }
 public function meses($mes)
 {
 $meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre',
 'noviembre', 'diciembre');
 echo json_encode($meses[$mes]);
 }
 }