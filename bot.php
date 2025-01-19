<?php

include __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;
use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use App\WebService\OpenWeatherMap;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Interactions\Command\Command;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$discord = new Discord([
    'token' => $_ENV['DISCORD_TOKEN'],
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT,
//      | Intents::MESSAGE_CONTENT, // Note: MESSAGE_CONTENT is privileged, see https://dis.gd/mcfaq
]);



$discord->on('init', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $command = new Command($discord, [
        'name' => 'tempo',
        'description' => 'Informações sobre uma cidade específica.',
        'options' => [
            [
                'type' => 3, // STRING
                'name' => 'cidade', // Nome da cidade
                'description' => 'O nome da cidade que você quer informações.',
                'required' => true,
            ],
            [
                'type' => 3, // STRING
                'name' => 'uf', // UF da cidade
                'description' => 'UF da cidade que você quer informações.',
                'required' => true,
            ],
        ],
    ]);
    
    $discord->application->commands->save($command);

    $discord->on(Event::INTERACTION_CREATE, function (Interaction $interaction) {
        if ($interaction->data->name === 'tempo') {
            $cidade = $interaction->data->options['cidade']->value; 
            $uf = $interaction->data->options['uf']->value; 
            if(empty($cidade) || empty($uf)){
                $resposta = MessageBuilder::new()->setContent("Por favor, informe a cidade e a UF.");
                $interaction->respondWithMessage($resposta);
                return;
            }

            try{
                $obOpenWeatherMap = new OpenWeatherMap($_ENV['OPENWEATHERMAP_API_KEY']);
                $dadosClima = $obOpenWeatherMap->consultarClimaAtualBR($cidade, $uf);
                if(isset($dadosClima['cod']) && $dadosClima['cod'] == 404 || $dadosClima == null){
                    $resposta = MessageBuilder::new()->setContent("**Cidade não encontrada:** $cidade - $uf \n Por favor, Verifique o Nome/Acentuações.");
                    $interaction->respondWithMessage($resposta);
                    
                }else{
                    $resposta = MessageBuilder::new()->setContent(
                        "Informações sobre a cidade: **$cidade - $uf** \n
                        **Condição**: ".($dadosClima['weather'][0]['description'] ?? 'Desconhecido')." 
                        **Temperatura**: ".($dadosClima['main']['temp'] . ' °C' ?? 'Desconhecido')."
                        **Sensação Térmica**: ".($dadosClima['main']['feels_like'] . ' °C' ?? 'Desconhecido')."
                        **Temperatura Mínima**: ".($dadosClima['main']['temp_min'] . ' °C' ?? 'Desconhecido')."
                        **Temperatura Máxima**: ".($dadosClima['main']['temp_max'] . ' °C' ?? 'Desconhecido')." "
                    );
                    $interaction->respondWithMessage($resposta);
                }
                
            }catch(Exception $e){
                $resposta = MessageBuilder::new()->setContent("Erro ao consultar o clima da cidade: $cidade - $uf");
                $interaction->respondWithMessage($resposta);
            }
        }
    });

});

$discord->run();