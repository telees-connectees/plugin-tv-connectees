<?php

namespace Models;

class Subject{

    private int $id;
    private string $name;
    private string $color;

    /**
     * @param int $id
     * @param string $name
     * @param string $color
     */
    public function __construct(int $id, string $name, string $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }




}