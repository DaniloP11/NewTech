<?php

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token ==''){
  echo 'Error al procesar la petición';
  exit;
} else {

  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

  if($token == $token_tmp){

      $sql = $con->prepare("SELECT count(id) FROM computadores WHERE id=? AND activo=1");
      $sql->execute([$id]);
    if($sql->fetchColumn() > 0){

      $sql = $con->prepare("SELECT nombre, descripcion, precio FROM computadores WHERE id=? AND activo=1 LIMIT 1");
      $sql->execute([$id]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $nombre = $row['nombre'];
      $descripcion = $row['descripcion'];
      $precio = $row['precio'];

      $dir_images = 'images/computadores/' . $id . '/';

      $rutaImg = $dir_images . 'productos.jpg';

      if(!file_exists($rutaImg)){
        $rutaImg = 'images/no-photo.jpg';
      }

        $imagenes = array();
        if(file_exists($dir_images)){
            $dir = dir($dir_images);

            while(($archivo = $dir->read()) != false){
              if($archivo != 'productos.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))){
                $imagenes[] = $dir_images . $archivo;
              }
            }
            $dir->close();
        }
    
    }else {
      echo 'Error al procesar la petición';
      exit;
    }
  } else {
    echo 'Error al procesar la petición';
    exit;
  }
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Computadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
		body{
			min-height: 100vh;
			background: linear-gradient(rgba(5, 7, 12, 0),
			 rgba(0, 0, 0, 0.412)),
			url('fondo3.jpg') no-repeat center center fixed;
			background-size: cover;
		}
	</style>
    
</head>
<body>

    <header>
        <div class="navbar navbar-expand-lg narvar-dark bg-dark">
          <div class="container">
            <a href="#" class="navbar-brand">
               <strong id="newtech">NewTech</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          <div class="collapse navbar-collapse" id="navbarHeader">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="#" class="nav-link active"></a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link"></a>
                </li>

            </ul>

            <a href="checkout.php" class="btn btn-primary">
              Carrito<span id="num_cart" class="badge bg-secondary">
                <?php echo $num_cart; ?></span>
            </a>

          </div>
          </div>
        </div>
      </header>

      <!--Contenido-->
      <main>
       <div  class="container">
              <div class="row">
                     <div class="col-md-6 order-md-1">
                              <div id="carouselImg" class="carousel slide" data-bs-ride="carousel">
                                  <div class="carousel-inner">
                                      <div class="carousel-item active">
                                          <img src="<?php echo $rutaImg;?>" class="d-block w-100" >
                                      </div>
                                    </div>

                                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselImg" data-bs-slide="prev">
                                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                      <span class="visually-hidden">Previous</span>
                                  </button>

                                  <button class="carousel-control-next" type="button" data-bs-target="#carouselImg" data-bs-slide="next">
                                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                      <span class="visually-hidden">Next</span>
                                  </button>
                               </div>
                      </div>

                    <div class="col-md-6 order-md-2">
                          <h2><?php echo $nombre; ?></h2>
                          <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                                  <p class="lead">
                                      <?php echo $descripcion; ?>
                                  </p>                  

                          <div class="d-grid gap-3 col-10 mx-auto">
                                <button class="btn btn-primary" type="button">Comprar Ahora</button>
                                <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                                
                          </div> 
                    </div>    
              </div>               
        </div>                              
      </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
    crossorigin="anonymous"></script>

    <script type="text/javascript" src="js/funciones.js"></script>

    <script>

function addProducto(id, token){
   let url = 'clases/carrito.php'
   let formData = new FormData()
   formData.append('id', id)
   formData.append('token', token)
   
   fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
   }).then(response => response.json())
   .then(data => {
      if(data.ok){
         let elemento = document.getElementById("num_cart")
         elemento.innerHTML = data.numero
      }
   })
}

      </script>

</body>
</html>