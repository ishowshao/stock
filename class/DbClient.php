<?php
/**
 * MongoDb
 *
 * @author ishowshao
 */
class DbClient
{
    /**
     * @var DbClient
     */
    private static $instance;

    /**
     * @var MongoDb
     */
    private $db;

    private function __construct()
    {
        $host = '127.0.0.1';
        $port = '27017';

        try {
            $mongoClient = new MongoClient("mongodb://{$host}:{$port}");

            $this->client = $mongoClient;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 获取实例之后要先 selectDb
     *
     * @return DbClient
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DbClient();
        }
        return self::$instance;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function selectDb($name)
    {
        $this->db = $this->client->selectDB($name);
        return $this;
    }

    public function getCollection($name)
    {
        return $this->db->selectCollection($name);
    }

    /**
     * 获取某个表的自增值
     *
     * @param {string} $name 某表的自增基数，表名字
     * @return int
     */
    public function autoIncrement($name)
    {
        $collection = $this->getCollection('counters');
        $exist = $collection->findOne(array('_id' => $name));
        if ($exist) {
            $result = $collection->findAndModify(array('_id' => $name), array('$inc' => array('seq' => 1)), null, array('new' => true));
            $id = $result['seq'];
        } else {
            $collection->insert(array('_id' => $name, 'seq' => 0));
            $id = 0;
        }
        return $id;
    }
}

$collection = DbClient::getInstance()->getCollection('testData');
$cursor = $collection->find();
foreach ($cursor as $data) {
    var_dump($data);
}