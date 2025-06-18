<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ActualizarSalarioMinimo extends Command
{
    protected $signature = 'app:actualizar-salario-minimo';

    protected $description = 'Actualiza el salario mínimo desde la web';

    public function handle()
    {
        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false, // Para evitar errores SSL en local. En producción pon el certificado correcto.
            ]);

            $url = 'https://www.salariominimocolombia.net/';

            $response = $client->get($url);

            if ($response->getStatusCode() !== 200) {
                $this->error('Error HTTP: ' . $response->getStatusCode());
                return 1; // Código error
            }

            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            $nodes = $crawler->filter('span.preciodolar');

            if ($nodes->count() === 0) {
                $this->error('Elemento .preciodolar no encontrado.');
                return 1;
            }

            $texto = $nodes->text();
            $numeric = str_replace(['$', ',', ' '], '', $texto);
            $salarioMinimo = (int) $numeric;

            if ($salarioMinimo <= 0) {
                $this->error('Salario mínimo extraído no válido.');
                return 1;
            }

            Cache::put('salario_minimo', $salarioMinimo, now()->addYear());

            $this->info("✅ Salario mínimo actualizado correctamente: $salarioMinimo");
            return 0; // Éxito

        } catch (\Exception $e) {
            $this->error('❌ Error al obtener salario mínimo: ' . $e->getMessage());
            Log::error('Error al obtener salario mínimo: ' . $e->getMessage());
            return 1;
        }
    }
}
