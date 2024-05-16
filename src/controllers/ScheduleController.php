<?php

namespace Controllers;

use Models\SubjectRepositoryInterface;

class ScheduleController{


    private SubjectRepositoryInterface $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository, )
    {

    }

    public function displayScheduleConfig(){
        $courseList = $this->subjectRepository->getSubjectList();
        if(isset($_POST['modif-color'])){
            (new CourseController())->modifyColors();
            (new NotificationController())->displaySuccessNotification('Couleurs modifiées avec succès');
        }

        return $this->view->displayScheduleConfig($courseList);
    }
}