<?php

namespace Models;

class SubjectRepository extends Model implements SubjectRepositoryInterface{

    public function add($subjectName, $defaultColor){
        $sql = "INSERT INTO secretary_courses(name,color) VALUES (?,?)";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$subjectName,$defaultColor]);
    }

    public function exist($courseName){
        $sql = "SELECT * FROM secretary_courses WHERE name LIKE '%" . $courseName . "%'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$courseName]);
        if($stmt->fetch()){
            return true;
        }
        return false;
    }


    public function getSubjectList() : array{
        $subjectList = [];
        $sql = "SELECT * FROM secretary_courses";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([]);
        while($row = $stmt->fetch()){
            $subject = new Subject($row['id'],$row['name'],$row['color']);
            $subjectList[] = $subject;
        }
        return $subjectList;
    }


    public function modifyColor($subjectName, $subjectColor){
        $sql = "UPDATE secretary_courses SET color=? WHERE name LIKE '%" . $subjectName . "%'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$subjectColor]);
    }


    public function getSubjectColor($subjectName) : string{
        $sql = "SELECT * FROM secretary_courses WHERE name LIKE '%" . $subjectName . "%'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$subjectName]);
        if($row = $stmt->fetch()){
            return $row['color'];
        }
        return '#000000';
    }

    function getSubject($subjectName)
    {
        $sql = "SELECT * FROM secretary_courses WHERE name LIKE '%" . $subjectName . "%'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$subjectName]);
        if($row = $stmt->fetch()){
            return new Subject($row['id'],$row['name'],$row['color']);
        }
    }
}