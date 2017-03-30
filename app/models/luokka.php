<?php

class luokka extends BaseModel {

    public $luokka_id, $nimi, $kayttaja_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Luokka');
        $query->execute();
        $rows = $query->fetchAll();
        $luokat = array();

        foreach ($rows as $row) {
            $luokat[] = new luokka(array(
                'luokka_id' => $row['luokka_id'],
                'nimi' => $row['nimi'],
                'kayttaja_id' => $row['kayttaja_id'],
            ));
        }

        return $luokat;
    }

    public static function find($luokka_id) {
        $query = DB::connection()->prepare('SELECT * FROM Luokka WHERE luokka_id =:luokka_id LIMIT 1');
        $query->execute(array('luokka_id' => $luokka_id));
        $row = $query->fetch();

        if ($row) {
            $luokka = new luokka(array(
                'luokka_id' => $row['luokka_id'],
                'nimi' => $row['nimi'],
                'kayttaja_id' => $row['kayttaja_id'],
            ));

            return $luokka;
        }

        return null;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Luokka (nimi, kayttaja_id) VALUES (:nimi, 1) RETURNING luokka_id');
        $query->execute(array('nimi' => $this->nimi));
        $row = $query->fetch();
        $this->luokka_id = $row['luokka_id'];
    }
}