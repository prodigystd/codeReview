<?php

namespace src\Integration;
//Необходимо добавить PHPDOC для класса и его свойств
class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    //Необходимо добавить типы для всех аргументов и тип возвращаемого значения
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    //Лучше назвать метод getResponse для большей прозрачности, также как и в дочернем классе
    //метод с таким названием в данном случае переподелит его в дочернем классе, но данном случае это не страшно
    public function get(array $request) // добавить тип возвращаемого значения
    {
        // returns a response from external service
    }

}
