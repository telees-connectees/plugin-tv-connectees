<?php

namespace Controllers;

use Models\CodeAde;
use Models\User;
use Views\TechnicianView;

use R34ICS;

/**
 * Class TechnicianController
 *
 * Manage Technician (Create, update, delete, display, display schedule)
 *
 * @package Controllers
 */
class TechnicianController extends UserController implements Schedule
{

    /**
     * @var User
     */
    private $model;

    /**
     * @var TechnicianView
     */
    private $view;

    /**
     * Constructor of SecretaryController.
     */
    public function __construct() {
        parent::__construct();
        $this->model = new User();
        $this->view = new TechnicianView();
    }

    /**
     * Displays the schedule of all students
     * @author Thomas Cardon
     * @return mixed|string
     */
    public function displayContent() {
        $codeAde = new CodeAde();

        $years = $codeAde->getAllFromType('year');
        $today_events_by_year = [];
        // On va récupérer par an l'EDT
        foreach ($years as $year) {
            global $R34ICS;
            $R34ICS = new R34ICS();
            $url = $this->getFilePath($year->getCode());
            $args = array(
                'count' => 10,
                'description' => null,
                'eventdesc' => null,
                'format' => null,
                'hidetimes' => null,
                'showendtimes' => null,
                'title' => null,
                'view' => 'list',
            );
            $data = $R34ICS->display_calendar($url, $year->getCode(), true, $args, true);

            $today_events = $data[0]["events"][date("Y")][date("m")][date("d")];
            $today_events_by_year[] = $today_events;
        }

        $all_events = [];
        $liste_salles_occupées_journee = [];
        $liste_horaire_par_salle = [];

        foreach ($today_events_by_year as $year){
            foreach ($year as $start_time){
                foreach ($start_time as $event){
                    $liste_salles_occupées_journee[] = $event["location"];
                    if ($liste_salles_occupées_journee === array_unique($liste_salles_occupées_journee)){
                        $liste_horaire_par_salle[] = [];
                    }
                    else{
                        $liste_salles_occupées_journee = array_unique($liste_salles_occupées_journee);
                    }
                    $liste_horaire_par_salle[array_search($event["location"], $liste_salles_occupées_journee)][] = [$event["start"], $event["end"]];
                    $liste_horaire_par_salle[array_search($event["location"], $liste_salles_occupées_journee)] = array_unique($liste_horaire_par_salle[array_search($event["location"], $liste_salles_occupées_journee)]);
                }
            }
        }

        $affichage = '<br><h1 id="titre_tech">Liste des salles occupées avec leurs horaires : </h1><br><br>
<table id="table_tech">
  <col>
  <colgroup span="2"></colgroup>
  <colgroup span="2"></colgroup>
  <tr id="tr_tech">
    <th id="th_tech" colspan="1" scope="colgroup">Salle</th>
    <th id="th_tech" colspan="10" scope="colgroup">Horaires d\'occupation</th>
  </tr>
  <tbody>
    ';

        for ($index = 0;$index<sizeof($liste_horaire_par_salle);$index++){
            $affichage .= "<tr id=\"tr_tech\"><th id=\"th_tech\" scope='row'>".$liste_salles_occupées_journee[$index]."</th>";
            foreach ($liste_horaire_par_salle[$index] as $horaires){

                $heure_debut_convertie = intval(explode("h", $horaires[0])[0])+4 ."h".explode("h", $horaires[0])[1];
                $heure_fin_convertie = intval(explode("h", $horaires[1])[0])+4 ."h".explode("h", $horaires[1])[1];

                $affichage .= "<td id=\"td_tech\">".$heure_debut_convertie." à ".$heure_fin_convertie."</td>";
            }
            $affichage .= "</tr>";

        }

        $affichage .= "</table><br><br>";

        return $affichage;
    }

    /**
     * Display all technicians in a table
     *
     * @return string
     */
    public function displayTableTechnician() {
        $users = $this->model->getUsersByRole('technicien');
        return $this->view->displayTableTechnicians($users);
    }
}
