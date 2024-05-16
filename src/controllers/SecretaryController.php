<?php

namespace Controllers;

use Models\CodeAde;
use Models\CourseRepository;
use Models\DailySchedule;
use Models\RoomRepository;
use Models\User;
use Models\WeeklySchedule;
use Views\SecretaryView;

/**
 * Class SecretaryController
 *
 * All actions for secretary (Create, update, display)
 *
 * @package Controllers
 */
class SecretaryController extends UserController
{

    /**
     * @var User
     */
    private $model;

    /**
     * @var SecretaryView
     */
    private $view;

    /**
     * Constructor of SecretaryController.
     */
    public function __construct() {
        parent::__construct();

        $this->model = new User();
        $this->view = new SecretaryView();
    }

    /**
     * Displays view content
     * @author Thomas Cardon
     * @return mixed|string
     */
    public function displayContent() {
        return $this->view->displayContent();
    }

    /**
     * Display all secretary
     * @return string
     */
    public function displayTableSecretary() {
        $users = $this->model->getUsersByRole('secretaire');
        return $this->view->displayTableSecretary($users);
    }

    public function displayUserCreationView() {
      $teacher = new TeacherController();
      $studyDirector = new StudyDirectorController();
      $secretary = new SecretaryController();
      $technician = new TechnicianController();
      $television = new TelevisionController();

      return $this->view->getHeader('Création des utilisateurs', '
      Il y a plusieurs types d\'utilisateurs :
      <br />
      Les enseignants, directeurs d\'études, secrétaires, techniciens, télévisions.

      <br /> <br />
      Les <b>enseignants</b> ont accès à leur emploi du temps et peuvent poster des alertes. <br />
      Les <b>directeurs d\'études</b> ont accès à leur emploi du temps et peuvent poster des alertes et des informations. <br />
      Les <b>secrétaires</b> peuvent poster des alertes et des informations. Ils peuvent aussi créer des utilisateurs. <br />
      Les <b>techniciens</b> ont accès aux emplois du temps des promotions. <br />
      Les <b>télévisions</b> sont les utilisateurs utilisés pour afficher ce site sur les téléviseurs. Les comptes télévisions peuvent afficher autant d\'emploi du temps que souhaité.
  ', URL_PATH . TV_PLUG_PATH . '/public/img/gestion.png') . '' . $this->view->renderContainerDivider() . '' . $this->view->renderContainer(
          $this->view->displayStartMultiSelect()
        . $this->view->displayTitleSelect('form', 'Par formulaire', true)
        . $this->view->displayTitleSelect('excel', 'Par fichier Excel (CSV)')
        . $this->view->displayEndOfTitle()
        . $this->view->displayContentSelect('form', $this->view->displayUserCreationForm(), true)
        . $this->view->displayContentSelect('excel', $this->view->displayUserCreationFormExcel())
        . $this->view->displayEndDiv()
      );
    }

    /**
     * Displays users by roles
     */
    public function displayUsers() { //@todo Supprimer les classes inutiles en dessous
        $teacher = new TeacherController(); //Inutile
        $studyDirector = new StudyDirectorController(); //Inutile
        $secretary = new SecretaryController();
        $technician = new TechnicianController();
        $television = new TelevisionController();
        $user = new UserController();
        $secretaryTV = new SecretaryTvController();

        return $this->view->getHeader('Liste des utilisateurs', '
        Il y a plusieurs types d\'utilisateurs :
        <br />
        Les <s>étudiants</s>, enseignants, directeurs d\'études, secrétaires, techniciens, télévisions.

        <br /> <br />
        Les <b>étudiants</b> ont accès à leur emploi du temps et reçoivent les alertes les concernants et les informations. <br />
        Les <b>enseignants</b> ont accès à leur emploi du temps et peuvent poster des alertes. <br />
        Les <b>directeurs d\'études</b> ont accès à leur emploi du temps et peuvent poster des alertes et des informations. <br />
        Les <b>secrétaires</b> peuvent poster des alertes et des informations. Ils peuvent aussi créer des utilisateurs. <br />
        Les <b>techniciens</b> ont accès aux emplois du temps des promotions. <br />
        Les <b>télévisions</b> sont les utilisateurs utilisés pour afficher ce site sur les téléviseurs. Les comptes télévisions peuvent afficher autant d\'emploi du temps que souhaité.
    ', URL_PATH . TV_PLUG_PATH . 'public/img/gestion.png') . '' . $this->view->renderContainerDivider() . '' . $this->view->renderContainer(
              $this->view->displayStartMultiSelect() .
              $this->view->displayTitleSelect('all', 'Tous les utilisateurs', true) .
              $this->view->displayTitleSelect('television', 'Télévisions') .
              $this->view->displayEndOfTitle() .
              $this->view->displayContentSelect('television', $television->displayTableTv()) .
              $this->view->displayContentSelect('all', $user->displayUsers(), true) .
              $this->view->displayEndDiv(), '', 'container-sm px-4 pb-5 my-5 text-center'
            );
    }

