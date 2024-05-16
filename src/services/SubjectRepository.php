<?php

namespace Services;

interface SubjectRepository{

    function add($subjectName, $defaultColor);
    function exist($subjectName);
    function getSubjectList();
    function modifyColor($subjectName, $subjectColor);
    function getSubject($subjectName);
}