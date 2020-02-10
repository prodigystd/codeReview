<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\IDataProvider;

//В большинстве случаев композиция предпочтительнее наследования
//Необходимо добавить PHPDOC для класса и его свойств
//Если необходим некий общий интерфейс то наследование можно заменить реализацией интерфейса

/**
 * Class DecoratorManager
 * @package src\Decorator
 */
class DecoratorManager implements IDataProvider
{
    //Желательно сделать свойства приватными по умолчанию, доступ только через geter/setter'ы.
    /** @var IDataProvider */
    private $dataProvider;

    /** @var CacheItemPoolInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param IDataProvider $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    //Необходимо добавить типы для всех аргументов
    //logger лучше не делать опцинальным, так как некуда будет писать ошибки поэтому добавляем в конструктор
    public function __construct(IDataProvider $dataProvider, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    //Необходимо добавить возвращаемый тип данных
    //заменили $input на $request для большей читабельности и унификации интерфейса
    public function getResponse(array $request): array
    {
        $result = []; //На всякий случай если вылетит exception и $result не будет инициализирован
        try {
            $cacheKey = $this->getCacheKey($request);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->getResponse($request);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );
            //Необходимо сохранить $cacheItem в $cache
            if (!$this->cache->save($cacheItem)) {
                $this->logger->warning('Could save cache for: ' . json_encode($request));
            }
        } catch (Exception $e) {
            //Для лучшей отладки необходимо логгировать само сообщение $e->getMessage()
            $this->logger->critical($e->getMessage());
        }
        //В случае ошибки доступа к api лучше вернуть сам результат для информативности
        return $result;
    }

    /**
     * @param array $input
     * @return string
     */
    //Необходимо добавить возвращаемый тип данных и PHPDoc
    public function getCacheKey(array $input): string
    {
        //Если input достаточно длинный то можно генерировать на его основе хеш, но это опционально
        return sha1(json_encode($input));
    }
}