    /**
     * Display the welcome page from the secretary view
     * @return string
     */
    function displayWelcomePage(){
        return $this->view->displaySecretaryWelcome();
    }

    public function displayRoomsSelection() : string {
        $model = new RoomRepository();
        $roomList = $model->getAllRoom();

        usort($roomList, function($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });

        return (new SecretaryView())->displayRoomSelection($roomList);
    }

    public function displayRoomSchedule() : string{
        $view = $this->displayRoomsSelection();
        if(!isset($_POST['roomName'])) return $view;
        $roomName = $_POST['roomName'];

        $room = (new RoomRepository())->getRoom($roomName);
        $dailyScheduleRoom = new DailySchedule(date('Ymd'));
        $codeAde = (new CodeAde())->getAllAdeCode();
        foreach ($codeAde as $code){
            $weeklySchedule = new WeeklySchedule($code);
            foreach ($weeklySchedule->getDailySchedules() as $dailySchedule){
                if($dailySchedule->getDate() != date('Ymd')) continue;
                foreach ($dailySchedule->getCourseList() as $course){
                    if($course != null && strpos($course->getLocation(),$roomName) !== false && !in_array($course,$dailyScheduleRoom->getCourseList())){
                        $dailyScheduleRoom->addExistingCourse($course);
                    }
                }
            }
        }
        return $view . $this->view->displayRoomSchedule($dailyScheduleRoom, $room);
    }

    /**
     * Modify an user
     */
    public function modifyUser() {
        $id = $_GET['id'];
        if (is_numeric($id) && $this->model->get($id)) {
            $user = $this->model->get($id);

            $wordpressUser = get_user_by('id', $id);

            if (in_array("television", $wordpressUser->roles)) {
                $controller = new TelevisionController();
                return $controller->modify($user);
            } else {
                return $this->view->displayNoUser();
            }
        } else {
            return $this->view->displayNoUser();
        }
    }

    /**
     * Delete an user
     *
     * @param $id
     */
    private function deleteUser($id) {
        $user = $this->model->get($id);
        $user->delete();
    }

    /** Affiche les salles info disponibles
     * @return string
     */
    public function displayComputerRoomsAvailable(){
        $model = new RoomRepository();
        $roomList = $model->getAllComputerRooms();
        
        $mobileRooms = array_filter($roomList, function($room) {
            return preg_match('/\/\s*Mobile\s*$/i', $room->getName());
        });

        $nonMobileRooms = array_filter($roomList, function($room) {
            return !preg_match('/\/\s*Mobile\s*$/i', $room->getName());
        });

        usort($nonMobileRooms, function($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });
        usort($mobileRooms, function($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });

        $sortedRoomList = array_merge($nonMobileRooms, $mobileRooms);
        return $this->view->displayComputerRoomsAvailable($sortedRoomList);
    }



    public function displayRoomsAvailable(): string
    {
        return $this->view->displayRoomsAvailable();
    }


    /** Affiche l'emploi du temps d'une année
     * TODO gestion erreur
     * @return string
     */
    public function displayYearStudentSchedule(): string
    {
        $year = $_GET['year'];
        $model = new CodeAde();
        $codeList = array();
        $codeListWithGroupName = array();
        switch ($year){
            case '1':
                $codeList = $model->getCodeOfAYear(1);
                break;
            case '2':
                $codeList = $model->getCodeOfAYear(2);
                break;
            case '3':
                $codeList = $model->getCodeOfAYear(3);
                break;
        }

        foreach ($codeList as $code) {
            $codeListWithGroupName[$code] = $model->getCodeName($code);
        }

        return $this->view->displayYearStudentScheduleView($codeListWithGroupName, $year);
    }

    public function displayHomePage(){
        return $this->view->displayHomePage();
    }

    public function displayScheduleConfig(){
        $model = new CourseRepository();
        $courseList = $model->getCourseList();
        if(isset($_POST['modif-color'])){
            (new CourseController())->modifyColors();
            (new NotificationController())->displaySuccessNotification('Couleurs modifiées avec succès');
        }

        return $this->view->displayScheduleConfig($courseList);
    }

    public function displayConfig() : string{
        return (new SecretaryView())->displaySecretaryConfig();
    }

    public function displayAllYearSchedule(){
        return (new SecretaryView())->displayAllYearSlider();
    }

    public function displayCodeAdeConfigPage(){
        if(isset($_POST['addCode'])){
            (new CodeAde())->addYearForCode($_POST['codeAde'],$_POST['year']);
        }
        if(isset($_POST['deleteAde'])){
            (new CodeAde())->deleteYearForCode($_POST['code']);
        }

        return (new SecretaryView())->displayCodeAdeConfigPage();
    }
}
