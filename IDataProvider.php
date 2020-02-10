<?php


namespace src\Integration;
/**
 * Interface IDataProvider
 * @package src\Integration
 */
interface IDataProvider
{
    /**
     * @param array $request
     * @return array
     * @throws \Exception
     */
    //заменили $input на $request для большей читабельности
    public function getResponse(array $request): array;
}
