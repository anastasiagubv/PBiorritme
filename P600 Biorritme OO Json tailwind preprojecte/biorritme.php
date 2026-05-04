<?php
class Biorritme {
    private $nom;
    private $naixement;
    private $arrPeriodes = array("físic"=>23, "emotiu"=>28, "intelectual"=>33);
  

    public function __construct($naixement, $nom) {
        $this->naixement = new DateTime($naixement);
        $this->nom = $nom; 
    }

    public function getNom() {
        return $this->nom;
    }

    public function calculBiorritme() {
    $avui = new DateTime();
    
    // dies desde el naixement
    $interval = $this->naixement->diff($avui);
    $dies = $interval->days;

    $results = array();
    
    foreach ($this->arrPeriodes as $nom => $periode) {
        // Cicles completats
        $cicles = $dies / $periode;
        
        // Convertir a radians
        $radians = $cicles * 2 * M_PI;
        
        // Sinus (valor entre -1 i 1)
        $valor = sin($radians);
        
        // Convertir a percentatge (0-100)
        $percentatge = (($valor + 1) / 2) * 100;
        
        $results[$nom] = round($percentatge, 2);
    }
    
    return $results;
    }

    public function saveCalculBiorritmeToJson($values) {
        $file_name="biorritmes.json";

        // Llegir dades existents
        $data = array(
            if (file_exists($file_name)) {
                $json_data = file_get_contents($file_name);
                $data = json_decode($json_data, true);
            }
        );

        // Nova entrada
        $nova_entrada = array(
            "nom" => $this->nom,
            "naixement" => $this->naixement->format('Y-m-d'),
            "data_calcul" => (new DateTime())->format('Y-m-d H:i:s'),
            "biorritmes" => $values
        );
        
        // Afegir entrada a les dades existents
        $data[] = $entrada;

        // Guardar dades actualitzades al JSON  
        $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file_name, $json_data);
    }

    public function tableCalculBiorritmeJsonFile() {
        $file_name = "biorritmes.json";
        
        if (!file_exists($file_name)) {
            return "<p>No hi ha dades enregistrades.</p>";
        }
        
        $json_data = file_get_contents($file_name);
        $data = json_decode($json_data, true);
        
        $html_table = '<table border="1" cellpadding="10">
            <tr>
                <th>Nom</th>
                <th>Data Naixement</th>
                <th>Data Càlcul</th>
                <th>Físic</th>
                <th>Emotiu</th>
                <th>Intel·lectual</th>
            </tr>';
        
        foreach ($data as $fila) {
            $html_table .= '<tr>
                <td>' . $fila['nom'] . '</td>
                <td>' . $fila['data_naixement'] . '</td>
                <td>' . $fila['data_calcul'] . '</td>
                <td>' . $fila['biorritmes']['físic'] . '%</td>
                <td>' . $fila['biorritmes']['emotiu'] . '%</td>
                <td>' . $fila['biorritmes']['intelectual'] . '%</td>
            </tr>';
        }
        
        $html_table .= '</table>';
        
        return $html_table;
    }
}
?>