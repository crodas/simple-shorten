<?php

require __DIR__ . '/common.php';

/**
 *  Example DB object
 */
class Database
{
    protected $db;

    public function __construct()
    {   
        $this->db = new \Pdo("sqlite:/tmp/shorten.db");
        $this->db->exec("CREATE TABLE links (
            id  INTEGER PRIMARY KEY AUTOINCREMENT,
            link varchar(250)
        )");
    }
    
    public function id($id)
    {
        $rows = $this->db->prepare("SELECT * FROM links WHERE id = ?");
        $rows->execute(array($id));
        $row = $rows->fetch();
        return $row ? $row : NULL;
    }

    public function insert($url)
    {
        $this->db->prepare("INSERT INTO links(link) VALUES(?)")
            ->execute(array($url));

        return $this->db->lastInsertId();
    }
}

Shorten::configure(
    require __DIR__ . '/config.php',
    new Database
);

/**
 *  Create URL
 */
var_dump(Shorten::create("http://www.google.com/foo"));
var_Dump(Shorten::create("http://www.google.com/bar"));
var_dump($id = Shorten::create("http://www.google.com/"));

/**
 *  Redirect
 */
$url = Shorten::route($id);
if ($url) {
    header("Location: {$url['link']}");
    exit;
}

