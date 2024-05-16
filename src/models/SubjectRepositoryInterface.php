<?php

namespace Models;

interface SubjectRepositoryInterface{

    function add($subjectName, $defaultColor);
    function exist($subjectName);
    function getSubjectList();
    function modifyColor($subjectName, $subjectColor);
    function getSubject($subjectName);
}