<?php

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM computadores WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Computadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
    <style>
		body{
			min-height: 100vh;
			background: linear-gradient(rgba(5, 7, 12, 0),
			 rgba(0, 0, 0, 0.412)),
			url('fondo2.jpg') no-repeat center center fixed;
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

            </ul>

            <a href="checkout.php" class="btn btn-primary">
              Carrito<span id="num_cart" class="badge bg-secondary">
                <?php echo $num_cart; ?></span>
            </a>

          </div>
          </div>
          
        </div>
      </header>

      <main>
      
      
        <div  class="container">
        <br><h1>Mira los productos que te ofrecemos</h1> <br>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                  <?php foreach($resultado as $row){ ?>
                    <div class="col">
                      <div class="card shadow-sm">
                          <?php

                          $id = $row["id"];
                          $imagen = "images/computadores/" . $id . "/productos.jpg";
                          
                          if(!file_exists($imagen)){
                       
                            $imagen = "images/no-photo.jpg";
                          
                          }
                          
                          ?>

                        <img src="<?php echo $imagen; ?>">
                        <div class="card-body">
                          <h5 class="card-title"><?php echo $row['nombre'];?></h5>
                          <p class="card-text">$<?php echo number_format($row['precio'], 2, '.', ',');?></p>
                          <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo 
                                hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" 
                                class="btn btn-primary">Detalles</a>
                            </div>
                            <button class="btn btn-outline-success" type="button" onclick="addProducto (<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
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