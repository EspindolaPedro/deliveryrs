
namespace core;

class Cache {
    private static $cacheDir = __DIR__ . "/../cache/"; // Caminho absoluto para evitar problemas
    private static $defaultExpiration = 900; // 15 minutos

    // 🔹 Garante que a pasta cache existe antes de salvar
    private static function ensureCacheDir() {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true); // Cria a pasta se não existir
        }
    }

    // 🔹 Salva um dado no cache
    public static function set($key, $data, $expiration = null) {
        self::ensureCacheDir(); // 🔹 Garante que a pasta de cache existe

        $file = self::$cacheDir . md5($key) . ".json";
        $cacheData = [
            'data' => $data,
            'expires' => time() + ($expiration ?? self::$defaultExpiration)
        ];

        file_put_contents($file, json_encode($cacheData)); // Salva o arquivo no cache
    }

    // 🔹 Recupera um dado do cache, se ainda for válido
    public static function get($key) {
        $file = self::$cacheDir . md5($key) . ".json";

        if (!file_exists($file)) return null;

        $cache = json_decode(file_get_contents($file), true);

        if (time() > $cache['expires']) {
            unlink($file); // Remove cache expirado
            return null;
        }

        return $cache['data'];
    }

    // 🔹 Remove um cache específico
    public static function clear($key) {
        $file = self::$cacheDir . md5($key) . ".json";
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // 🔹 Remove todos os caches
    public static function clearAll() {
        $files = glob(self::$cacheDir . '*.json');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
