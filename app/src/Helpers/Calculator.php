<?php
namespace App\Helpers;

final class Calculator {

public $param;
public $student;
public $kotelezoTantargyak;

    public function __construct($param) {
    $this->param = $param;
    $this->kotelezoTantargyak = ['magyar nyelv és irodalom', 'történelem', 'matematika'];
    include('homework_input.php');
    $this->student = ${$param};

    }


    public function result()
    {
        $kotelezok = $this->checkKotelezok();
        if (count($kotelezok) > 0) {
            $kotelezokString = implode(', ', $kotelezok);
            return [
                'class' => 'danger',
                'message' => 'Az alábbi tantárgy(ak) nincs(enek) a kötelező értettségi tárgyak közül: '. $kotelezokString . '. <br> Pontszámítás nem lehetséges.'
            ];
        }
        $lowPercents = $this->checkLowPercent();
        if (count($lowPercents) > 0) {
            $lowPercentString = implode(', ', $lowPercents);
            return [
                'class' => 'danger',
                'message' => 'Az alábbi tantárgy(ak) esetében nincs meg minimum szint (20%): '. $lowPercentString. '. Pontszámítás nem lehetséges.'
            ];
        }
    }

    private function checkLowPercent()
    {
        $lows = [];
        foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
            $percent = explode('%', $value['eredmeny']);
            if ($percent[0] < 20) {
                $lows[] = $value['nev'];
            }
        }

        return $lows;
    }

    private function checkKotelezok()
    {
        $cnt = 0;
        $existingKOtelezok = [];
        foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
            if (in_array($value['nev'], $this->kotelezoTantargyak)) {
                error_log('megvan '.$value['nev']);
                $existingKOtelezok[] = $value['nev'];
                $cnt = $cnt +1;
            }
        }
       return $diff = array_diff( $this->kotelezoTantargyak, $existingKOtelezok);
    }






    

}