<?php

namespace Models;

class RoomRepository extends Model{

    public function exist($name): bool
    {
        $sql = "SELECT * FROM ecran_rooms WHERE name LIKE '%" . $name . "%'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$name]);
        if($stmt->fetch()){
            return true;
        }
        return false;
    }

    public function add($name, $type='TD') : void {
        $sql = "INSERT INTO ecran_rooms(name, room_type) VALUES (?,?)";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$name, $type]);
    }

    public function delete($name) : void{
        $sql = "DELETE FROM ecran_rooms WHERE name=?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$name]);
    }


    /**
     * @return Room[]
     */
    public function getAllComputerRooms(){
        $sql = "SELECT DISTINCT * FROM ecran_rooms WHERE isComputerRoom=TRUE";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute();
        $roomList = [];

        while($row = $stmt->fetch()){
            $roomList[] = new Room($row['name'], $row['isComputerRoom'], $row['pc_available'], $row['broken_computer'], $row['place_available'],
                $row['has_video_projector'],$row['cable_types'], $row['room_type']);
        }
        return $roomList;
    }

    public function getAllRoom(){
        $sql = "SELECT DISTINCT * FROM ecran_rooms";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute();
        $roomList = [];

        while($row = $stmt->fetch()){
            $roomList[] = new Room($row['name'], $row['isComputerRoom'], $row['pc_available'], $row['broken_computer'], $row['place_available'],
                $row['has_video_projector'],$row['cable_types'], $row['room_type']);
        }
        return $roomList;
    }


    public function lockRoom($roomName, $motif, $endDate){
        $sql = "INSERT INTO secretary_lock_room(roomName, motif, lockEndDate) VALUES (?,?,?)";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$roomName,$motif, $endDate]);
    }

    public function isRoomLocked($roomName){
        $date = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM secretary_lock_room WHERE roomName = ? AND lockEndDate > ?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$roomName,$date]);
        if($stmt->fetch()){
            return true;
        }
        return false;
    }

    public function getMotifLock($roomName){
        $sql = "SELECT * FROM secretary_lock_room WHERE roomName = ?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$roomName]);
        if($row = $stmt->fetch()){
            return [$row['motif'],$row['lockEndDate']];
        }
        return null;
    }

    public function getRoom($roomName){
        $roomName = trim(preg_replace('/(TD)|(TP)|(Mobile)/','',$roomName));
        $sql = "SELECT * FROM ecran_rooms WHERE name= '$roomName'";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$roomName]);
        if($row = $stmt->fetch()){
            return new Room($row['name'], $row['isComputerRoom'], $row['pc_available'], $row['broken_computer'], $row['place_available'],
                $row['has_video_projector'],$row['cable_types'], $row['room_type']);
        }
        return null;
    }

    public function unlockRoom($roomName){
        $sql = "DELETE FROM secretary_lock_room WHERE roomName = ?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$roomName]);
    }

    public function resetComputerRoomCheck(){
        $sql = "UPDATE ecran_rooms SET isComputerRoom=0";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([]);
    }
    public function updateComputerRoom($roomName, $value){
        $sql = "UPDATE ecran_rooms SET isComputerRoom=? WHERE name=?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$value,$roomName]);
    }

    public function updateRoom($roomName, $pcCount, $brokenPc, $projector, $chairCount, $roomType, $connection){
        $sql = "UPDATE ecran_rooms SET pc_available=?,broken_computer=?,has_video_projector=?,place_available=?,cable_types=?,room_type=? WHERE name=?";
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([$pcCount,$brokenPc, $projector, $chairCount, $connection,$roomType, $roomName]);
        return $this->getRoom($roomName);
    }


}
