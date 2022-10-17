<?php

require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$computadores = isset($_SESSION['carrito']['computadores']) ? $_SESSION['carrito']['computadores'] : null;
print_r($_SESSION);

$lista_carrito = array();

if ($computadores != null){
    foreach ($computadores as $clave => $cantidad){

        $sql = $con->prepare("SELECT id, nombre, precio, $cantidad AS cantidad FROM computadores WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);

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
                    <a  id="catalogo" href="#" class="nav-link active"></a>
                </li>

                <li class="nav-item">
                    <a id="contacto" href="#" class="nav-link"></a>
                </li>

            </ul>

            <a href="carrito.php" class="btn btn-primary">
              Carrito<span id="num_cart" class="badge bg-secondary">
                <?php echo $num_cart; ?></span>
            </a>

          </div>
          </div>
        </div>
      </header>

     <main>
        <div  class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th> 
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($lista_carrito == null){
                            echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                            } else {

                                $total = 0;
                                foreach ($lista_carrito as $producto){
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $cantidad = $producto['cantidad'];
                                    //$descuento = $precio['descuento'];
                                    //$precio_desc = $precio - (($precio * $descuento) /100);
                                    $subtotal = $cantidad * $precio;
                                    $total += $subtotal;
                                    ?>

                        <tr>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo MONEDA . number_format($precio,2, '.', ','); ?></td>
                            <td>
                                <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad?>"
                                size="5" id="cantidad_<?php echo $_id; ?>" 
                                onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                           </td>
                           <td>
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . 
                                number_format($subtotal,2, '.', ','); ?></div>
                           </td>
                           
                        </tr>
                        <?php }?>

                        <tr>
                            <td colspan="3"><b>Precio Total:</b></td>
                            <td colspan="2">
                                <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.',
                                ',');?></p>
                            </td>
                        </tr>

                    </tbody>
                    <?php } ?>
                </table>
            </div>
                <div class="row">
                    <div class='col-md-5 offset-md-7 d-grid gap-2'>
                        <button class="btn btn-primary btn-lg">Realizar pago</button>
                    </div>
                </div>

        </div>
     </main>

     <!-- Modal -->
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminaModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
    crossorigin="anonymous"></script>

    <script type="text/javascript" src="js/funciones.js"></script> 

    <script>

function actualizaCantidad(cantidad, id){
   let url = 'clases/actualizar_carrito.php'
   let formData = new FormData()
   formData.append('action', 'agregar')
   formData.append('id', id)
   formData.append('cantidad', token)
   
   fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
   }).then(response => response.json())
   .then(data => {
      if(data.ok){

            let divsubtotal = document.getElementById('subtotal_' + id)
            divsubtotal.innerHTML = data.sub

            let total = 0.00
            let list = document.getElementsByName('subtotal[]')

            for(let i = 0; i < list.lenght; i++){
                total += parseFloat(list[i].innerHtml.replace(/[$,]/g, ''))
            }

            total = new Intl.NumberFormat('en-US'{
                minimumFractionDigits: 2
            }).format(total)
            document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total

        }
   })
}

      </script>

</body>
</html>