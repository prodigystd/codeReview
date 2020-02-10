<?php

namespace src\Integration;

//Необходимо добавить PHPDOC для класса и его свойств
/**
 * Class DataProvider
 * @package src\Integration
 */
//Добавили интерфейс для большей гибкости
class DataProvider implements IDataProvider
{
    /** @var string */
    private $host;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    //Необходимо добавить типы для всех аргументов и тип возвращаемого значения
    public function __construct(string $host, string $user, string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     * @return array
     */
    //Лучше назвать метод getResponse для большей прозрачности, также как и в дочернем классе
    //метод с таким названием в данном случае переподелит его в дочернем классе, но данном случае это не страшно
    public function getResponse(array $request): array // добавить тип возвращаемого значения
    {
        // returns a response from external service
    }

}
