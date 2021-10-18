<?php

//Función para leer el archvio "ip_blacklist.csv"
function Read_file($long, $limit, $fileName, $enclosure, $fileManager){
    $ips = array();
    $rowNumber = 1;
    //Bucle para leer el contenido del archivo .csv
    while(($row = fgetcsv($fileManager, $long, $limit, $enclosure)) !== false){
        //bucle para recorrer las filas de la primer columna
        foreach ($row as $columnNumber => $column) {
            //De la primera fila extraigo el título de la columna
            if($rowNumber === 1){
                echo "$column";
                echo "<br>";
            }else if(!$column){ //Si la celda de la fila está vacía, muestro un mensaje
                echo "Celda vacía";
                echo "<br>";
            }else if(filter_var($column, FILTER_VALIDATE_IP)){ //si el contenido de la celda es una ip válida
                echo "Fila: $rowNumber / Columna: $columnNumber -----> $column <br>";
                //Extraigo el segundo octeto de cada ip
                $dirIp = explode(".", $column);
                //Si el octeto leído no existe en el arreglo, inicializo el contador de casos en 1
                if(!array_key_exists($dirIp[1], $ips)){
                    $ips[$dirIp[1]] = ['casos' => 1];
                }else{
                    //Si el octeto leído si existe en el arreglo, aumento el contador de casos en 1
                    $ips[$dirIp[1]] = ['casos' => $ips[$dirIp[1]]['casos'] + 1];
                }
            }
        }
        $rowNumber++;
    }
    echo "<br>";
    fclose($fileManager);
    return $ips;
}

//Función para obtener la información del endpoint de API
function GetNodes(){
    //Enlace al endpoint de API
    $url = "https://soldef.westnet.com.ar/api/v1/nodo";
    //Guardo todo lo que traigo de la API en un JSON
    $json = file_get_contents($url);
    //Extraigo la información del JSON en un array
    $resArray = json_decode($json, true);
    //Guardo toda la información de 'data' del array en uno distinto
    $nodes = $resArray['data'];
    return $nodes;
};


//Función para cruzar la información de ambos arrays
function MergeData(&$item, $key, $nodes){
    //Si la clave leída del arreglo de ips existe en el array de nodos
    if(array_key_exists($key-1, $nodes)){
        //Le asigno la información del array nodos, al item del array de ips que tiene el número de casos
        $item = [
            'nodo' => $nodes[$key-1]['name'],
            'casos' => $item['casos'],
        ];
    }
};


//leer el archivo .csv
$long = 101;
$limit = ",";
$fileName = "ip_blacklist.csv";
$enclosure = "'";
$fileManager = fopen($fileName, "r");
$keys = array();
$nodes = array();


//Mostrar un mensaje en caso de que no se pueda acceder al archivo
if(!$fileManager){
    exit("No se puede abrir el archivo $fileName");
}else{
    //Obtengo el arreglo de octetos con la cantidad de casos
    $keys = Read_file($long, $limit, $fileName, $enclosure, $fileManager);
}

//obtengo la información de la API y la guardo en un array
$nodes = GetNodes();

//Uso la función array_walk para recorrer ambos arreglos y llamo a la función MergeData para cruzar la info
array_walk($keys, 'MergeData', $nodes);

$keysToJson = json_encode($keys);

echo $keysToJson;

