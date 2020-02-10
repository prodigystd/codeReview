<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

//В большинстве случаев композиция предпочтительнее наследования
//Необходимо добавить PHPDOC для класса и его свойств
//Если необходим некий общий интерфейс то наследование можно заменить реализацией интерфейса
class DecoratorManager extends DataProvider
{
    //Желательно сделать свойства приватными по умолчанию, доступ только через geter/setter'ы.
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    //Необходимо добавить типы для всех аргументов
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    //logger лучше не делать опцинальным, так как некуда будет писать ошибки
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    //Необходимо добавить возвращаемый тип данных
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );
            //Необходимо сохранить $cacheItem в $cache
            return $result;
        } catch (Exception $e) {
            //Для лучшей отладки необходимо логгировать само сообщение $e->getMessage()
            $this->logger->critical('Error');
        }
        //В случае ошибки доступа к api лучше вернуть сам результат для информативности
        return [];
    }

    //Необходимо добавить возвращаемый тип данных
    public function getCacheKey(array $input)
    {
        //Если input достаточно длинный то можно генерировать на его основе хеш, но это опционально
        return json_encode($input);
    }
}
