<?php
namespace App\Helpers;

final class Calculator {

public $param;
public $student;
public $kotelezoTantargyak;
public $progmatTargyak;
public $AnlgoszTargyak;

    public function __construct($param) {
        $this->param = $param;
        $this->kotelezoTantargyak = ['magyar nyelv és irodalom', 'történelem', 'matematika'];
        $this->progInfoTargyak = ['kotelezo' => [
                                    'matematika'
                                    ],
                                    'kotValTargyak' => [
                                        'biológia', 'fizika', 'informatika', 'kémia'
                                    ],
                                    'kotTipus' => 'emelt, közép',
                                ];
        $this->agloTargyak = ['kotelezo' => [
                                    'angol'
                                    ],
                                    'kotValTargyak' => [
                                        'francia', 'német', 'olasz', 'orosz'
                                    ],
                                    'kotTipus' => 'emelt',
                                ];
        include('homework_input.php');
        $this->student = ${$param};

    }

    public function setParam($param)  //unitteszthez
    {
        $this->param = $param;
    }


    public function result()
    {
        //Ellenőrizzük a kötelező tantárgyakat
        $kotelezok = $this->checkKotelezok();
        if (count($kotelezok) > 0) {
            $kotelezokString = implode(', ', $kotelezok);
            return [
                'class' => 'danger',
                'message' => 'hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt: '. $kotelezokString
            ];
        }
        //Megnézzük, vannak-e 20% alattiak
        $lowPercents = $this->checkLowPercent();
        if (count($lowPercents) > 0) {
            $lowPercentString = implode(', ', $lowPercents);
            return [
                'class' => 'danger',
                'message' => 'hiba, nem lehetséges a pontszámítás a '. $lowPercentString . ' tárgyból elért 20% alatti eredmény miatt'
            ];
        }

        //Kiszámoltatjuk a pontokat
        $checkPoints = $this->checkPoints();
        if ( ($checkPoints['alap'] + $checkPoints['tobblet']) > 0) {            //ha több pontja van, mint 0
            return [
                'class' => 'success',
                'message' => $checkPoints['alap'] + $checkPoints['tobblet'] .' ('.$checkPoints['alap'].'  alappont + '.$checkPoints['tobblet'].' többletpont)'
            ];
        } else {            //ha 0 pontja lett
            return [
                'class' => 'warning',
                'message' => 'Nem sikerült pontot elérni'
            ];
        }
    }

    public function checkPoints()
    {        
       $alapPont = $this->checkAlapPont();
       $tobbletPont = $this->checkTobbletPont();
       //Ha több pontja van, mint 100 akkor visszavesszük a maximumra
       if ( $tobbletPont > 100 ) {
            $tobbletPont = 100;
       }
       return [ 'alap' => $alapPont * 2,
                'tobblet' => $tobbletPont,
                ];
    }

    public function checkTobbletPont()
    {
        $cntEmelt = 0;
        foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
            if ($value['tipus'] == 'emelt') {    //megnézzük, hogy az emelt szint hányszor van meg az eredmények között
                $cntEmelt = $cntEmelt +1;
            }
        }

        $emeltPonts =  $cntEmelt * 50 ;   //megszorozzuk 50-nel a kapott számot

        $nyelv = array();
        foreach ($this->student['tobbletpontok'] as $key => $value) {
            
            $nyelvStr = $this->ekezettelenito($value['nyelv']);

            if (isset($nyelv[$nyelvStr]) && $nyelv[$nyelvStr] == 'B2' && $value['tipus'] == 'C1') {  //hamár van ilyen nyelvből, de van masik is, amelyiknak nagyobb a potértéke, akkor kicseréljük
                $nyelv[$nyelvStr] = $value['tipus'];
            } else {            //ha nincs még ilyen tárgy a listában, akkor létrehozzuk
                $nyelv[$nyelvStr] = '';
                $nyelv[$nyelvStr] = $value['tipus'];
            }
        }

