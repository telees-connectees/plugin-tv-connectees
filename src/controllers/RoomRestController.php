<?php

namespace Controllers;
use Models\Room;
use Models\RoomRepository;

class RoomRestController extends \WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'amu-ecran-connectee/v1';
        $this->rest_base = 'room';
    }

    public function register_routes(){
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/create',
            array(array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_room'),
                'args' => $this->get_collection_params(),
             ),'schema' => array($this, 'get_public_item_schema'),
            )
        );
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_room'),
                    'args' => $this->get_collection_params(),
                ),
                array(
                    'methods' => \WP_REST_Server::DELETABLE,
                    'callback' => array($this, 'delete_room'),
                    'args' => $this->get_collection_params(),
                ),
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => array($this, 'update_room'),
                    'args' => array(
                        'pcCount' => array(
                            'type' => 'number',
                            'description' => __('Number of pc available'),
                        ),
                        'brokenComputer' => array(
                            'type' => 'number',
                            'description' => __('Number of pc broken'),
                        ),
                        'projector' => array(
                            'type' => 'boolean',
                            'description' => __('Is there a projector'),
                        ),
                        'chairCount' => array(
                            'type' => 'number',
                            'description' => __('Number of place available'),
                        ),
                        'connection' => array(
                            'type' => 'string',
                            'description' => __('Connection type available'),
                        ),
                        'roomType' => array(
                            'type' => 'string',
                            'description' => __('The type of room'),
                        ),
                    ),
                ),
                'schema' => array($this, 'get_public_item_schema'),

            )
        );

    }

    /**
     * Get a room
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_room($request){
        $roomRepo = new RoomRepository();
        $room = (new RoomRepository())->getRoom($request['id']);
        $status = "";

        if(!$room){
            return new \WP_REST_Response(array('message' => 'Information not found'), 404);
        }

        if($roomRepo->isRoomLocked($request['id'])){
            $room->setStatus("locked");
        }
        else if(!$roomRepo->getRoom($request['id'])->isAvailable()){
            $room->setStatus("occupied");
        }
        else{
            $room->setStatus("available");
        }

        return new \WP_REST_Response(array($room), 200);
    }


    /**
     * Update a room
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function update_room($request){
        $roomRepo = new RoomRepository();
        $room = $roomRepo->updateRoom($request['id'], $request['pcCount'], $request['brokenComputer'],$request['projector'], $request['chairCount'], $request['roomType'], $request['connection']);
        return new \WP_REST_Response(array($room), 200);
    }

    /**
     * Create a room
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function create_room($request){
        $roomRepo = new RoomRepository();
        $roomRepo->add($request['name'], $request['type']);
        return new \WP_REST_Response(array('OK'), 200);
    }

    /**
     * Delete a room
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete_room($request){
        $roomRepo = new RoomRepository();
        $roomRepo->delete($request['name']);
        return new \WP_REST_Response(array('OK'), 200);
    }




}