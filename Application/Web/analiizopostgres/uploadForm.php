<form enctype="multipart/form-data" action="uploadImage.php" method="POST">
    <!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
    <input type="hidden" name="MAX_FILE_SIZE" value="0">
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    <input type="hidden" name="pollid" value="1">
    <input type="hidden" name="description" value="descripción de prueba">
    Enviar este fichero: <input name="UpImage" type="file" />
    <input type="submit" value="Enviar fichero" />
</form>