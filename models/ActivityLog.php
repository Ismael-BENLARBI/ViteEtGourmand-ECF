<?php

class ActivityLog {

    private static function getManager() {
        if (!class_exists('MongoDB\\Driver\\Manager')) {
            return null;
        }

        $dsn = getenv('MONGO_DSN') ?: 'mongodb://127.0.0.1:27017';

        try {
            return new MongoDB\Driver\Manager($dsn);
        } catch (Throwable $e) {
            error_log('NoSQL Manager indisponible: ' . $e->getMessage());
            return null;
        }
    }

    private static function getNamespace() {
        $database = getenv('MONGO_DB') ?: 'vite_et_gourmand';
        $collection = getenv('MONGO_ACTIVITY_COLLECTION') ?: 'logs_activite';
        return $database . '.' . $collection;
    }

    public static function logAuthSuccess(array $user, $ipAddress) {
        $manager = self::getManager();
        if (!$manager) {
            return;
        }

        $timestamp = time();
        $document = [
            'utilisateur_id' => (int)($user['id'] ?? 0),
            'email' => (string)($user['email'] ?? ''),
            'role_id' => (int)($user['role_id'] ?? 0),
            'action' => 'Connexion reussie',
            'ip_address' => (string)($ipAddress ?: 'unknown'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_ts' => $timestamp,
        ];

        try {
            $bulk = new MongoDB\Driver\BulkWrite();
            $bulk->insert($document);
            $manager->executeBulkWrite(self::getNamespace(), $bulk);
        } catch (Throwable $e) {
            error_log('NoSQL log echec: ' . $e->getMessage());
        }
    }

    public static function getRecent($limit = 50) {
        $manager = self::getManager();
        if (!$manager) {
            return [];
        }

        try {
            $query = new MongoDB\Driver\Query([], [
                'sort' => ['created_ts' => -1],
                'limit' => max(1, (int)$limit),
            ]);

            $cursor = $manager->executeQuery(self::getNamespace(), $query);
            $results = [];

            foreach ($cursor as $doc) {
                $row = json_decode(json_encode($doc), true);
                $results[] = [
                    'created_at' => $row['created_at'] ?? '',
                    'email' => $row['email'] ?? '',
                    'role_id' => $row['role_id'] ?? 0,
                    'action' => $row['action'] ?? '',
                    'ip_address' => $row['ip_address'] ?? '',
                ];
            }

            return $results;
        } catch (Throwable $e) {
            error_log('NoSQL lecture echec: ' . $e->getMessage());
            return [];
        }
    }
}
