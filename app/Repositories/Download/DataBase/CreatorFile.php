<?php

namespace App\Repositories\Download\DataBase;

use App\Exceptions\ServerException;
use App\Repositories\Download\CreatorFile as CreatorFileBase;

final class CreatorFile extends CreatorFileBase
{
    private const FILE_NAME = 'danshin_genealogy_db.sql';

    /**
     * @throws ServerException
     */
    public function create(string $pathDirectory): string
    {
        $pathFile = $pathDirectory.self::FILE_NAME;
        $res = exec($this->buildQuery($pathFile));

        if ($res === false) {
            throw new ServerException('An error occurred when creating a dump of the database file');
        }

        return $pathFile;
    }

    /**
     * @throws ServerException
     */
    private function buildQuery(string $pathFile): string
    {
        $dbConnection = 'mysql';

        switch ($dbConnection) {
            case 'mysql':
                $dbUserName = config('database.connections.mysql.username');
                $dbPassword = config('database.connections.mysql.password');
                $dbName = config('database.connections.mysql.database');

                return "mysqldump -u$dbUserName -p$dbPassword --skip-compact $dbName > {$pathFile}";
            default:
                throw new ServerException("Unknown type of database connection: '$dbConnection'");
        }
    }
}
