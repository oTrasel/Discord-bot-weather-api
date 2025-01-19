
# Discord-bot-weather-api

Discord bot no qual consulta uma weather api e retorna a previsão do tempo com base na cidade e UF informada.


## Pré-Requisitos
- Docker
- Chave de API para OpenWeatherMap no qual Conseguirá no site abaixo:
```
https://openweathermap.org/api
```
- Cadastrar o BOT no Discord:
```
https://discord.com/developers/applications
```
- Para configurar o bot, é necessário na aba BOT, dar todas as permissões Privileged Gateway Intents e em Bot Permissions, marcar como Administrador. Após isto, é necessário ir até a OAuth2, no painel OAuth2 URL Generator, marcar a opção BOT, após isto, liberará o Painel Bot Permissions, aonde deve-se marcar também como administrador. Logo Abaixo a este painel, terá Generated URL, a mesma é utilizada para adicionar o bot ao seu servidor.
## Instalação
- Adicionar o BOT no servidor

- Clonar o Repositório
```
git clone https://github.com/oTrasel/Discord-bot-weather-api
```

- Criar o .env baseado no .env-exemple

- Entrar na raiz do projeto e configurar o token discord e API key dentro do .env
```
DISCORD_TOKEN="YOUR BOT TOKEN"
OPENWEATHERMAP_API_KEY="YOUR API KEY OF WEATHER"
```
- Buildar a Imagem docker
```
docker-compose build
```

- Subir o container
```
docker-compose up -d
```
## Modo de Uso

- Em qualquer canal de texto, digite /tempo, aonde abrirá dois parâmetros, CIDADE e UF, o nome da cidade, deve ser preenchido com suas devidas acentuações. Após isto, o bot retornará as informações

- Exemplo de uso
```
/tempo cidade:Porto Alegre uf:RS
```
- Retorno
```
Informações sobre a cidade: Porto Alegre - RS 

Condição: nuvens dispersas 
Temperatura: 31.54 °C
Sensação Térmica: 36.88 °C
Temperatura Mínima: 30.78 °C
Temperatura Máxima: 32.37 °C
```