        //kiszámítjuk a pontértékeinet
        $nyelvPoints= 0 ;
        foreach ($nyelv as $key => $value) {
            if ($value == 'B2') {
                $nyelvPoints = $nyelvPoints + 28;
            } elseif ($value == 'C1') {
                $nyelvPoints = $nyelvPoints + 40;
            } 
        }
        
        return $emeltPonts + $nyelvPoints;
    }

    public function checkAlapPont()
    {
            $pont = 0;
            $kotval = [];

            //
        $szak = $this->student['valasztott-szak']['szak'];
        if ($szak == "Programtervező informatikus") {
            //megnézzük benne van-e a kötelező tárgy
                foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
                    $percent = substr_replace($value['eredmeny'], "", -1);
                    
                    //ha a köletezo tantárgy megvan, hozzárendeljük a pontszámát
                if (in_array( $value['nev'], $this->progInfoTargyak['kotelezo'] )) {
                        $pont = $pont + $percent;
                }

                //kivalogatjuk, mely érettségi tantárgyak vannak meg a kötelezően válaszhatók közül
                if (in_array($value['nev'],  $this->progInfoTargyak['kotValTargyak']  )) {
                    $kotval[$key] = $value['nev'];
                }
            }

            //ha nincs találat
            if ($pont == 0) {
                return 0;
            }

            $kotvalPont = 0;
            foreach ($kotval as $key => $value) {
                $percent = substr_replace($this->student['erettsegi-eredmenyek'][$key]['eredmeny'], "", -1);
                if ($kotvalPont < $percent) {
                    $kotvalPont = $percent;
                }
            }

        }  elseif ($szak == "Anglisztika") {
            //megnézzük benne van-e a kötelező tárgy
            foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
                $percent = substr_replace($value['eredmeny'], "", -1);
                //error_log($percent);
                //ha a köletezo tantárgy és a tipus megvan, hozzárendeljük a pontszámát
                if (in_array( $value['nev'], $this->agloTargyak['kotelezo'] ) && in_array($value['tipus'],  $this->agloTargyak['kotTipus'] ) ) {
                    $pont = $pont + $percent;
                }

                //kivalogatjuk, mely érettségi tantárgyak vannak meg a kötelezően válaszhatók közül
                if (in_array($value['nev'],  $this->agloTargyak['kotValTargyak']  )) {
                    $kotval[$key] = $value['nev'];
                }
            }

            //ha nincs találat
            if ($pont == 0) {
                return 0;
            }

            $kotvalPont = 0;
            foreach ($kotval as $key => $value) {
                $percent = substr_replace($this->student['erettsegi-eredmenyek'][$key]['eredmeny'], "", -1);
                if ($kotvalPont < $percent) {
                    $kotvalPont = $percent;
                }
            }

        }

        return $pont + $kotvalPont;

    }
    public function checkLowPercent()
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

    public function checkKotelezok()
    {
        $cnt = 0;
        $existingKOtelezok = [];
        foreach ($this->student['erettsegi-eredmenyek'] as $key => $value) {
            if (in_array($value['nev'], $this->kotelezoTantargyak)) {
                $existingKOtelezok[] = $value['nev'];
                $cnt = $cnt +1;
            }
        }
       return $diff = array_diff( $this->kotelezoTantargyak, $existingKOtelezok);
    }

    function ekezettelenito($txt)
    {
        $ekezetes = array(' ','Á','É','Í','Ó','Ö','&#213;','Ú','Ü','&#219;','á ','é','í','ó','ö','&#245;','ú','ü','&#251;','ű','Ű ','Ő','ő','lő','ű');
        $ekezettelen = array('-','a','e','i','o','o','o','u','u','u','a','e','i', 'o','o','o','u','u','u','u','U','o','o','u');
        return strtolower(str_replace($ekezetes, $ekezettelen, $txt));
    }

}
