<?php

class tehtava extends BaseModel {

    public $tehtava_id, $nimi, $lisayspaiva, $suoritettu, $tarkeysaste, $deadline, $kuvaus, $kayttaja_id;

    public function __construct($ab) {
        parent::__construct($ab);
    }

    public static function all() {

        $query = DB::connection()->prepare('SELECT * FROM Tehtava');
        $query->execute();
        $rows = $query->fetchAll();
        $tehtavat = array();

        foreach ($rows as $row) {
            $tehtavat[] = new tehtava(array(
                'tehtava_id' => $row['tehtava_id'],
                'nimi' => $row['nimi'],
                'lisayspaiva' => $row['lisayspaiva'],
                'suoritettu' => $row['suoritettu'],
                'tarkeysaste' => $row['tarkeysaste'],
                'deadline' => $row['deadline'],
                'kuvaus' => $row['kuvaus'],
                'kayttaja_id' => $row['kayttaja_id']
            ));
        }
        return $tehtavat;
    }

    public static function find($tehtava_id) {

        $query = DB::connection()->prepare('SELECT * FROM Tehtava WHERE tehtava_id = :tehtava_id LIMIT 1');
        $query->execute(array('tehtava_id' => $tehtava_id));
        $row = $query->fetch();

        if ($row) {
            $tehtava = new tehtava(array(
                'tehtava_id' => $row['tehtava_id'],
                'nimi' => $row['nimi'],
                'lisayspaiva' => $row['lisayspaiva'],
                'suoritettu' => $row['suoritettu'],
                'tarkeysaste' => $row['tarkeysaste'],
                'kuvaus' => $row['kuvaus'],
                'deadline' => $row['deadline'],
                'kayttaja_id' => $row['kayttaja_id']
            ));

            return $tehtava;
        }

        return null;
    }

    public function tallenna() {
        // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
        $query = DB::connection()->prepare('INSERT INTO Tehtava (nimi, lisayspaiva, tarkeysaste, deadline, kuvaus, kayttaja_id) VALUES (:nimi, NOW(), :tarkeysaste, :deadline, :kuvaus, 1) RETURNING tehtava_id');
        // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
        $query->execute(array('nimi' => $this->nimi, 'tarkeysaste' => $this->tarkeysaste, 'deadline' => $this->deadline, 'kuvaus' => $this->kuvaus));
        // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
        $row = $query->fetch();
        // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
        $this->tehtava_idid = $row['tehtava_id'];
    }

}