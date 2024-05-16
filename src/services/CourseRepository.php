<?php

namespace Services;

interface CourseRepository{

    function add($courseName, $defaultColor);
    function exist($courseName);
    function getCourseList();
    function modifyColor($courseName, $newColor);
    function getCourse($courseName);

    function getCourseColor($courseName);
}