<?php
class Pila {
    private $elementos = [];

    // Agregar un elemento al tope de la pila
    public function push($item) {
        array_push($this->elementos, $item);
    }

    // Quitar y devolver el elemento del tope de la pila
    public function pop() {
        if (!$this->estaVacia()) {
            return array_pop($this->elementos);
        }
        return null; // La pila está vacía
    }

    // Ver el elemento del tope sin quitarlo
    public function peek() {
        if (!$this->estaVacia()) {
            return end($this->elementos);
        }
        return null;
    }

    // Comprobar si la pila está vacía
    public function estaVacia() {
        return empty($this->elementos);
    }

    // Obtener el tamaño de la pila
    public function size() {
        return count($this->elementos);
    }

    // Mostrar todos los elementos
    public function mostrar() {
        print_r($this->elementos);
    }
}

//Con esto podríamos exportar la clase a otro archivo "Pila.php" y reutilizarlo en cualquier otro proyecto
//require 'Pila.php'; 

$pila = new Pila();

$pila->push("Manzana");
$pila->push("Banana");
$pila->push("Cereza");

echo "Elementos de la pila:\n";
$pila->mostrar();

echo "Tope de la pila: " . $pila->peek() . "\n";

echo "Elemento sacado: " . $pila->pop() . "\n";

echo "Pila después de hacer pop:\n";
$pila->mostrar();

echo "Tamaño de la pila: " . $pila->size() . "\n";

echo $pila->estaVacia() ? "La pila está vacía\n" : "La pila NO está vacía\n";

?>
